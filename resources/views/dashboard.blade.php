<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Message de succès -->
            @if (session()->has('message'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            <div class="mb-6">
                <p class="text-gray-600">Vous êtes connecté !</p>
            </div>

            <!-- Liste des catégories -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Liste des Catégories</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categories as $categorie)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-6">
                                <!-- Image de la catégorie -->
                                @if($categorie->image)
                                    <img src="{{ asset('storage/' . $categorie->image) }}" 
                                         alt="{{ $categorie->name }}" 
                                         class="w-full h-40 object-cover mb-4 rounded">
                                @else
                                    <div class="w-full h-40 bg-gray-100 mb-4 rounded flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">Aucune image</span>
                                    </div>
                                @endif
                                
                                <!-- Nom de la catégorie -->
                                <h4 class="text-md font-semibold text-gray-900 mb-2 truncate" title="{{ $categorie->name }}">
                                    {{ $categorie->name }}
                                </h4>
                                
                                <!-- Description -->
                                @if($categorie->description)
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                        {{ $categorie->description }}
                                    </p>
                                @endif
                                
                                <!-- Nombre de puzzles -->
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-sm text-gray-500">
                                        {{ $categorie->puzzles->count() }} puzzle(s)
                                    </span>
                                </div>
                                
                                <!-- Bouton pour voir les puzzles -->
                                <a href="{{ route('categories.show', $categorie->id) }}" 
                                   class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                                    Voir les puzzles
                                </a>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Message si aucune catégorie -->
                    @if($categories->isEmpty())
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500 mb-4">Aucune catégorie disponible pour le moment.</p>
                            <a href="{{ route('puzzles.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
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
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</x-app-layout>