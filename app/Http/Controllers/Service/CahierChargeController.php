<?php

namespace App\Http\Controllers\Service;

use App\Models\CahierCharge;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CahierChargeController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cahiers = \App\Models\CahierCharge::orderByDesc('date_partage')->get();
        return view('service.cahiers.index', compact('cahiers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CahierCharge $cahier)
    {
        return view('service.cahiers.show', ['cahier' => $cahier]);
    }

    public function download(CahierCharge $cahier)
    {
        $path = $cahier->fichier_path;

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->download(
            storage_path('app/public/' . $path),
            basename($path)
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CahierCharge $cahier)
    {
        return view('service.cahiers.edit', ['cahier' => $cahier]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CahierCharge $cahier)
    {
        $validated = $request->validate([
            'sujet_stage' => 'required|string|max:255',
            'description' => 'nullable|string|max:3000',
            'fichier_path' => 'nullable|file|max:10240',
        ]);

        $data = [
            'sujet_stage' => $validated['sujet_stage'],
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('fichier_path')) {
            if ($cahier->fichier_path && Storage::disk('public')->exists($cahier->fichier_path)) {
                Storage::disk('public')->delete($cahier->fichier_path);
            }

            $data['fichier_path'] = $request->file('fichier_path')->store('cahiers_charges', 'public');
        }

        $cahier->update($data);

        return redirect()->route('service.cahiers.index')
            ->with('success', 'Cahier de charge mis a jour avec succes.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CahierCharge $cahier)
    {
        $cahier->delete();
        return redirect()->route('service.cahiers.index')->with('success', 'Cahier de charge supprimé avec succès.');
    }
}
