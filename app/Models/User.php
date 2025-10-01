<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'role',
        'telephone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Récupérer le nom complet de l'utilisateur.
     */
    public function getFullNameAttribute(): string
    {
        return trim(collect([$this->prenom, $this->nom])->filter()->join(' '));
    }

    /**
     * Récupérer un nom d'affichage robuste pour l'utilisateur.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($fullName = $this->full_name) {
            return $fullName;
        }

        if (filled($this->attributes['name'] ?? null)) {
            return $this->attributes['name'];
        }

        return (string) ($this->email ?? '');
    }

    /**
     * Relations possibles (exemples à adapter plus tard).
     */
    public function adresses()
    {
        return $this->hasMany(Adresse::class);
    }

    public function paniers()
    {
        return $this->hasMany(Panier::class);
    }
}
