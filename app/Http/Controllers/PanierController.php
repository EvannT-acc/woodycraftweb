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
            // 🌐 Invité : panier stocké dans la session
            $panier = (object)[
                'id' => null,
                'total' => 0,
            ];
            $sessionPanier = session('panier', []);
            $lignes = collect($sessionPanier)->map(function ($item) {
                $puzzle = Puzzle::find($item['puzzle_id']);
                return (object)[
                    'puzzle' => $puzzle,
                    'quantite' => $item['quantite'],
                ];
            });
            $panier->total = $lignes->sum(fn($l) => $l->puzzle->prix * $l->quantite);
        }

        return view('paniers.index', compact('panier', 'lignes'));
    }

    /**
     * Ajouter un puzzle au panier
     */
    public function add(Request $request, $puzzle_id)
    {
        $puzzle = Puzzle::findOrFail($puzzle_id);

        if ($puzzle->stock < 1) {
            return back()->with('error', 'Ce produit est en rupture de stock.');
        }

        if (Auth::check()) {
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

            $panier->total = $panier->lignes()->with('puzzle')->get()->sum(fn($l) => $l->puzzle->prix * $l->quantite);
            $panier->save();
        } else {
            // 🌐 Invité : panier en session
            $panier = session()->get('panier', []);
            if (isset($panier[$puzzle_id])) {
                $panier[$puzzle_id]['quantite']++;
            } else {
                $panier[$puzzle_id] = [
                    'puzzle_id' => $puzzle_id,
                    'quantite' => 1,
                ];
            }
            session()->put('panier', $panier);
        }

        return back()->with('success', 'Produit ajouté au panier !');
    }

    /**
     * Mettre à jour la quantité d’un produit du panier
     */
    public function update(Request $request, $ligne_id)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1'
        ]);

        if (Auth::check()) {
            $ligne = LignePanier::findOrFail($ligne_id);
            $panier = $ligne->panier;

            if ($request->quantite > $ligne->puzzle->stock) {
                return back()->with('error', 'Stock insuffisant.');
            }

            $ligne->update(['quantite' => $request->quantite]);
            $panier->update([
                'total' => $panier->lignes()->with('puzzle')->get()->sum(fn($l) => $l->puzzle->prix * $l->quantite),
            ]);
        } else {
            $panier = session()->get('panier', []);
            if (isset($panier[$ligne_id])) {
                $panier[$ligne_id]['quantite'] = $request->quantite;
                session()->put('panier', $panier);
            }
        }

        return back()->with('success', 'Quantité mise à jour.');
    }

    /**
     * Supprimer un produit du panier
     */
    public function remove($ligne_id)
    {
        if (Auth::check()) {
            $ligne = LignePanier::findOrFail($ligne_id);
            $panier = $ligne->panier;
            $ligne->delete();

            $panier->update([
                'total' => $panier->lignes()->with('puzzle')->get()->sum(fn($l) => $l->puzzle->prix * $l->quantite),
            ]);
        } else {
            $panier = session()->get('panier', []);
            unset($panier[$ligne_id]);
            session()->put('panier', $panier);
        }

        return back()->with('success', 'Produit retiré du panier.');
    }
}
