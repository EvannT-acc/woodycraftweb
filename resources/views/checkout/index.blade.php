<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('paniers.index') }}" 
           class="inline-block mb-6 text-accent hover:text-blue-400 text-sm transition">
            ← Retour au panier
        </a>

        <h2 class="font-bold text-3xl text-gray-100 leading-tight">Finaliser ma commande</h2>
    </x-slot>

    <div class="py-10 bg-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 grid md:grid-cols-2 gap-10">

            <!-- Adresse + Paiement -->
            <div class="bg-gray-800 p-6 rounded-2xl shadow-soft border border-gray-700">
                <h3 class="font-semibold text-xl text-accent mb-4">Adresse de livraison</h3>

                <form action="{{ route('checkout.valider') }}" method="POST" id="checkout-form">
                    @csrf
                    <div class="space-y-4" id="address-fields">
                        <input type="text" name="numero" value="{{ old('numero', $adresse->numero ?? '') }}" placeholder="Numéro" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded p-3 focus:ring-accent focus:border-accent">
                        <input type="text" name="rue" value="{{ old('rue', $adresse->rue ?? '') }}" placeholder="Rue" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded p-3 focus:ring-accent focus:border-accent">
                        <input type="text" name="ville" value="{{ old('ville', $adresse->ville ?? '') }}" placeholder="Ville" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded p-3 focus:ring-accent focus:border-accent">
                        <input type="text" name="code_postal" value="{{ old('code_postal', $adresse->code_postal ?? '') }}" placeholder="Code postal" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded p-3 focus:ring-accent focus:border-accent">
                        <input type="text" name="pays" value="{{ old('pays', $adresse->pays ?? '') }}" placeholder="Pays" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded p-3 focus:ring-accent focus:border-accent">
                    </div>

                    <h3 class="font-semibold text-xl text-accent mt-6 mb-3">Mode de paiement</h3>
                    <div class="space-y-2 text-gray-300">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="mode_paiement" value="cheque" class="accent-accent" onclick="togglePayment('cheque')" checked>
                            <span>Par chèque</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="mode_paiement" value="paypal" class="accent-accent" onclick="togglePayment('paypal')">
                            <span>PayPal</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="mode_paiement" value="carte" class="accent-accent" onclick="togglePayment('carte')">
                            <span>Carte bancaire</span>
                        </label>
                    </div>

                    <!-- Champs carte bancaire -->
                    <div id="card-fields" class="mt-6 hidden animate-fadeIn">
                        <h3 class="font-semibold text-lg text-accent mb-4">Informations de la carte</h3>
                        <div class="space-y-4">
                            <input type="text" name="card_name" placeholder="Nom sur la carte" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded p-3 focus:ring-accent focus:border-accent">
                            <input type="text" name="card_number" placeholder="Numéro de carte" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded p-3 focus:ring-accent focus:border-accent">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="expiry" placeholder="MM/AA" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded p-3 focus:ring-accent focus:border-accent">
                                <input type="text" name="cvc" placeholder="CVC" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded p-3 focus:ring-accent focus:border-accent">
                            </div>
                        </div>
                    </div>

                    <button class="mt-8 bg-accent text-gray-900 font-semibold px-6 py-3 rounded-lg shadow hover:bg-blue-400 transition w-full">
                        Valider ma commande
                    </button>
                </form>
            </div>

            <!-- Récapitulatif -->
            <div class="bg-gray-800 p-6 rounded-2xl shadow-soft border border-gray-700">
                <h3 class="font-semibold text-xl text-accent mb-4">Récapitulatif</h3>
                <div class="divide-y divide-gray-700">
                    @foreach($lignes as $ligne)
                        <div class="flex justify-between py-3">
                            <span class="text-gray-300">{{ $ligne->puzzle->nom }} x {{ $ligne->quantite }}</span>
                            <span class="text-gray-100">{{ number_format($ligne->puzzle->prix * $ligne->quantite, 2, ',', ' ') }} €</span>
                        </div>
                    @endforeach
                </div>

                <div class="text-right mt-6">
                    <h3 class="text-2xl font-bold text-accent">
                        Total : {{ number_format($panier->total, 2, ',', ' ') }} €
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePayment(type) {
            const cardFields = document.getElementById('card-fields');

            cardFields.classList.add('hidden');

            if (type === 'carte') {
                cardFields.classList.remove('hidden');
            }
        }
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }
    </style>
</x-app-layout>
