<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-100 leading-tight">
            {{ __('Détail du puzzle') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-10 px-4">
        <a href="{{ route('categories.show', $puzzle->categorie->id) }}" 
           class="inline-block mb-6 text-accent hover:text-blue-400 text-sm transition">
            ← Retour à la catégorie {{ $puzzle->categorie->nom }}
        </a>

        <div class="flex flex-col lg:flex-row gap-10 bg-gray-800 rounded-2xl p-8 shadow-soft border border-gray-700">
            <div class="flex-1">
                <div class="rounded-xl overflow-hidden shadow-lg relative group">
                    <img src="{{ $puzzle->image ? asset('images/puzzles/' . $puzzle->image) : asset('images/no_image.jpg') }}" 
                         alt="{{ $puzzle->nom }}" 
                         class="w-full h-96 object-cover transform group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition duration-500"></div>
                </div>
            </div>

            <div class="flex-1 flex flex-col justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-3">{{ $puzzle->nom }}</h1>
                    <p class="text-gray-400 mb-6">{{ $puzzle->description }}</p>

                    <p class="text-sm text-gray-500 mb-1">
                        Catégorie : <span class="text-accent">{{ $puzzle->categorie->nom }}</span>
                    </p>

                    <p class="text-sm text-gray-500 mb-1">
                        Créé le : <span class="text-gray-300">{{ $puzzle->created_at->format('d/m/Y') }}</span>
                    </p>

                    @if($puzzle->created_at != $puzzle->updated_at)
                        <p class="text-sm text-gray-500 mb-3">
                            Dernière modification : <span class="text-gray-300">{{ $puzzle->updated_at->format('d/m/Y') }}</span>
                        </p>
                    @endif

                    <div class="mt-3">
                        @if($puzzle->stock > 0)
                            <span class="inline-block px-3 py-1 text-xs font-bold text-green-400 bg-green-900/30 rounded">
                                En stock ({{ $puzzle->stock }})
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 text-xs font-bold text-red-400 bg-red-900/30 rounded">
                                Rupture
                            </span>
                        @endif
                    </div>

                    <p class="mt-5 text-3xl font-bold text-accent">
                        {{ number_format($puzzle->prix, 2, ',', ' ') }} €
                    </p>

                    <form action="{{ route('paniers.add', $puzzle->id) }}" method="POST" class="mt-6">
                        @csrf
                        <button type="submit" 
                                class="px-6 py-3 text-gray-900 font-semibold rounded-xl shadow transition 
                                       @if($puzzle->stock > 0) bg-accent hover:bg-blue-400 
                                       @else bg-gray-600 text-gray-400 cursor-not-allowed @endif"
                                @if($puzzle->stock == 0) disabled @endif>
                            Ajouter au panier
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
