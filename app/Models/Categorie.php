<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'image_path',
    ];

    public function puzzles()
    {
        return $this->hasMany(Puzzle::class, 'categorie_id');
    }

    // Image URL helper
    public function imageUrl(): string
    {
        return asset('storage/' . ($this->image_path ?: 'placeholders/category.png'));
    }
}
