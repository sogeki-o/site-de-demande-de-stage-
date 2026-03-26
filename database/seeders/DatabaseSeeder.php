<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ServiceUCA;
use App\Models\DemandeStage;
use App\Models\Entretien;
use App\Models\CahierCharge;
use App\Models\Notification;
use App\Models\Historique;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            ServicesUCASeeder::class,
        ]);

        // Créer des services UCA
        $services = ServiceUCA::factory(10)->create();

        // Créer des utilisateurs avec différents rôles
        $admins = User::factory(2)->admin()->create();
        $rhUsers = User::factory(3)->rh()->create();
        $serviceUsers = User::factory(5)->service()->create([
            'service_uca_id' => $services->random()->id
        ]);
        $demandeurs = User::factory(20)->demandeur()->create();

        // Créer des demandes de stage
        $demandes = DemandeStage::factory(50)->create([
            'user_id' => $demandeurs->random()->id,
            'service_uca_id' => $services->random()->id,
            'traite_par' => $rhUsers->random()->id,
        ]);

        // Créer des entretiens pour certaines demandes acceptées
        $demandesAcceptees = $demandes->where('statut', 'acceptee_rh')->take(20);
        foreach ($demandesAcceptees as $demande) {
            Entretien::factory()->create([
                'demande_stage_id' => $demande->id,
                'users_id' => $serviceUsers->random()->id,
            ]);
        }

        // Créer des cahiers de charge pour certaines demandes en cours
        $demandesEnCours = $demandes->where('statut', 'affectee_service')->take(15);
        foreach ($demandesEnCours as $demande) {
            CahierCharge::factory()->create([
                'demande_stage_id' => $demande->id,
                'partage_par' => $serviceUsers->random()->id,
            ]);
        }

        // Créer un utilisateur de test
        User::factory()->create([
            'nom' => 'Admin',
            'prenom' => 'Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'actif' => true,
        ]);
    }
}
