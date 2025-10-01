<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Fantastique', 'description' => 'Puzzles avec des éléments magiques ou surnaturels.', 'image' => 'Fantastique.jpg'],
            ['nom' => 'Science Fiction', 'description' => 'Puzzles futuristes avec technologies et espace.', 'image' => 'ScienceFiction.jpg'],
            ['nom' => 'Horreur', 'description' => 'Puzzles effrayants avec zombies, fantômes, etc.', 'image' => 'Horreur.jpg'],
            ['nom' => 'Aventure', 'description' => 'Puzzles sur des aventures palpitantes.', 'image' => 'Aventure.jpg'],
        ];

        foreach ($categories as $cat) {
            Categorie::firstOrCreate(['nom' => $cat['nom']], $cat);
        }
    }
}
