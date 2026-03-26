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
        Schema::table('demandes_stage', function (Blueprint $table) {
            $table->text('documents_demande')->nullable()->after('motif_refus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes_stage', function (Blueprint $table) {
            $table->dropColumn('documents_demande');
        });
    }
};
