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
     * Afficher le panier (session ou utilisateur)
     */
    public function index()
    {
        if (Auth::check()) {
            // 🔒 Utilisateur connecté : panier en BDD
            $panier = Panier::firstOrCreate(
                ['user_id' => Auth::id(), 'status' => 0],
                ['total' => 0]
            );
            $lignes = $panier->lignes()->with('puzzle')->get();
        } else {
            // 🌐 Invité : panier "lecture seule"
            $panier = (object)[
                'id' => null,
                'total' => 0,
            ];

            $sessionPanier = session('panier', []);
            $lignes = collect($sessionPanier)->map(function ($item) {
                $puzzle = Puzzle::find($item['puzzle_id']);
                return (object)[
                    'id' => null, // pas d’ID car pas en base
                    'puzzle' => $puzzle,
                    'quantite' => $item['quantite'],
                ];
            });

            $panier->total = $lignes->sum(fn($l) => $l->puzzle->prix * $l->quantite);
        }

        return view('paniers.index', compact('panier', 'lignes'));
    }

    /**
     * Ajouter un puzzle au panier (connecté uniquement)
     */
    public function add(Request $request, $puzzle_id)
    {
        // 🚫 Si pas connecté, rediriger
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour ajouter un produit au panier.');
        }

        $puzzle = Puzzle::findOrFail($puzzle_id);

        if ($puzzle->stock < 1) {
            return back()->with('error', 'Ce produit est en rupture de stock.');
        }

        // 🔒 Utilisateur connecté : panier BDD
        $panier = Panier::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 0],
            ['total' => 0]
        );

        $ligne = LignePanier::where('panier_id', $panier->id)
            ->where('puzzle_id', $puzzle->id)
            ->first();

        if ($ligne) {
            if ($ligne->quantite >= $puzzle->stock) {
                return back()->with('error', 'Stock insuffisant pour ajouter plus de ce produit.');
            }
            $ligne->increment('quantite');
        } else {
            LignePanier::create([
                'panier_id' => $panier->id,
                'puzzle_id' => $puzzle->id,
                'quantite' => 1,
            ]);
        }

        // 🔄 Mise à jour du total
        $panier->total = $panier->lignes()->with('puzzle')->get()
            ->sum(fn($l) => $l->puzzle->prix * $l->quantite);
        $panier->save();

        return back()->with('success', 'Produit ajouté au panier !');
    }

    /**
     * Mettre à jour la quantité d’un produit du panier (connecté uniquement)
     */
    public function update(Request $request, $ligne_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Connectez-vous pour modifier votre panier.');
        }

        $request->validate([
            'quantite' => 'required|integer|min:1'
        ]);

        $ligne = LignePanier::findOrFail($ligne_id);
        $panier = $ligne->panier;

        if ($request->quantite > $ligne->puzzle->stock) {
            return back()->with('error', 'Stock insuffisant.');
        }

        $ligne->update(['quantite' => $request->quantite]);
        $panier->update([
            'total' => $panier->lignes()->with('puzzle')->get()
                ->sum(fn($l) => $l->puzzle->prix * $l->quantite),
        ]);

        return back()->with('success', 'Quantité mise à jour.');
    }

    /**
     * Supprimer un produit du panier (connecté uniquement)
     */
    public function remove($ligne_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Connectez-vous pour modifier votre panier.');
        }

        $ligne = LignePanier::findOrFail($ligne_id);
        $panier = $ligne->panier;

        $ligne->delete();

        $panier->update([
            'total' => $panier->lignes()->with('puzzle')->get()
                ->sum(fn($l) => $l->puzzle->prix * $l->quantite),
        ]);

        return back()->with('success', 'Produit retiré du panier.');
    }
}
