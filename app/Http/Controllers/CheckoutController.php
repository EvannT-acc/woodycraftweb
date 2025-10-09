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
    public function index()
    {
        $user = Auth::user();

        $panier = Panier::firstOrCreate(
            ['user_id' => $user->id, 'status' => 0],
            ['total' => 0]
        );

        $lignes = LignePanier::with('puzzle')
            ->where('panier_id', $panier->id)
            ->get();

        $total = $lignes->sum(fn($l) => $l->puzzle->prix * $l->quantite);
        $panier->update(['total' => $total]);

        // Récupérer l'adresse de l'utilisateur (s'il y en a une)
        $adresse = DB::table('adresses')->where('user_id', $user->id)->first();

        return view('checkout.index', compact('panier', 'lignes', 'adresse'));
    }

    public function valider(Request $request)
    {
        $request->validate([
            'mode_paiement' => 'required|in:cheque,paypal,carte', // Ajout du mode carte
            'numero'         => 'required|string|max:50',
            'rue'            => 'required|string|max:150',
            'ville'          => 'required|string|max:100',
            'code_postal'    => 'required|string|max:20',
            'pays'           => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $panier = Panier::where('user_id', $user->id)->where('status', 0)->first();

        if (!$panier) {
            return back()->with('error', 'Aucun panier actif trouvé.');
        }

        $lignes = LignePanier::with('puzzle')->where('panier_id', $panier->id)->get();

        if ($lignes->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        // Mise à jour ou création de l'adresse de l'utilisateur
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

        $total = $lignes->sum(fn($l) => $l->puzzle->prix * $l->quantite);
        $panier->update([
            'status'        => 1, // Statut du panier payé
            'mode_paiement' => $request->mode_paiement, // Mode de paiement (PayPal, carte ou chèque)
            'total'         => $total,
        ]);

        // Nouveau panier vide pour la suite
        Panier::create([
            'user_id' => $user->id,
            'status'  => 0,
            'total'   => 0,
        ]);

        // Traitement selon le mode de paiement
        if ($request->mode_paiement === 'cheque') {
            return $this->factureCheque($user, $panier, $lignes);
        }

        if ($request->mode_paiement === 'carte') {
            return $this->paiementCarte($user, $panier, $lignes);
        }

        return $this->redirigerVersPaypal($panier, $total);
    }

    /**
     * 💳 Paiement par carte : suppression du panier + redirection
     */
    private function paiementCarte($user, $panier, $lignes)
    {
        // Supprimer panier et lignes
        DB::transaction(function () use ($panier) {
            LignePanier::where('panier_id', $panier->id)->delete();
            $panier->delete();
        });

        // Rediriger vers le tableau de bord avec un message de succès
        return redirect()
            ->route('dashboard')
            ->with('message', 'Paiement par carte effectué avec succès 💳');
    }

    /**
     * 🧾 Paiement par chèque : génération de la facture PDF et téléchargement
     */
    private function factureCheque($user, $panier, $lignes)
    {
        $adresse = DB::table('adresses')->where('user_id', $user->id)->first();

        // Générer la facture PDF
        $pdf = Pdf::loadView('pdf.facture', [
            'user'    => $user,
            'panier'  => $panier,
            'lignes'  => $lignes,
            'adresse' => $adresse,
        ]);

        $filename = 'facture_panier_' . $panier->id . '.pdf';
        $content  = $pdf->output();

        // Supprimer le panier et ses lignes après la génération du PDF
        DB::transaction(function () use ($panier) {
            LignePanier::where('panier_id', $panier->id)->delete();
            $panier->delete();
        });

        // Téléchargement immédiat + redirection après 2 secondes
        return response($content)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"')
            ->setContent(
                $content . '<script>
                    setTimeout(() => { window.location.href = "'.route('dashboard').'?success='.urlencode('Votre paiement par chèque a bien été enregistré 🎉').'"; }, 2000);
                </script>'
            );
    }

    private function redirigerVersPaypal($panier, $total)
    {
        $paypalUrl = 'https://www.paypal.com/fr/home'; // Redirection vers la page d'accueil de PayPal

        // Rediriger l'utilisateur vers PayPal
        return redirect()->away($paypalUrl);
    }
}
