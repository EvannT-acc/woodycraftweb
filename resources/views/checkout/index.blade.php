<x-app-layout>
    <x-slot name="header">
        <!-- Bouton retour -->
        <a href="{{ route('paniers.index') }}" class="inline-block mb-6 text-blue-600 hover:text-blue-800 text-sm">
            ← Retour au panier
<       </a>

        <h2 class="font-bold text-xl text-gray-800 leading-tight">Finaliser ma commande</h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 grid md:grid-cols-2 gap-8">

            <!-- Adresse -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold mb-4">Adresse de livraison</h3>
                <form action="{{ route('checkout.valider') }}" method="POST">
                    @csrf
                    <div class="space-y-3">
                        <input type="text" name="numero" value="{{ old('numero', $adresse->numero ?? '') }}" placeholder="Numéro" class="w-full border p-2 rounded">
                        <input type="text" name="rue" value="{{ old('rue', $adresse->rue ?? '') }}" placeholder="Rue" class="w-full border p-2 rounded">
                        <input type="text" name="ville" value="{{ old('ville', $adresse->ville ?? '') }}" placeholder="Ville" class="w-full border p-2 rounded">
                        <input type="text" name="code_postal" value="{{ old('code_postal', $adresse->code_postal ?? '') }}" placeholder="Code postal" class="w-full border p-2 rounded">
                        <input type="text" name="pays" value="{{ old('pays', $adresse->pays ?? '') }}" placeholder="Pays" class="w-full border p-2 rounded">
                    </div>

                    <h3 class="font-semibold mt-6 mb-2">Mode de paiement</h3>
                    <label class="block"><input type="radio" name="mode_paiement" value="cheque" checked> Par chèque</label>
                    <label class="block"><input type="radio" name="mode_paiement" value="paypal"> PayPal</label>

                    <button class="mt-6 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Valider ma commande</button>
                </form>
            </div>

            <!-- Récap -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold mb-4">Récapitulatif</h3>
                @foreach($lignes as $ligne)
                    <div class="flex justify-between border-b py-2">
                        <span>{{ $ligne->puzzle->nom }} x {{ $ligne->quantite }}</span>
                        <span>{{ number_format($ligne->puzzle->prix * $ligne->quantite, 2, ',', ' ') }} €</span>
                    </div>
                @endforeach
                <div class="text-right mt-4 font-bold">
                    Total : {{ number_format($panier->total, 2, ',', ' ') }} €
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
