<?php

namespace App\Console\Commands;

use App\Models\DemandeStage;
use App\Models\User;
use Illuminate\Console\Command;

class FixServiceUserId extends Command
{
    protected $signature = 'fix:service-user-id';
    protected $description = 'Assign service_uca_id to service users based on assigned demands';

    public function handle()
    {
        // Récupérer la dernière demande affectée à un service
        $latestDemande = DemandeStage::whereNotNull('service_uca_id')
            ->where('statut', '!=', 'soumise')
            ->latest('id')
            ->first();

        if (!$latestDemande) {
            $this->error('Aucune demande affectée trouvée.');
            return;
        }

        $serviceId = $latestDemande->service_uca_id;
        $this->info("Demande trouvée: ID {$latestDemande->id}, Service ID: {$serviceId}");

        // Récupérer les utilisateurs service sans service_uca_id
        $serviceUsersWithoutId = User::where('role', 'service')
            ->whereNull('service_uca_id')
            ->get();

        if ($serviceUsersWithoutId->isEmpty()) {
            $this->info('Aucun utilisateur service sans service_uca_id trouvé.');
            return;
        }

        // Mettre à jour tous les utilisateurs service sans service_uca_id
        foreach ($serviceUsersWithoutId as $user) {
            $user->update(['service_uca_id' => $serviceId]);
            $this->info("✓ Utilisateur {$user->prenom} {$user->nom} (ID: {$user->id}) assigné au service ID: {$serviceId}");
        }

        $this->info('Service users mis à jour avec succès!');
    }
}
