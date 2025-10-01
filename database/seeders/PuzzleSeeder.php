<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Puzzle;
use App\Models\Categorie;

class PuzzleSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Categorie::all();

        foreach ($categories as $categorie) {
            for ($i = 1; $i <= 6; $i++) {
                Puzzle::firstOrCreate(
                    ['nom' => $categorie->nom . ' Puzzle #' . $i],
                    [
                        'description'  => 'Un puzzle de la catégorie ' . $categorie->nom,
                        'prix'         => rand(9, 39),
                        'stock'        => rand(5, 50),
                        'image'        => 'placeholder_puzzle.jpg',
                        'categorie_id' => $categorie->id,
                    ]
                );
            }
        }
    }
}
