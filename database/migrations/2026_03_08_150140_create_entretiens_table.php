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
        Schema::create('entretiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_stage_id')->constrained('demandes_stage')->onDelete('cascade');
            $table->datetime('date_heure');
            $table->string('lieu')->nullable();
            $table->string('lien_reunion')->nullable();
            $table->text('notes')->nullable();
            $table->text('documents_demande')->nullable();
            $table->boolean('realise')->default(false);
            $table->foreignId('users_id')->constrained()->name('planifier_par');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entretiens');
    }
};
