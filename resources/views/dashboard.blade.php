<x-app-layout>
    <x-slot name="header">
        <div class="text-gray-100">
            <h2 class="font-bold text-3xl leading-tight">{{ __('Tableau de bord') }}</h2>

            @auth
                <p class="text-gray-400 mt-1">
                    Bienvenue, 
                    <span class="font-semibold text-accent">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                    <span class="ml-2 px-2 py-1 text-xs rounded-full 
                        {{ Auth::user()->role === 'admin' ? 'bg-red-600/30 text-red-400' : 'bg-green-600/30 text-green-400' }}">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </p>
            @else
                <p class="text-blue-400 mt-1 italic">Bienvenue sur le tableau des puzzles</p>
            @endauth
        </div>
    </x-slot>

    <div class="py-10 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session()->has('message'))
                <div class="mb-6 p-4 bg-green-700/30 text-green-300 rounded-lg shadow-soft">
                    {{ session('message') }}
                </div>
            @endif

            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-100 mb-8 border-b-4 border-accent inline-block pb-2">
                    Liste des Catégories
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($categories as $categorie)
                        <div class="bg-gray-800 rounded-2xl shadow-soft overflow-hidden hover:shadow-xl transition-all duration-200 relative min-h-[380px] flex flex-col">
                            
                            <div class="relative w-full h-48 overflow-hidden">
                                @if($categorie->image)
                                    <img src="{{ asset('images/categories/' . $categorie->image) }}" 
                                         alt="{{ $categorie->nom }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-400 font-medium">Aucune image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-5 flex flex-col flex-1">
                                <h4 class="text-xl font-semibold text-white mb-2 truncate">{{ $categorie->nom }}</h4>

                                @if($categorie->description)
                                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $categorie->description }}</p>
                                @endif

                                <div class="flex justify-between items-center text-gray-500 text-sm mb-4">
                                    <span>{{ $categorie->puzzles->count() }} puzzle(s)</span>
                                </div>

                                <div class="mt-auto space-y-3">
                                    <!-- Voir les puzzles -->
                                    <a href="{{ route('categories.show', $categorie->id) }}" 
                                       class="block text-center px-4 py-3 rounded-lg font-semibold text-base transition duration-200
                                              bg-blue-500 text-white hover:bg-blue-600 shadow-md hover:shadow-lg">
                                        Voir les puzzles
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($categories->isEmpty())
                        <div class="col-span-full text-center py-12 bg-gray-800 rounded-2xl shadow-soft">
                            <p class="text-gray-400 mb-4">Aucune catégorie disponible pour le moment.</p>
                            @auth
                                <a href="{{ route('puzzles.create') }}" 
                                   class="inline-block px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition shadow-md">
                                    Créer le premier puzzle
                                </a>
                            @endauth
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
    </style>
</x-app-layout>
