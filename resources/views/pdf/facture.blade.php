<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Facture commande #{{ $panier->id }}</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 5px; }
        h3 { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f3f3; }
        .right { text-align: right; }
        .info { margin-top: 5px; font-size: 12px; }
    </style>
</head>
<body>
    <h1>Facture - Commande #{{ $panier->id }}</h1>

    <p class="info">
        <strong>Date :</strong> {{ $panier->updated_at->format('d/m/Y H:i') }}<br>
        <strong>Client :</strong> {{ $user->prenom }} {{ $user->nom }} ({{ $user->email }})<br>
        <strong>Mode de paiement :</strong> 
        @if($panier->mode_paiement === 'cheque')
            Chèque
        @elseif($panier->mode_paiement === 'paypal')
            PayPal
        @else
            Non spécifié
        @endif
    </p>

    <h3>Adresse de livraison</h3>
    <p>
        {{ $adresse->numero }} {{ $adresse->rue }}<br>
        {{ $adresse->code_postal }} {{ $adresse->ville }}<br>
        {{ $adresse->pays }}
    </p>

    <h3>Détails de la commande</h3>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th class="right">Qté</th>
                <th class="right">PU (€)</th>
                <th class="right">Total (€)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lignes as $ligne)
                <tr>
                    <td>{{ $ligne->puzzle->nom }}</td>
                    <td class="right">{{ $ligne->quantite }}</td>
                    <td class="right">{{ number_format($ligne->puzzle->prix, 2, ',', ' ') }}</td>
                    <td class="right">{{ number_format($ligne->puzzle->prix * $ligne->quantite, 2, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="right">Total</th>
                <th class="right">{{ number_format($panier->total, 2, ',', ' ') }} €</th>
            </tr>
        </tfoot>
    </table>

    <h3>Instructions de paiement</h3>
    @if($panier->mode_paiement === 'cheque')
        <p>Merci d’envoyer votre chèque à l’ordre de <strong>WoodyCraft</strong> à l’adresse suivante :</p>
        <p><strong>WoodyCraft - Service Comptabilité<br>
        12 rue des Artisans<br>
        75001 Paris, France</strong></p>
        <p>Votre commande sera traitée dès réception du chèque.</p>
    @elseif($panier->mode_paiement === 'paypal')
        <p>Vous avez choisi un paiement par <strong>PayPal</strong>.  
        Un e-mail de confirmation vous sera envoyé après validation du paiement.</p>
    @endif
</body>
</html>
