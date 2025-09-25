<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Puzzle;
use App\Models\Categorie;

return new class extends Migration
{
    public function up(): void
    {
        // Récupérer toutes les catégories uniques existantes
        $existingCategories = Puzzle::distinct()->pluck('category');
        
        foreach ($existingCategories as $categoryName) {
            if (!empty($categoryName)) {
                // Créer la catégorie
                $categorie = Categorie::create([
                    'name' => $categoryName,
                    'description' => 'Catégorie existante'
                ]);
                
                // Mettre à jour les puzzles avec la nouvelle categorie_id
                Puzzle::where('category', $categoryName)
                      ->update(['categorie_id' => $categorie->id]);
            }
        }
    }

    public function down(): void
    {
        // Optionnel : pour rollback
        Puzzle::whereNotNull('categorie_id')->update(['categorie_id' => null]);
    }
};