<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    // Tableau des champs assignables
    protected $fillable = [
        'user_id',
        'puzzle_id',
        'commentaire',
        'note',
    ];

    /**
     * Définir la relation avec l'utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Définir la relation avec le puzzle.
     */
    public function puzzle()
    {
        return $this->belongsTo(Puzzle::class);
    }
}
