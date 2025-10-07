<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-100 leading-tight">
            {{ __('Mon Panier') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-700/30 text-green-300 rounded-lg shadow-soft">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-700/30 text-red-300 rounded-lg shadow-soft">
                    {{ session('error') }}
                </div>
            @endif

            <a href="{{ route('dashboard') }}" 
               class="inline-block mb-6 text-accent hover:text-blue-400 text-sm transition">
                ← Retour aux catégories
            </a>

            <div class="bg-gray-800 shadow-soft rounded-2xl overflow-hidden border border-gray-700">
                <div class="p-6">
                    @if($lignes->isEmpty())
                        <p class="text-gray-400 text-center py-10 text-lg">Votre panier est vide 🛒</p>
                    @else
                        <table class="w-full text-left border-collapse text-gray-300">
                            <thead>
                                <tr class="bg-gray-700 text-gray-100">
                                    <th class="p-3">Produit</th>
                                    <th class="p-3">Prix</th>
                                    <th class="p-3">Quantité</th>
                                    <th class="p-3">Sous-total</th>
                                    <th class="p-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lignes as $ligne)
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/40 transition">
                                        <td class="p-3 flex items-center space-x-3">
                                            <img src="{{ asset('images/puzzles/' . $ligne->puzzle->image) }}" 
                                                 alt="{{ $ligne->puzzle->nom }}" 
                                                 class="w-14 h-14 object-cover rounded-lg shadow-md">
                                            <span class="font-medium">{{ $ligne->puzzle->nom }}</span>
                                        </td>
                                        <td class="p-3">{{ number_format($ligne->puzzle->prix, 2, ',', ' ') }} €</td>

                                        <td class="p-3">
                                            <form action="{{ route('paniers.update', $ligne->id) }}" method="POST" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantite" value="{{ $ligne->quantite }}" 
                                                       min="1" max="{{ $ligne->puzzle->stock }}" 
                                                       class="w-16 bg-gray-900 border border-gray-600 rounded p-1 text-center text-gray-100 focus:ring-accent focus:border-accent">
                                                <button type="submit" 
                                                        class="px-3 py-1 bg-accent text-gray-900 text-xs font-semibold rounded hover:bg-blue-400 transition">
                                                    Mettre à jour
                                                </button>
                                            </form>
                                            <small class="text-gray-500">Stock dispo : {{ $ligne->puzzle->stock }}</small>
                                        </td>

                                        <td class="p-3 font-semibold text-gray-100">
                                            {{ number_format($ligne->puzzle->prix * $ligne->quantite, 2, ',', ' ') }} €
                                        </td>
                                        <td class="p-3 text-right">
                                            <form action="{{ route('paniers.remove', $ligne->id) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-600/80 text-white text-sm rounded hover:bg-red-600 transition">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-8 text-right">
                            <h3 class="text-xl font-bold text-gray-100">
                                Total : <span class="text-accent">{{ number_format($panier->total, 2, ',', ' ') }} €</span>
                            </h3>
                        </div>

                        <div class="mt-8 text-right">
                            @auth
                                <a href="{{ route('checkout.index') }}" 
                                   class="inline-block bg-accent text-gray-900 font-semibold px-6 py-3 rounded-lg shadow hover:bg-blue-400 transition">
                                    Passer commande
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="inline-block bg-yellow-500 text-gray-900 font-semibold px-6 py-3 rounded-lg shadow hover:bg-yellow-400 transition">
                                    Se connecter pour commander
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
