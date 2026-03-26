<?php

namespace App\Http\Controllers\Demandeur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $demandes = $user->demandesStage()
            ->with(['service', 'entretien', 'cahierCharge'])
            ->orderBy('created_at', 'desc')
            ->get();

        $notifications = collect();

        foreach ($demandes as $demande) {
            if (in_array($demande->statut, ['refusee_rh', 'refusee_service'], true)) {
                $notifications->push((object) [
                    'date' => $demande->updated_at,
                    'message' => "Votre demande #{$demande->id} a ete refusee.",
                ]);
            }

            if (in_array($demande->statut, ['acceptee_rh', 'affectee_service'], true)) {
                $notifications->push((object) [
                    'date' => $demande->updated_at,
                    'message' => "Votre demande #{$demande->id} a ete acceptee et est en cours de traitement.",
                ]);
            }

            if ($demande->entretien && $demande->entretien->date_heure) {
                $notifications->push((object) [
                    'date' => $demande->entretien->updated_at ?? $demande->entretien->created_at,
                    'message' => "Entretien planifie pour la demande #{$demande->id} le " . $demande->entretien->date_heure->format('d/m/Y H:i') . '.',
                ]);
            }

            if ($demande->cahierCharge) {
                $notifications->push((object) [
                    'date' => $demande->cahierCharge->updated_at ?? $demande->cahierCharge->created_at,
                    'message' => "Le cahier des charges de la demande #{$demande->id} est disponible au telechargement.",
                ]);
            }
        }

        $notifications = $notifications->sortByDesc('date')->values();
        $notificationsNonLues = $notifications->count();
        
        return view('demandeur.dashboard', compact('demandes', 'notifications', 'notificationsNonLues'));
    }
}
