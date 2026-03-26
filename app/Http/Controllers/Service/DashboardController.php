<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Mail\CahierChargePartageMail;
use App\Mail\DemandeRefuseeServiceMail;
use App\Mail\EntretienConvocationMail;
use App\Models\CahierCharge;
use App\Models\DemandeStage;
use App\Models\Entretien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    private function ensureServiceAccess(DemandeStage $demandeStage): void
    {
        if ($demandeStage->service_uca_id !== Auth::user()->service_uca_id) {
            abort(403);
        }
    }

    public function index()
    {
        $serviceId = Auth::user()->service_uca_id;

        $stats = [
            'total' => DemandeStage::where('service_uca_id', $serviceId)->count(),
            'nouvelles' => DemandeStage::where('service_uca_id', $serviceId)
                ->where('statut', 'affectee_service')
                ->count(),
            'en_cours' => DemandeStage::where('service_uca_id', $serviceId)
                ->whereIn('statut', ['prise_en_charge_acceptee', 'entretien_planifie', 'entretien_realise', 'sujet_renseigne', 'cahier_charges_partage'])
                ->count(),
            'cloturees' => DemandeStage::where('service_uca_id', $serviceId)
                ->where('statut', 'cloturee')
                ->count(),
        ];

        $demandesRecentes = DemandeStage::with(['user', 'service'])
            ->where('service_uca_id', $serviceId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $selectedDemande = request()->filled('demande')
            ? DemandeStage::with(['user', 'service', 'entretien', 'cahierCharge', 'traitePar'])
                ->where('service_uca_id', $serviceId)
                ->find(request('demande'))
            : null;

        $history = collect();
        if ($selectedDemande) {
            $history = collect([
                [
                    'date' => $selectedDemande->date_soumission ?? $selectedDemande->created_at,
                    'label' => 'Demande soumise par le demandeur',
                ],
                $selectedDemande->date_affectation ? [
                    'date' => $selectedDemande->date_affectation,
                    'label' => 'Demande affectee au service',
                ] : null,
                $selectedDemande->entretien ? [
                    'date' => $selectedDemande->entretien->date_heure,
                    'label' => 'Entretien planifie',
                ] : null,
                ($selectedDemande->entretien && $selectedDemande->entretien->realise) ? [
                    'date' => $selectedDemande->entretien->updated_at,
                    'label' => 'Entretien marque comme realise',
                ] : null,
                ($selectedDemande->statut === 'sujet_renseigne') ? [
                    'date' => $selectedDemande->updated_at,
                    'label' => 'Sujet de stage renseigne',
                ] : null,
                $selectedDemande->cahierCharge ? [
                    'date' => $selectedDemande->cahierCharge->date_partage ?? $selectedDemande->cahierCharge->created_at,
                    'label' => 'Cahier des charges partage avec le demandeur',
                ] : null,
                ($selectedDemande->statut === 'cloturee') ? [
                    'date' => $selectedDemande->updated_at,
                    'label' => 'Demande cloturee par le service',
                ] : null,
                ($selectedDemande->statut === 'refusee_service') ? [
                    'date' => $selectedDemande->updated_at,
                    'label' => 'Prise en charge refusee par le service',
                ] : null,
            ])->filter()->sortByDesc('date')->values();
        }

        return view('service.dashboard', compact('stats', 'demandesRecentes', 'selectedDemande', 'history'));
    }

    public function demandes(Request $request)
    {
        $serviceId = Auth::user()->service_uca_id;

        $demandes = DemandeStage::with(['user', 'service'])
            ->where('service_uca_id', $serviceId)
            ->when($request->filled('statut'), function ($query) use ($request) {
                if ($request->statut === 'en_cours') {
                    $query->whereIn('statut', ['prise_en_charge_acceptee', 'entretien_planifie', 'entretien_realise', 'sujet_renseigne', 'cahier_charges_partage']);
                    return;
                }

                $query->where('statut', $request->statut);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('etablissement', 'like', "%{$search}%")
                        ->orWhere('niveau_etude', 'like', "%{$search}%")
                        ->orWhere('filiere', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('nom', 'like', "%{$search}%")
                                ->orWhere('prenom', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('service.demandes.index', compact('demandes'));
    }

    public function show(DemandeStage $demandeStage)
    {
        $this->ensureServiceAccess($demandeStage);

        $demandeStage->load(['user', 'service', 'entretien', 'cahierCharge', 'traitePar']);

        $history = collect([
            [
                'date' => $demandeStage->date_soumission ?? $demandeStage->created_at,
                'label' => 'Demande soumise par le demandeur',
            ],
            $demandeStage->date_affectation ? [
                'date' => $demandeStage->date_affectation,
                'label' => 'Demande affectee au service',
            ] : null,
            $demandeStage->entretien ? [
                'date' => $demandeStage->entretien->date_heure,
                'label' => 'Entretien planifie',
            ] : null,
            ($demandeStage->entretien && $demandeStage->entretien->realise) ? [
                'date' => $demandeStage->entretien->updated_at,
                'label' => 'Entretien marque comme realise',
            ] : null,
            ($demandeStage->statut === 'sujet_renseigne') ? [
                'date' => $demandeStage->updated_at,
                'label' => 'Sujet de stage renseigne',
            ] : null,
            $demandeStage->cahierCharge ? [
                'date' => $demandeStage->cahierCharge->date_partage ?? $demandeStage->cahierCharge->created_at,
                'label' => 'Cahier des charges partage avec le demandeur',
            ] : null,
            ($demandeStage->statut === 'cloturee') ? [
                'date' => $demandeStage->updated_at,
                'label' => 'Demande cloturee par le service',
            ] : null,
            ($demandeStage->statut === 'refusee_service') ? [
                'date' => $demandeStage->updated_at,
                'label' => 'Prise en charge refusee par le service',
            ] : null,
        ])->filter()->sortByDesc('date')->values();

        return view('service.demandes.show', [
            'demande' => $demandeStage,
            'history' => $history,
        ]);
    }

    public function accepter(DemandeStage $demandeStage)
    {
        $this->ensureServiceAccess($demandeStage);

        if ($demandeStage->statut === 'refusee_service') {
            return back()->with('error', 'Cette demande a deja ete refusee.');
        }

        $demandeStage->update([
            'statut' => 'prise_en_charge_acceptee',
            'traite_par' => Auth::id(),
            'motif_refus' => null,
        ]);

        return back()->with('success', 'Prise en charge acceptee. Vous pouvez maintenant planifier l\'entretien.');
    }

    public function refuser(Request $request, DemandeStage $demandeStage)
    {
        $this->ensureServiceAccess($demandeStage);

        $validated = $request->validate([
            'motif_refus' => 'required|string|max:1000',
        ]);

        $demandeStage->update([
            'statut' => 'refusee_service',
            'motif_refus' => $validated['motif_refus'],
            'traite_par' => Auth::id(),
        ]);

        $demandeStage->loadMissing(['user', 'service']);

        if (! empty($demandeStage->user?->email)) {
            Mail::to($demandeStage->user->email)->send(new DemandeRefuseeServiceMail($demandeStage));
        }

        return back()->with('success', 'Demande refusee par le service.');
    }

    public function planifierEntretien(Request $request, DemandeStage $demandeStage)
    {
        $this->ensureServiceAccess($demandeStage);

        $demandeStage->loadMissing('traitePar');
        $serviceAccepted = $demandeStage->statut === 'prise_en_charge_acceptee'
            || ($demandeStage->statut === 'affectee_service'
                && $demandeStage->traitePar
                && $demandeStage->traitePar->role === 'service');
        $alreadyInWorkflow = in_array($demandeStage->statut, [
            'entretien_planifie',
            'entretien_realise',
            'sujet_renseigne',
            'cahier_charges_partage',
        ], true);

        if (! $serviceAccepted && ! $alreadyInWorkflow) {
            return back()->with('error', 'Vous devez d\'abord accepter la prise en charge avant de planifier l\'entretien.');
        }

        $validated = $request->validate([
            'date_heure' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'lien_reunion' => 'nullable|url|max:255',
        ]);

        $entretien = Entretien::updateOrCreate(
            ['demande_stage_id' => $demandeStage->id],
            [
                'date_heure' => $validated['date_heure'],
                'lieu' => $validated['lieu'] ?? null,
                'lien_reunion' => $validated['lien_reunion'] ?? null,
                'documents_demande' => $demandeStage->documents_demande,
                'users_id' => Auth::id(),
                'realise' => false,
            ]
        );

        $demandeStage->update([
            'statut' => 'entretien_planifie',
            'traite_par' => Auth::id(),
        ]);

        Mail::to($demandeStage->user->email)->send(new EntretienConvocationMail($demandeStage, $entretien));

        return back()->with('success', 'Entretien planifie et email de convocation envoye.');
    }

    public function marquerEntretienRealise(DemandeStage $demandeStage)
    {
        $this->ensureServiceAccess($demandeStage);

        $entretien = $demandeStage->entretien;
        if (! $entretien) {
            return back()->with('error', 'Aucun entretien planifie pour cette demande.');
        }

        $entretien->update([
            'realise' => true,
        ]);

        $demandeStage->update([
            'statut' => 'entretien_realise',
            'traite_par' => Auth::id(),
        ]);

        return back()->with('success', 'Entretien marque comme realise.');
    }

    public function renseignerSujet(Request $request, DemandeStage $demandeStage)
    {
        $this->ensureServiceAccess($demandeStage);

        $validated = $request->validate([
            'sujet_stage' => 'required|string|max:255',
            'description_sujet' => 'nullable|string|max:2000',
        ]);

        $entretien = $demandeStage->entretien;
        if (! $entretien) {
            return back()->with('error', 'Vous devez d\'abord planifier l\'entretien.');
        }

        if (! $entretien->realise) {
            return back()->with('error', 'Vous devez d\'abord marquer l\'entretien comme realise.');
        }

        $description = $validated['description_sujet'] ?? '';
        $entretien->update([
            'notes' => "Sujet: {$validated['sujet_stage']}\n" . $description,
        ]);

        $demandeStage->update([
            'statut' => 'sujet_renseigne',
            'traite_par' => Auth::id(),
        ]);

        return back()->with('success', 'Sujet de stage renseigne.');
    }

    public function partagerCahier(Request $request, DemandeStage $demandeStage)
    {
        $this->ensureServiceAccess($demandeStage);

        $validated = $request->validate([
            'sujet_stage' => 'required|string|max:255',
            'description' => 'nullable|string|max:3000',
            'fichier_path' => 'required|file|mimes:pdf|max:10240',
        ]);

        $filePath = $request->file('fichier_path')->store('cahiers_charges', 'public');

        $cahierCharge = CahierCharge::updateOrCreate(
            ['demande_stage_id' => $demandeStage->id],
            [
                'sujet_stage' => $validated['sujet_stage'],
                'description' => $validated['description'] ?? null,
                'fichier_path' => $filePath,
                'date_partage' => now()->toDateString(),
                'partage_par' => Auth::id(),
                'status' => 'soumis',
                'pourcentage_completion' => 100,
            ]
        );

        $demandeStage->update([
            'statut' => 'cahier_charges_partage',
            'traite_par' => Auth::id(),
        ]);

        $demandeStage->loadMissing(['user', 'service']);

        if (! empty($demandeStage->user?->email)) {
            Mail::to($demandeStage->user->email)->send(new CahierChargePartageMail($demandeStage, $cahierCharge));
        }

        return back()->with('success', 'Cahier des charges partage avec succes. Email envoye au demandeur.');
    }

    public function downloadCahier(DemandeStage $demandeStage)
    {
        $this->ensureServiceAccess($demandeStage);

        $demandeStage->load('cahierCharge');
        $path = $demandeStage->cahierCharge?->fichier_path;

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->file(storage_path('app/public/' . $path));
    }

    public function cloturer(DemandeStage $demandeStage)
    {
        $this->ensureServiceAccess($demandeStage);

        if ($demandeStage->statut === 'cloturee') {
            return back()->with('error', 'Cette demande est deja cloturee.');
        }

        if ($demandeStage->statut !== 'cahier_charges_partage') {
            return back()->with('error', 'La demande peut etre cloturee uniquement apres le partage du cahier des charges.');
        }

        $demandeStage->update([
            'statut' => 'cloturee',
            'traite_par' => Auth::id(),
        ]);

        return back()->with('success', 'La demande a ete cloturee avec succes.');
    }
}
