<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Message de succès -->
            @if (session()->has('message'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg shadow-md">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Liste des catégories -->
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 border-b-4 border-indigo-500 inline-block pb-2">
                    Liste des Catégories
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($categories as $categorie)
                        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1">
                            <div class="p-6 flex flex-col h-full">
                                
                                <!-- Image de la catégorie -->
                                @if($categorie->image)
                                    <div class="w-full h-10 mx-auto overflow-hidden rounded-md border border-gray-200 mb-4">
                                        <img src="{{ asset('images/' . $categorie->image) }}" 
                                             alt="{{ $categorie->nom }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-full h-40 bg-gray-200 mb-4 rounded-md flex items-center justify-center">
                                        <span class="text-gray-600 font-medium">Aucune image</span>
                                    </div>
                                @endif

                                <!-- Nom de la catégorie -->
                                <h4 class="text-lg font-bold text-gray-800 mb-2 truncate" title="{{ $categorie->nom }}">
                                    {{ $categorie->nom }}
                                </h4>

                                <!-- Description -->
                                @if($categorie->description)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                        {{ $categorie->description }}
                                    </p>
                                @endif

                                <!-- Nombre de puzzles -->
                                <div class="flex justify-between items-center mb-6 text-gray-500 text-sm">
                                    <span>{{ $categorie->puzzles->count() }} puzzle(s)</span>
                                </div>

                                <!-- Bouton voir -->
                                <a href="{{ route('categories.show', $categorie->id) }}" 
                                   class="mt-auto inline-block w-full text-center px-4 py-3 bg-black text-white rounded-lg font-semibold text-base transition">
                                    Voir les puzzles
                                </a>
                            </div>
                        </div>
                    @endforeach

                    <!-- Message si aucune catégorie -->
                    @if($categories->isEmpty())
                        <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
                            <p class="text-gray-600 mb-4">Aucune catégorie disponible pour le moment.</p>
                            <a href="{{ route('puzzles.create') }}" 
                               class="inline-block px-6 py-3 bg-black text-white rounded-lg text-base font-semibold hover:bg-gray-900 shadow-md">
                                Créer le premier puzzle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Améliorations visuelles globales */
        h2, h3, h4 {
            letter-spacing: 0.5px;
        }

        .rounded-xl {
            border-radius: 1rem !important;
        }

        /* Boutons noirs visibles */
        a.bg-black {
            background-color: #000000 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.25);
        }
        a.bg-black:hover {
            background-color: #1a1a1a !important;
            transform: scale(1.02);
        }
    </style>
</x-app-layout>
