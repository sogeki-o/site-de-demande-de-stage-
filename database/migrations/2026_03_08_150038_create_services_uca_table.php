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
        Schema::create('services_uca', function (Blueprint $table) {
           $table->id();
            $table->string('nom');
            $table->string('description')->nullable();
            $table->string('responsable_nom')->nullable();
            $table->string('responsable_email')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_uca');
    }
};
