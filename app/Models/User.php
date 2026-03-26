<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomVerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
     use HasFactory, Notifiable;

    protected $fillable = [
        'nom', 'prenom', 'email', 'password', 'telephone', 'role', 'service_uca_id', 'actif'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function demandesStage()
    {
        return $this->hasMany(DemandeStage::class, 'user_id');
    }

    public function demandesTraitees()
    {
        return $this->hasMany(DemandeStage::class, 'traite_par');
    }

    public function service()
    {
        return $this->belongsTo(ServiceUCA::class, 'service_uca_id');
    }

    public function entretiensPlanifies()
    {
        return $this->hasMany(Entretien::class, 'users_id');
    }


    public function isRh()
    {
        return $this->role === 'rh' || $this->isAdmin();
    }

    public function isService()
    {
        return $this->role === 'service' || $this->isAdmin();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDemandeur()
    {
        return $this->role === 'demandeur';
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmailNotification());
    }
}
