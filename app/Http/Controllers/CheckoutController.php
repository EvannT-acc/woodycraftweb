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

        // Récupérer le panier courant (status 0 = en cours)
        $panier = Panier::where('user_id', $user->id)
                        ->where('status', 0)
                        ->first();

        if (!$panier) {
            // ✅ Crée un nouveau panier vide si aucun n'existe
            $panier = Panier::create([
                'user_id' => $user->id,
                'status'  => 0,
                'total'   => 0,
            ]);
        }

        // Récupère les lignes et calcule le total
        $lignes = LignePanier::with('puzzle')
                    ->where('panier_id', $panier->id)
                    ->get();

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

        $user = Auth::user();
        $panier = Panier::where('user_id', $user->id)
                        ->where('status', 0)
                        ->first();

        if (!$panier) {
            return back()->with('error', 'Aucun panier actif trouvé.');
        }

        $lignes = LignePanier::with('puzzle')->where('panier_id', $panier->id)->get();

        if ($lignes->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        // Sauvegarde ou mise à jour de l'adresse
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

        // Calcul du total
        $total = $lignes->sum(fn($l) => $l->puzzle->prix * $l->quantite);

        // ✅ Marquer le panier comme validé
        $panier->update([
            'status'        => 1,
            'mode_paiement' => $request->mode_paiement,
            'total'         => $total,
        ]);

        // ✅ Créer un nouveau panier vide pour la prochaine commande
        Panier::create([
            'user_id' => $user->id,
            'status'  => 0,
            'total'   => 0,
        ]);

        if ($request->mode_paiement === 'cheque') {
            // Paiement par chèque → PDF + suppression panier
            return $this->facturePDFEtSupprimerPanier($user, $panier, $lignes);
        }

        // Paiement PayPal → redirection
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

        // ✅ Supprimer le panier et ses lignes
        DB::transaction(function () use ($panier) {
            LignePanier::where('panier_id', $panier->id)->delete();
            $panier->delete();
        });

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename);
    }

    /**
     * Redirection vers PayPal Sandbox
     */
    private function redirigerVersPaypal($panier, $total)
    {
        $paypalBusiness = 'sb-xxxxxxxxxxxx@business.example.com'; // ton email sandbox
        $paypalUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr?' . http_build_query([
            'cmd'           => '_xclick',
            'business'      => $paypalBusiness,
            'item_name'     => 'Commande WoodyCraft #' . $panier->id,
            'amount'        => number_format($total, 2, '.', ''),
            'currency_code' => 'EUR',
            'return'        => route('paypal.success', ['id' => $panier->id]),
            'cancel_return' => route('paypal.cancel'),
            'notify_url'    => route('paypal.ipn'),
        ]);

        return redirect()->away($paypalUrl);
    }

    /**
     * ✅ Callback PayPal : paiement réussi
     */
    public function paypalSuccess(Request $request)
    {
        $user = Auth::user();
        $panierId = $request->query('id');

        if (!$panierId) {
            return redirect()->route('dashboard')->with('error', 'Identifiant de commande manquant.');
        }

        $panier = Panier::find($panierId);

        if (!$panier || $panier->status != 1) {
            return redirect()->route('dashboard')->with('error', 'Commande introuvable ou déjà traitée.');
        }

        // ✅ Supprimer le panier et ses lignes
        DB::transaction(function () use ($panier) {
            LignePanier::where('panier_id', $panier->id)->delete();
            $panier->delete();
        });

        return redirect()->route('dashboard')->with('message', 'Paiement PayPal confirmé 🎉');
    }

    /**
     * ✅ Callback PayPal : annulation du paiement
     */
    public function paypalCancel()
    {
        return redirect()->route('dashboard')->with('error', 'Paiement annulé par l’utilisateur.');
    }

    /**
     * (Optionnel) Gestion des notifications IPN PayPal (non obligatoire ici)
     */
    public function paypalIpn(Request $request)
    {
        // Ici, tu peux traiter les notifications automatiques de PayPal si besoin
        return response('OK', 200);
    }
}
