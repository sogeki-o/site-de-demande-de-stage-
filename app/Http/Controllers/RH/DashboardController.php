<?php

namespace App\Http\Controllers\RH;

use App\Http\Controllers\Controller;
use App\Mail\DemandeAffecteeServiceMail;
use App\Mail\DemandeDecisionRhMail;
use App\Models\DemandeStage;
use App\Models\ServiceUCA;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    private function applyPeriodFilter($query, ?string $periode)
    {
        return match ($periode) {
            'today' => $query->whereDate('created_at', now()->toDateString()),
            'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month),
            'year' => $query->whereYear('created_at', now()->year),
            default => $query,
        };
    }

    private function buildDashboardData(Request $request): array
    {
        $periode = $request->get('periode', 'all');
        $baseQuery = DemandeStage::query();
        $this->applyPeriodFilter($baseQuery, $periode);

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'en_attente' => (clone $baseQuery)->where('statut', 'soumise')->count(),
            'acceptees' => (clone $baseQuery)->whereIn('statut', ['acceptee_rh', 'affectee_service'])->count(),
            'refusees' => (clone $baseQuery)->where('statut', 'refusee_rh')->count(),
            'par_service' => (clone $baseQuery)->selectRaw('service_uca_id, COUNT(*) as total')
                ->groupBy('service_uca_id')
                ->with('service')
                ->get(),
        ];

        $demandesRecentes = (clone $baseQuery)->with(['user', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return compact('stats', 'demandesRecentes', 'periode');
    }

    public function index(Request $request)
    {
        return view('rh.dashboard', $this->buildDashboardData($request));
    }

    public function demandes(Request $request)
    {
        $query = DemandeStage::with(['user', 'service', 'traitePar']);

        $this->applyPeriodFilter($query, $request->get('periode', 'all'));

        $query->when($request->filled('statut'), function ($q) use ($request) {
            $q->where('statut', $request->statut);
        });

        $query->when($request->filled('service_uca_id'), function ($q) use ($request) {
            $q->where('service_uca_id', $request->service_uca_id);
        });

        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($inner) use ($search) {
                $inner->where('etablissement', 'like', "%{$search}%")
                    ->orWhere('filiere', 'like', "%{$search}%")
                    ->orWhere('niveau_etude', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        });

        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['created_at', 'date_soumission', 'statut'];
        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'created_at';
        }

        $demandes = $query->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();
        $services = ServiceUCA::where('actif', true)->orderBy('nom')->get();
        $stats = $this->buildDashboardData($request)['stats'];

        return view('rh.demandes.index', compact('demandes', 'services', 'stats'));
    }

    public function show(DemandeStage $demandeStage)
    {
        $demandeStage->load(['user', 'service', 'traitePar']);

        $services = ServiceUCA::where('actif', true)->orderBy('nom')->get();

        $history = collect([
            [
                'date' => $demandeStage->date_soumission ?? $demandeStage->created_at,
                'label' => 'Demande soumise par le demandeur',
            ],
            $demandeStage->date_traitement_rh ? [
                'date' => $demandeStage->date_traitement_rh,
                'label' => 'Traitement RH effectue',
            ] : null,
            $demandeStage->date_affectation ? [
                'date' => $demandeStage->date_affectation,
                'label' => 'Demande affectee au service',
            ] : null,
            ($demandeStage->statut === 'refusee_rh' && $demandeStage->motif_refus) ? [
                'date' => $demandeStage->updated_at,
                'label' => 'Refus RH motive',
            ] : null,
        ])->filter()->sortByDesc('date')->values();

        return view('rh.demandes.show', [
            'demande' => $demandeStage,
            'services' => $services,
            'history' => $history,
            'motifRefusObligatoire' => true,
        ]);
    }

    public function cv(DemandeStage $demandeStage)
    {
        $user = Auth::user();
        $canAccess = $user
            && ($user->isAdmin() || $user->isRh() || $demandeStage->user_id === $user->id);

        if (! $canAccess) {
            abort(403);
        }

        if (! $demandeStage->cv_path || ! Storage::disk('public')->exists($demandeStage->cv_path)) {
            abort(404);
        }

        return response()->file(storage_path('app/public/' . $demandeStage->cv_path));
    }

    public function accepter(Request $request, DemandeStage $demandeStage)
    {
        if ($demandeStage->statut !== 'soumise') {
            return back()->with('error', 'Cette demande a deja ete traitee.');
        }

        $validated = $request->validate([
            'service_uca_id' => 'required|exists:services_uca,id',
            'documents_demande' => 'nullable|string|max:2000',
        ]);

        $service = ServiceUCA::find($validated['service_uca_id']);

        $serviceEmails = \App\Models\User::where('service_uca_id', $validated['service_uca_id'])
            ->where('role', 'service')
            ->where('actif', true)
            ->pluck('email')
            ->filter()
            ->all();

        if (! empty($service?->responsable_email)) {
            $serviceEmails[] = $service->responsable_email;
        }

        $serviceEmails = array_values(array_unique($serviceEmails));

        if (empty($serviceEmails)) {
            return back()->with('error', 'Aucune adresse email disponible pour notifier le service.');
        }

        $demandeStage->update([
            'statut' => 'affectee_service',
            'service_uca_id' => $validated['service_uca_id'],
            'motif_refus' => null,
            'documents_demande' => $validated['documents_demande'] ?? null,
            'date_traitement_rh' => now(),
            'date_affectation' => now(),
            'traite_par' => Auth::id(),
        ]);

        $demandeStage->load(['service', 'user']);

        if (!empty($serviceEmails)) {
            Mail::to($serviceEmails)->send(new DemandeAffecteeServiceMail($demandeStage));
        }

        if (! empty($demandeStage->user?->email)) {
            Mail::to($demandeStage->user->email)->send(new DemandeDecisionRhMail($demandeStage, 'acceptee'));
        }

        return redirect()
            ->route('rh.demandes.show', $demandeStage)
            ->with('success', 'La demande a ete acceptee puis affectee au service. Notification envoyee au service.');
    }

    public function refuser(Request $request, DemandeStage $demandeStage)
    {
        if ($demandeStage->statut !== 'soumise') {
            return back()->with('error', 'Cette demande a deja ete traitee.');
        }

        $validated = $request->validate([
            'motif_refus' => 'required|string|max:1000',
        ]);

        $demandeStage->update([
            'statut' => 'refusee_rh',
            'motif_refus' => $validated['motif_refus'],
            'date_traitement_rh' => now(),
            'traite_par' => Auth::id(),
        ]);

        $demandeStage->load(['user', 'service']);

        if (! empty($demandeStage->user?->email)) {
            Mail::to($demandeStage->user->email)->send(new DemandeDecisionRhMail($demandeStage, 'refusee'));
        }

        return redirect()
            ->route('rh.demandes.show', $demandeStage)
            ->with('success', 'La demande a ete refusee par le RH.');
    }

    public function export(Request $request)
    {
        $fileName = 'demandes_stage_' . now()->format('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Demandeur', 'Email', 'Service', 'Statut', 'Traite par', 'Date de soumission', 'Date traitement RH']);

            $query = DemandeStage::with(['user', 'service', 'traitePar']);
            $this->applyPeriodFilter($query, $request->get('periode', 'all'));

            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
            }

            if ($request->filled('service_uca_id')) {
                $query->where('service_uca_id', $request->service_uca_id);
            }

            $query
                ->orderBy('created_at', 'desc')
                ->chunk(200, function ($demandes) use ($handle) {
                    foreach ($demandes as $demande) {
                        fputcsv($handle, [
                            $demande->id,
                            trim(($demande->user->prenom ?? '') . ' ' . ($demande->user->nom ?? '')),
                            $demande->user->email ?? '',
                            $demande->service->nom ?? '',
                            $demande->statut,
                            trim(($demande->traitePar->prenom ?? '') . ' ' . ($demande->traitePar->nom ?? '')),
                            optional($demande->date_soumission)->format('Y-m-d H:i'),
                            optional($demande->date_traitement_rh)->format('Y-m-d H:i'),
                        ]);
                    }
                });

            fclose($handle);
        }, Response::HTTP_OK, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);

        return $response;
    }
}

