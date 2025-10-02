<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            Puzzles - {{ $categorie->nom }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg shadow-md">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Bouton retour -->
            <a href="{{ route('dashboard') }}" 
               class="inline-block mb-6 text-blue-600 hover:text-blue-800 text-sm">
                ← Retour aux catégories
            </a>

            <!-- Info catégorie -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-8 p-6">
                <h1 class="text-3xl font-extrabold text-indigo-700 mb-3">{{ $categorie->nom }}</h1>
                @if($categorie->description)
                    <p class="text-gray-600 text-lg">{{ $categorie->description }}</p>
                @endif
            </div>

            <!-- Liste des puzzles -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categorie->puzzles as $puzzle)
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-xl transition p-5 flex flex-col justify-between">
                        <div>
                            <!-- Image puzzle depuis public/images/puzzles -->
                            <img src="{{ asset('images/puzzles/' . $puzzle->image) }}" 
                                 alt="{{ $puzzle->nom }}" 
                                 class="w-full h-44 object-cover rounded-md mb-4">
                            
                            <h3 class="text-xl font-semibold text-gray-900">{{ $puzzle->nom }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($puzzle->description, 90) }}</p>
                            
                            <!-- Badge stock -->
                            @if($puzzle->stock > 0)
                                <span class="inline-block mt-2 px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded">
                                    En stock ({{ $puzzle->stock }})
                                </span>
                            @else
                                <span class="inline-block mt-2 px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded">
                                    Rupture
                                </span>
                            @endif

                            <!-- Prix en bleu -->
                            <p class="mt-3 text-lg font-bold text-indigo-600">
                                {{ number_format($puzzle->prix, 2, ',', ' ') }} €
                            </p>
                        </div>

                        <!-- Boutons -->
                        <div class="mt-5 flex justify-between">
                            <!-- Lien détail -->
                            <a href="{{ route('puzzles.show', $puzzle->id) }}" 
                               class="px-3 py-2 bg-gray-200 text-gray-800 text-sm rounded-lg hover:bg-gray-300 transition">
                                Voir le détail
                            </a>

                            <!-- Bouton ajouter au panier -->
                            <form action="{{ route('paniers.add', $puzzle->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 text-sm rounded-lg shadow transition
                                               @if($puzzle->stock > 0) bg-indigo-600 text-white hover:bg-indigo-700 
                                               @else bg-gray-300 text-gray-500 cursor-not-allowed @endif"
                                        @if($puzzle->stock == 0) disabled @endif>
                                     Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
