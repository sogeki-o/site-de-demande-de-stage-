<?php

namespace App\Http\Controllers;

use App\Models\DemandeStage;
use App\Models\User;
use Illuminate\Http\Response;

class DebugController extends Controller
{
    public function fixServiceUserId()
    {
        // Récupérer la dernière demande affectée à un service
        $latestDemande = DemandeStage::whereNotNull('service_uca_id')
            ->where('statut', '!=', 'soumise')
            ->latest('id')
            ->first();

        if (!$latestDemande) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune demande affectée trouvée.',
            ]);
        }

        $serviceId = $latestDemande->service_uca_id;

        // Récupérer les utilisateurs service sans service_uca_id
        $serviceUsers = User::where('role', 'service')
            ->whereNull('service_uca_id')
            ->get();

        if ($serviceUsers->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Aucun utilisateur service sans service_uca_id trouvé.',
                'updated' => 0,
            ]);
        }

        // Mettre à jour tous les utilisateurs service sans service_uca_id
        $updated = User::where('role', 'service')
            ->whereNull('service_uca_id')
            ->update(['service_uca_id' => $serviceId]);

        return response()->json([
            'success' => true,
            'message' => "✓ {$updated} utilisateur(s) service mis à jour avec le service ID: {$serviceId}",
            'updated' => $updated,
            'service_id' => $serviceId,
        ]);
    }
}
