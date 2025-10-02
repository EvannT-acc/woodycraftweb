<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Panier;
use App\Models\LignePanier;
use App\Models\Puzzle;

class PanierController extends Controller
{
    /**
     * Afficher le panier de l'utilisateur connecté
     */
    public function index()
    {
        $user = Auth::user();

        // Récupérer ou créer le panier de l'utilisateur
        $panier = Panier::firstOrCreate(
            ['user_id' => $user->id, 'status' => 0], // status 0 = panier en cours
            ['total' => 0]
        );

        $lignes = $panier->lignes()->with('puzzle')->get();

        return view('paniers.index', compact('panier', 'lignes'));
    }

    /**
     * Ajouter un puzzle au panier
     */
    public function add(Request $request, $puzzle_id)
    {
        $user = Auth::user();
        $panier = Panier::firstOrCreate(
            ['user_id' => $user->id, 'status' => 0],
            ['total' => 0]
        );

        $puzzle = Puzzle::findOrFail($puzzle_id);

        // Vérifier si le puzzle est déjà dans le panier
        $ligne = LignePanier::where('panier_id', $panier->id)
            ->where('puzzle_id', $puzzle->id)
            ->first();

        if ($ligne) {
            // Incrémente la quantité
            $ligne->quantite += 1;
            $ligne->save();
        } else {
            // Crée une nouvelle ligne
            LignePanier::create([
                'panier_id' => $panier->id,
                'puzzle_id' => $puzzle->id,
                'quantite' => 1,
            ]);
        }

        // Recalculer le total global du panier
        $panier->total = $panier->lignes()->with('puzzle')->get()->sum(function ($l) {
            return $l->puzzle->prix * $l->quantite;
        });
        $panier->save();

        return redirect()->route('paniers.index')->with('success', 'Produit ajouté au panier !');
    }

    /**
     * Mettre à jour la quantité d’un produit du panier
     */
    public function update(Request $request, $ligne_id)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1'
        ]);

        $ligne = LignePanier::findOrFail($ligne_id);
        $panier = $ligne->panier;

        // Mettre à jour la quantité
        $ligne->quantite = $request->quantite;
        $ligne->save();

        // Recalculer le total global du panier
        $panier->total = $panier->lignes()->with('puzzle')->get()->sum(function ($l) {
            return $l->puzzle->prix * $l->quantite;
        });
        $panier->save();

        return redirect()->route('paniers.index')->with('success', 'Quantité mise à jour avec succès.');
    }

    /**
     * Supprimer un produit du panier
     */
    public function remove($ligne_id)
    {
        $ligne = LignePanier::findOrFail($ligne_id);
        $panier = $ligne->panier;

        // Supprimer la ligne
        $ligne->delete();

        // Recalculer le total global du panier
        $panier->total = $panier->lignes()->with('puzzle')->get()->sum(function ($l) {
            return $l->puzzle->prix * $l->quantite;
        });
        $panier->save();

        return redirect()->route('paniers.index')->with('success', 'Produit retiré du panier.');
    }
}
