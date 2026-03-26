<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CahierCharge extends Model
{
    use HasFactory;

    protected $table = 'cahiers_charges';
    
    protected $fillable = [
        'demande_stage_id', 'sujet_stage', 'description', 'fichier_path', 
        'date_partage', 'partage_par','status','note','pourcentage_completion'
    ];

    protected $casts = [
        'note' => 'decimal:2',
        'pourcentage_completion' => 'integer',
        'date_partage' => 'date',
    ];

    public function demandeStage()
    {
        return $this->belongsTo(DemandeStage::class);
    }

    public function partagePar()
    {
        return $this->belongsTo(User::class, 'partage_par');
    }
}
