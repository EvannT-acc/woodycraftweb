<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Puzzles - {{ $categorie->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Retour au dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center mb-6 text-blue-600 hover:text-blue-800 text-sm">
                ← Retour au tableau de bord
            </a>

            <!-- Info catégorie -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $categorie->name }}</h1>
                    @if($categorie->description)
                        <p class="text-gray-600 mb-2">{{ $categorie->description }}</p>
                    @endif
                    <p class="text-sm text-gray-500">{{ $categorie->puzzles->count() }} puzzle(s) dans cette catégorie</p>
                </div>
            </div>

            <!-- Liste des puzzles -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categorie->puzzles as $puzzle)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            @if($puzzle->image)
                                <img src="{{ asset('storage/' . $puzzle->image) }}" 
                                     alt="{{ $puzzle->name }}" 
                                     class="w-full h-48 object-cover mb-4 rounded">
                            @else
                                <div class="w-full h-48 bg-gray-100 mb-4 rounded flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">Aucune image</span>
                                </div>
                            @endif
                            
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 truncate" title="{{ $puzzle->name }}">
                                {{ $puzzle->name }}
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ $puzzle->description }}
                            </p>
                            
                            <p class="text-lg font-bold text-blue-600 mb-4">
                                {{ number_format($puzzle->price, 2) }} €
                            </p>
                            
                            <div class="mt-4">
                                <a href="{{ route('puzzles.show', $puzzle->id) }}" 
                                   class="block w-full text-center px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-medium hover:bg-gray-700 transition-colors">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($categorie->puzzles->isEmpty())
                    <div class="col-span-full text-center py-8">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                            <p class="text-gray-500 mb-4">Aucun puzzle dans cette catégorie pour le moment.</p>
                            <a href="{{ route('puzzles.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                                Ajouter un puzzle
                            </a>
                        </div>
                    </div>
                @endif
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