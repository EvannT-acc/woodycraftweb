<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Puzzle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'difficulty',
        'price',
        'categorie_id',
        'image_path',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function imageUrl(): string
    {
        return asset('storage/' . ($this->image_path ?: 'placeholders/puzzle.png'));
    }
}
