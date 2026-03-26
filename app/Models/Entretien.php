<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entretien extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_stage_id', 'date_heure', 'lieu', 'lien_reunion', 
        'notes', 'documents_demande', 'realise', 'users_id'
    ];

    protected $casts = [
        'date_heure' => 'datetime',
        'realise' => 'boolean',
    ];

    public function demandeStage()
    {
        return $this->belongsTo(DemandeStage::class);
    }

    public function planifiePar()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

}
