<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceUCA extends Model
{
     use HasFactory;

    protected $table = 'services_uca';
    
    protected $fillable = [
        'nom', 'description', 'responsable_nom', 'responsable_email', 'actif'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'service_uca_id');
    }

    public function demandes()
    {
        return $this->hasMany(DemandeStage::class, 'service_uca_id');
    }
}
