<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Panier;
use App\Models\LignePanier;

class CheckoutController extends Controller
{
    /**
     * Page de finalisation de commande (adresse + mode de paiement)
     */
    public function index()
    {
        $user = Auth::user();

        // Récupérer le panier courant
        $panier = Panier::where('user_id', $user->id)->first();

        // Recalcule/actualise le total pour que le récap soit tjrs juste
        $lignes = LignePanier::with('puzzle')->where('panier_id', $panier->id)->get();
        $total = $lignes->sum(fn($l) => $l->puzzle->prix * $l->quantite);
        $panier->update(['total' => $total]);

        // Adresse par défaut
        $adresse = DB::table('adresses')->where('user_id', $user->id)->first();

        return view('checkout.index', compact('panier', 'lignes', 'adresse'));
    }

    /**
     * Validation de la commande : choix du paiement
     */
    public function valider(Request $request)
    {
        $request->validate([
            'numero'        => 'required|string|max:50',
            'rue'           => 'required|string|max:150',
            'ville'         => 'required|string|max:100',
            'code_postal'   => 'required|string|max:20',
            'pays'          => 'required|string|max:100',
            'mode_paiement' => 'required|in:cheque,paypal',
        ]);

        $user   = Auth::user();
        $panier = Panier::where('user_id', $user->id)->first();
        $lignes = LignePanier::with('puzzle')->where('panier_id', $panier->id)->get();

        if ($lignes->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        // Sauvegarde / MAJ adresse
        DB::table('adresses')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'numero'      => $request->numero,
                'rue'         => $request->rue,
                'ville'       => $request->ville,
                'code_postal' => $request->code_postal,
                'pays'        => $request->pays,
                'updated_at'  => now(),
            ]
        );

        // Calcul du total au moment T (garanti à jour)
        $total = $lignes->sum(fn($l) => $l->puzzle->prix * $l->quantite);

        // Marquer la "commande" (panier actuel) comme validée
        $panier->update([
            'status'        => 1,
            'mode_paiement' => $request->mode_paiement,
            'total'         => $total,
        ]);

        $panier->refresh();

        if ($request->mode_paiement === 'cheque') {
            // Générer + envoyer le PDF ET supprimer le panier en BDD avant de renvoyer le flux
            return $this->facturePDFEtSupprimerPanier($user, $panier, $lignes);
        }

        // PayPal : on redirige vers PayPal, et on supprimera le panier au retour (/paypal/success)
        return $this->redirigerVersPaypal($panier, $total);
    }

    /**
     * Génère la facture PDF, supprime le panier (lignes + panier), puis renvoie le téléchargement
     */
    private function facturePDFEtSupprimerPanier($user, $panier, $lignes)
    {
        $adresse = DB::table('adresses')->where('user_id', $user->id)->first();

        $pdf = Pdf::loadView('pdf.facture', [
            'user'    => $user,
            'panier'  => $panier,
            'lignes'  => $lignes,
            'adresse' => $adresse,
        ]);

        $filename = 'facture_panier_' . $panier->id . '.pdf';
        $content  = $pdf->output();

        // 🔒 On supprime le panier et ses lignes AVANT de renvoyer le flux (pour éviter les paniers fantômes)
        DB::transaction(function () use ($panier) {
            LignePanier::where('panier_id', $panier->id)->delete();
            $panier->delete();
        });

        // 🔁 On renvoie le téléchargement direct (pas de redirect ici)
        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename);
    }

    /**
     * Redirection vers PayPal Sandbox avec les paramètres nécessaires
     */
    private function redirigerVersPaypal($panier, $total)
    {
        $paypalBusiness = 'sb-xxxxxxxxxxxx@business.example.com'; // ← mets ton email sandbox business
        $paypalUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr?' . http_build_query([
            'cmd'           => '_xclick',
            'business'      => $paypalBusiness,
            'item_name'     => 'Commande WoodyCraft #' . $panier->id,
            'amount'        => number_format($total, 2, '.', ''),
            'currency_code' => 'EUR',
            'return'        => route('paypal.success'), // on supprimera le panier ici
            'cancel_return' => route('paypal.cancel'),
            'notify_url'    => route('paypal.ipn'),
        ]);

        return redirect()->away($paypalUrl);
    }
}
