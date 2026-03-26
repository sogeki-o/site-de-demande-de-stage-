<?php

namespace App\Http\Controllers\Demandeur;

use App\Models\DemandeStage;
use App\Http\Controllers\Controller;
use App\Mail\DemandeStageSoumiseRhMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\ServiceUCA;
use App\Models\User;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $demandes = DemandeStage::with(['service', 'entretien', 'cahierCharge'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('demandeur.demandes.index', compact('demandes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = ServiceUCA::orderBy('nom')->get();
        $allowedCvMimes = config('app.allowed_cv_mimes', 'pdf,doc,docx');
        $allowedCvAccept = collect(explode(',', $allowedCvMimes))
            ->map(fn ($ext) => '.' . trim($ext))
            ->implode(',');

        return view('demandeur.demandes.create', compact('services', 'allowedCvMimes', 'allowedCvAccept'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $allowedCvMimes = config('app.allowed_cv_mimes', 'pdf,doc,docx');

        $request->validate([
            'niveau_etude' => 'required|string',
            'etablissement' => 'required|string',
            'filiere' => 'required|string',
            'duree_stage' => 'required|integer|min:1|max:12',
            'date_debut_prevue' => 'required|date',
            'service_uca_id' => 'required|exists:services_uca,id',
            'cv_path' => "required|file|mimes:{$allowedCvMimes}|max:10240",
        ]);

        $cvPath = null;
        if ($request->hasFile('cv_path')) {
            $cvPath = $request->file('cv_path')->store('cv', 'public');
        }

        $demande = DemandeStage::create([
            'user_id' => Auth::id(),
            'niveau_etude' => $request->niveau_etude,
            'etablissement' => $request->etablissement,
            'filiere' => $request->filiere,
            'duree_stage' => $request->duree_stage,
            'date_debut_prevue' => $request->date_debut_prevue,
            'service_uca_id' => $request->service_uca_id,
            'cv_path' => $cvPath,
            'statut' => 'soumise',
            'date_soumission' => now(),
        ]);

        $rhEmails = User::where('role', 'rh')
            ->where('actif', true)
            ->pluck('email')
            ->filter()
            ->all();

        if (! empty($rhEmails)) {
            Mail::to($rhEmails)->send(new DemandeStageSoumiseRhMail($demande));
        }

        return redirect()->route('demandeur.demandes.index')->with('success', 'Demande créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DemandeStage $demandeStage)
    {
        if ($demandeStage->user_id !== Auth::id()) {
            abort(403);
        }

        $demandeStage->load(['service', 'entretien', 'cahierCharge']);

        return view('demandeur.demandes.show', [
            'demande' => $demandeStage,
        ]);
    }

    public function downloadCahier(Request $request, DemandeStage $demandeStage)
    {
        $isSignedAccess = $request->hasValidSignature();

        if (! $isSignedAccess) {
            if (! Auth::check() || $demandeStage->user_id !== Auth::id()) {
                abort(403);
            }
        }

        $demandeStage->load('cahierCharge');
        $path = $demandeStage->cahierCharge?->fichier_path;

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->file(storage_path('app/public/' . $path));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DemandeStage $demandeStage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DemandeStage $demandeStage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DemandeStage $demandeStage)
    {
        //
    }
}
