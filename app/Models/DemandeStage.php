<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeStage extends Model
{
      use HasFactory;

    protected $table = 'demandes_stage';
    
    protected $fillable = [
        'user_id', 'niveau_etude', 'etablissement', 'filiere', 'duree_stage',
        'date_debut_prevue', 'service_uca_id', 'cv_path', 'statut', 'motif_refus',
        'date_soumission', 'date_traitement_rh', 'date_affectation', 'traite_par'
    ];

    protected $casts = [
        'date_debut_prevue' => 'date',
        'date_soumission' => 'datetime',
        'date_traitement_rh' => 'datetime',
        'date_affectation' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(ServiceUCA::class, 'service_uca_id');
    }

    public function traitePar()
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    public function entretien()
    {
        return $this->hasOne(Entretien::class, 'demande_stage_id');
    }

    public function cahierCharge()
    {
        return $this->hasOne(CahierCharge::class, 'demande_stage_id');
    }

    public function peutEtreModifiee()
    {
        return in_array($this->statut, ['brouillon', 'soumise', 'refusee_rh']);
    }
}
