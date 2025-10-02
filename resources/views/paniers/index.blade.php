<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🛒 {{ __('Mon Panier') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Message de succès -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    @if($lignes->isEmpty())
                        <p class="text-gray-600">Votre panier est vide.</p>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="p-3">Produit</th>
                                    <th class="p-3">Prix</th>
                                    <th class="p-3">Quantité</th>
                                    <th class="p-3">Sous-total</th>
                                    <th class="p-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lignes as $ligne)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 flex items-center space-x-3">
                                            <img src="{{ asset('storage/'.$ligne->puzzle->image) }}" alt="{{ $ligne->puzzle->nom }}" class="w-12 h-12 object-cover rounded">
                                            <span>{{ $ligne->puzzle->nom }}</span>
                                        </td>
                                        <td class="p-3">{{ number_format($ligne->puzzle->prix, 2, ',', ' ') }} €</td>
                                        <td class="p-3">{{ $ligne->quantite }}</td>
                                        <td class="p-3">{{ number_format($ligne->puzzle->prix * $ligne->quantite, 2, ',', ' ') }} €</td>
                                        <td class="p-3 text-right">
                                            <form action="{{ route('paniers.remove', $ligne->id) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Total -->
                        <div class="mt-6 text-right">
                            <h3 class="text-lg font-bold">
                                Total : {{ number_format($panier->total, 2, ',', ' ') }} €
                            </h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
