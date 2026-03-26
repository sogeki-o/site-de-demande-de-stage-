<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Récupérer la dernière demande affectée à un service
        $latestDemande = DB::table('demandes_stage')
            ->whereNotNull('service_uca_id')
            ->where('statut', '!=', 'soumise')
            ->latest('id')
            ->first();

        if ($latestDemande) {
            // Mettre à jour tous les utilisateurs service sans service_uca_id
            DB::table('users')
                ->where('role', 'service')
                ->whereNull('service_uca_id')
                ->update(['service_uca_id' => $latestDemande->service_uca_id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: remettre à NULL (optionnel)
        DB::table('users')
            ->where('role', 'service')
            ->update(['service_uca_id' => null]);
    }
};
