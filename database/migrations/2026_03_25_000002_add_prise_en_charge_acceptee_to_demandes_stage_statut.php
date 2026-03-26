<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE demandes_stage MODIFY COLUMN statut ENUM(
            'brouillon',
            'soumise',
            'en_cours_traitement_rh',
            'refusee_rh',
            'acceptee_rh',
            'affectee_service',
            'prise_en_charge_acceptee',
            'refusee_service',
            'entretien_planifie',
            'entretien_realise',
            'sujet_renseigne',
            'cahier_charges_partage',
            'cloturee'
        ) NOT NULL DEFAULT 'brouillon'");
    }

    public function down(): void
    {
        DB::statement("UPDATE demandes_stage SET statut = 'affectee_service' WHERE statut = 'prise_en_charge_acceptee'");

        DB::statement("ALTER TABLE demandes_stage MODIFY COLUMN statut ENUM(
            'brouillon',
            'soumise',
            'en_cours_traitement_rh',
            'refusee_rh',
            'acceptee_rh',
            'affectee_service',
            'refusee_service',
            'entretien_planifie',
            'entretien_realise',
            'sujet_renseigne',
            'cahier_charges_partage',
            'cloturee'
        ) NOT NULL DEFAULT 'brouillon'");
    }
};
