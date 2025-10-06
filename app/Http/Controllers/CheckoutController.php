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
    /**
     * Page de finalisation de commande (adresse + mode de paiement)
     */
    public function index()
{
    $user = Auth::user();

    // On récupère le panier avec ses lignes actualisées
    $panier = Panier::where('user_id', $user->id)->first();

    //  Recalcul du total pour être sûr qu'il soit à jour
    $lignes = LignePanier::with('puzzle')->where('panier_id', $panier->id)->get();
    $total = $lignes->sum(fn($l) => $l->puzzle->prix * $l->quantite);
    $panier->update(['total' => $total]);

    // Récupère la dernière adresse utilisée ou vide si aucune
    $adresse = DB::table('adresses')->where('user_id', $user->id)->first();

    return view('checkout.index', compact('panier', 'lignes', 'adresse'));
}

    /**
     * Validation de la commande : choix du paiement
     */
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

        // Sauvegarde ou mise à jour de l'adresse de livraison
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

        // Calcul du total du panier
        $total = $lignes->sum(fn($l) => $l->puzzle->prix * $l->quantite);

        // Marquer la commande comme validée
        $panier->update([
            'status' => 1,
            'mode_paiement' => $request->mode_paiement,
            'total' => $total,
        ]);

        // Rafraîchir le modèle pour que les valeurs à jour soient prises en compte
        $panier->refresh();

        // Paiement par chèque → PDF + redirection vers panier
        if ($request->mode_paiement === 'cheque') {
            return $this->facturePDF($user, $panier, $lignes);
        }

        // Paiement PayPal → redirection vers PayPal Sandbox
        return $this->redirigerVersPaypal($panier, $total);
    }

    /**
     * Génère la facture PDF pour le paiement par chèque
     */
    private function facturePDF($user, $panier, $lignes)
    {
     $adresse = DB::table('adresses')->where('user_id', $user->id)->first();

        $pdf = Pdf::loadView('pdf.facture', [
            'user' => $user,
            'panier' => $panier,
           'lignes' => $lignes,
           'adresse' => $adresse
    ]);

    // Téléchargement direct du fichier
    return $pdf->download('facture_panier_' . $panier->id . '.pdf');
    }

    /**
     * Redirection vers PayPal Sandbox avec les paramètres nécessaires
     */
    private function redirigerVersPaypal($panier, $total)
    {
        // Adresse du compte business sandbox PayPal (à modifier par la tienne)
        $paypalBusiness = 'sb-xxxxxxxxxxxx@business.example.com';

        // Construction de l’URL de paiement PayPal
        $paypalUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr?' . http_build_query([
            'cmd'           => '_xclick',
            'business'      => $paypalBusiness,
            'item_name'     => 'Commande WoodyCraft #' . $panier->id,
            'amount'        => number_format($total, 2, '.', ''), // format correct
            'currency_code' => 'EUR',
            'return'        => route('paypal.success'),
            'cancel_return' => route('paypal.cancel'),
            'notify_url'    => route('paypal.ipn'),
        ]);

        // Redirection vers la page de paiement PayPal
        return redirect()->away($paypalUrl);
    }
}
