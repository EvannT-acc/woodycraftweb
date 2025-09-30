<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔹 Utilisateur admin par défaut
        DB::table('users')->insert([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'role' => 'admin',
            'telephone' => '0102030405',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 🔹 Quelques utilisateurs de test via UserFactory
        User::factory(5)->create([
            'role' => 'client',
        ]);

        // 🔹 Catégories
        DB::table('categories')->insert([
            [
                'nom' => 'Fantastique',
                'description' => 'Puzzles où l’on retrouve des éléments surnaturels ou magiques.',
                'image' => 'Fantastique.jpg',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nom' => 'Science Fiction',
                'description' => 'Puzzles futuristes avec technologies avancées et voyages spatiaux.',
                'image' => 'ScienceFiction.jpg',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nom' => 'Horreur',
                'description' => 'Puzzles effrayants avec fantômes, zombies et créatures nocturnes.',
                'image' => 'Horreur.jpg',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nom' => 'Aventure',
                'description' => 'Puzzles qui plongent dans des aventures palpitantes.',
                'image' => 'Aventure.jpg',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // 🔹 Récupération des IDs
        $fantastiqueId = DB::table('categories')->where('nom', 'Fantastique')->value('id');
        $scifiId       = DB::table('categories')->where('nom', 'Science Fiction')->value('id');
        $horreurId     = DB::table('categories')->where('nom', 'Horreur')->value('id');
        $aventureId    = DB::table('categories')->where('nom', 'Aventure')->value('id');

        // 🔹 Puzzles
        DB::table('puzzles')->insert([
            [
                'nom' => 'Harry Potter: Le Puzzle de Poudlard',
                'description' => 'Reconstituez le château de Poudlard pièce par pièce.',
                'image' => null,
                'prix' => 24.99,
                'stock' => 50,
                'categorie_id' => $fantastiqueId,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nom' => 'Le Seigneur des Anneaux: Le Puzzle de l’Anneau Unique',
                'description' => 'Un puzzle mystique avec l’Anneau de Sauron.',
                'image' => null,
                'prix' => 29.99,
                'stock' => 30,
                'categorie_id' => $fantastiqueId,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nom' => 'Matrix: Code et Puzzles',
                'description' => 'Un puzzle qui déforme la réalité.',
                'image' => null,
                'prix' => 24.99,
                'stock' => 40,
                'categorie_id' => $scifiId,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nom' => 'Star Wars: Le Puzzle de l’Empire Caché',
                'description' => 'Le puzzle ultime de l’Empire Galactique.',
                'image' => null,
                'prix' => 29.99,
                'stock' => 35,
                'categorie_id' => $scifiId,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nom' => 'Shining: Le Puzzle de l’Hôtel Hanté',
                'description' => 'Chaque pièce libère un spectre.',
                'image' => null,
                'prix' => 29.99,
                'stock' => 20,
                'categorie_id' => $horreurId,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nom' => 'The Walking Dead: Puzzle Zombi',
                'description' => 'Chaque pièce attire un zombie.',
                'image' => null,
                'prix' => 25.99,
                'stock' => 20,
                'categorie_id' => $horreurId,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nom' => 'Indiana Jones et le Puzzle du Crâne de Cristal',
                'description' => 'Indy affronte des pièges absurdes.',
                'image' => null,
                'prix' => 29.99,
                'stock' => 15,
                'categorie_id' => $aventureId,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nom' => 'Pirates des Caraïbes: La Carte Puzzle Maudite',
                'description' => 'Jack Sparrow part à la recherche du trésor.',
                'image' => null,
                'prix' => 19.99,
                'stock' => 25,
                'categorie_id' => $aventureId,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
