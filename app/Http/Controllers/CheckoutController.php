<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Panier;
use App\Models\LignePanier;
use App\Models\Puzzle;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $panier = Panier::where('user_id', $user->id)->first();
        $lignes = LignePanier::with('puzzle')->where('panier_id', $panier->id)->get();

        // Adresse : on prend celle de la dernière commande (panier status 1)
        $adresse = DB::table('adresses')->where('user_id', $user->id)->first();

        return view('checkout.index', compact('panier', 'lignes', 'adresse'));
    }

    public function valider(Request $request)
    {
        $request->validate([
            'numero' => 'required|string|max:50',
            'rue' => 'required|string|max:150',
            'ville' => 'required|string|max:100',
            'code_postal' => 'required|string|max:20',
            'pays' => 'required|string|max:100',
            'mode_paiement' => 'required|in:cheque,paypal',
        ]);

        $user = Auth::user();
        $panier = Panier::where('user_id', $user->id)->first();
        $lignes = LignePanier::with('puzzle')->where('panier_id', $panier->id)->get();

        if ($lignes->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        // Sauvegarde ou mise à jour adresse
        DB::table('adresses')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'numero' => $request->numero,
                'rue' => $request->rue,
                'ville' => $request->ville,
                'code_postal' => $request->code_postal,
                'pays' => $request->pays,
                'updated_at' => now(),
            ]
        );

        // Marquer la commande comme validée (status = 1)
        $total = $lignes->sum(fn($l) => $l->puzzle->prix * $l->quantite);
        $panier->update([
            'status' => 1,
            'mode_paiement' => $request->mode_paiement,
            'total' => $total,
        ]);

        if ($request->mode_paiement === 'cheque') {
            return $this->facturePDF($user, $panier, $lignes);
        } else {
            return back()->with('success', 'Redirection PayPal à venir.');
        }
    }

    private function facturePDF($user, $panier, $lignes)
    {
        $adresse = DB::table('adresses')->where('user_id', $user->id)->first();
        $pdf = Pdf::loadView('pdf.facture', [
            'user' => $user,
            'panier' => $panier,
            'lignes' => $lignes,
            'adresse' => $adresse
        ]);

        return $pdf->download('facture_panier_' . $panier->id . '.pdf');
    }
}
