<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;
use App\Models\Puzzle;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Catégories
        $cats = [
            ['name' => 'Classiques', 'description' => 'Puzzles intemporels.', 'image_path' => 'placeholders/category.png'],
            ['name' => 'Nature', 'description' => 'Paysages et animaux.', 'image_path' => 'placeholders/category.png'],
            ['name' => 'Art', 'description' => 'Œuvres et abstrait.', 'image_path' => 'placeholders/category.png'],
            ['name' => 'Villes', 'description' => 'Skylines et rues.', 'image_path' => 'placeholders/category.png'],
        ];

        foreach ($cats as $c) {
            $categorie = Categorie::firstOrCreate(['name' => $c['name']], $c);

            // 6–7 puzzles par catégorie
            for ($i = 1; $i <= 7; $i++) {
                Puzzle::firstOrCreate(
                    ['title' => $c['name'].' #'.$i, 'categorie_id' => $categorie->id],
                    [
                        'description'  => 'Puzzle '.$c['name'].' numéro '.$i,
                        'difficulty'   => rand(1,5),
                        'price'        => rand(9, 39),
                        'image_path'   => 'placeholders/puzzle.png',
                    ]
                );
            }
        }
    }
}
