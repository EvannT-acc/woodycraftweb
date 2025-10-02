<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LignePanier extends Model
{
    use HasFactory;

    protected $fillable = [
        'panier_id',
        'puzzle_id',
        'quantite',
    ];

    public function panier()
    {
        return $this->belongsTo(Panier::class);
    }

    public function puzzle()
    {
        return $this->belongsTo(Puzzle::class);
    }
}
