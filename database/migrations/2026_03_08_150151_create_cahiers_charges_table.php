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
        Schema::create('cahiers_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_stage_id')->constrained('demandes_stage')->onDelete('cascade');
            $table->string('sujet_stage');
            $table->text('description')->nullable();
            $table->string('fichier_path');
            $table->date('date_partage');
            $table->enum('status', ['en_cours', 'soumis', 'valide', 'refuse', 'archive'])
                  ->default('en_cours')
                  ->comment('Statut du cahier des charges');
            $table->decimal('note', 5, 2)->nullable()->comment('Note attribuée (ex: 15.50)');
            
            $table->unsignedTinyInteger('pourcentage_completion')->default(0)->comment('Pourcentage de complétion (0-100)');      
            $table->foreignId('partage_par')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cahiers_charges');
    }
};
