<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceUCA;
use App\Http\Controllers\Controller;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
         $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $services = ServiceUCA::withCount(['demandes', 'users'])
                              ->orderBy('nom')
                              ->paginate(15);
        
        return view('admin.services.index', compact('services'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.services.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:services_uca',
            'description' => 'nullable|string',
            'responsable_nom' => 'nullable|string|max:255',
            'responsable_email' => 'nullable|email',
            'actif' => 'sometimes|boolean'
        ]);

        $validated['actif'] = $request->boolean('actif');

        $service = ServiceUCA::create($validated);

        AuditLogger::log('admin.service.create', 'ServiceUCA', $service->id, 'Creation d\'un service', [
            'nom' => $service->nom,
        ]);

        return redirect()->route('admin.services.index')
                        ->with('success', 'Service créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceUCA $serviceUCA)
    {
        $serviceUCA->load(['demandes' => function($query) {
            $query->latest()->limit(10);
        }, 'users']);
        
        return view('admin.services.show', compact('serviceUCA'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceUCA $serviceUCA)
    {
        $serviceUCA->loadCount(['demandes', 'users']);

        return view('admin.services.edit', compact('serviceUCA'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceUCA $serviceUCA)
    {
         $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:services_uca,nom,' . $serviceUCA->id,
            'description' => 'nullable|string',
            'responsable_nom' => 'nullable|string|max:255',
            'responsable_email' => 'nullable|email',
            'actif' => 'sometimes|boolean'
        ]);

        $validated['actif'] = $request->boolean('actif');

        $serviceUCA->update($validated);

        AuditLogger::log('admin.service.update', 'ServiceUCA', $serviceUCA->id, 'Mise a jour d\'un service', [
            'nom' => $serviceUCA->nom,
        ]);

        return redirect()->route('admin.services.index')
                        ->with('success', 'Service mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceUCA $serviceUCA)
    {
        $serviceId = $serviceUCA->id;
        $serviceNom = $serviceUCA->nom;
        $usersCount = $serviceUCA->users()->count();
        $demandesSupprimees = 0;

        if ($usersCount > 0) {
            return back()->with('error', 'Suppression impossible: ce service contient des utilisateurs associes.');
        }

        try {
            DB::transaction(function () use ($serviceUCA, &$demandesSupprimees) {
                $demandesSupprimees = $serviceUCA->demandes()->count();
                $serviceUCA->demandes()->delete();
                $serviceUCA->delete();
            });
        } catch (QueryException $exception) {
            return back()->with('error', 'Suppression impossible: ce service est encore utilisé par d\'autres données.');
        }

        AuditLogger::log('admin.service.delete', 'ServiceUCA', $serviceId, 'Suppression d\'un service', [
            'nom' => $serviceNom,
            'users_detaches' => 0,
            'demandes_supprimees' => $demandesSupprimees,
        ]);

        return redirect()->route('admin.services.index')
                        ->with('success', 'Service supprime avec succes. Utilisateurs dissocies: 0, demandes supprimees: ' . $demandesSupprimees . '.');
    }
}
