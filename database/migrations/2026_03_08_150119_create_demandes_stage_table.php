<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demandes_stage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('niveau_etude');
            $table->string('etablissement');
            $table->string('filiere');
            $table->integer('duree_stage');
            $table->date('date_debut_prevue');
            $table->foreignId('service_uca_id')->constrained('services_uca');
            $table->string('cv_path');
            $table->enum('statut', [
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
            ])->default('brouillon');
            $table->text('motif_refus')->nullable();
            $table->timestamp('date_soumission')->nullable();
            $table->timestamp('date_traitement_rh')->nullable();
            $table->timestamp('date_affectation')->nullable();
            $table->foreignId('traite_par')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_stage');
    }
};
