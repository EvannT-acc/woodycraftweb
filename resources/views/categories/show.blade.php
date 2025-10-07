<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-100 leading-tight">
            Puzzles - {{ $categorie->nom }}
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

            <div class="bg-gray-800 rounded-2xl shadow-soft mb-8 p-6 border border-gray-700">
                <h1 class="text-3xl font-extrabold text-accent mb-3">{{ $categorie->nom }}</h1>
                @if($categorie->description)
                    <p class="text-gray-400 text-lg">{{ $categorie->description }}</p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categorie->puzzles as $puzzle)
                    <div class="bg-gray-800 rounded-2xl shadow-soft overflow-hidden hover:scale-[1.02] transition-all duration-300 group flex flex-col">
                        <div class="relative">
                            <img src="{{ asset('images/puzzles/' . $puzzle->image) }}" 
                                 alt="{{ $puzzle->nom }}" 
                                 class="w-full h-52 object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>

                        <div class="p-5 flex flex-col justify-between flex-grow">
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-1">{{ $puzzle->nom }}</h3>
                                <p class="text-gray-400 text-sm mb-3">{{ Str::limit($puzzle->description, 90) }}</p>

                                @if($puzzle->stock > 0)
                                    <span class="inline-block mt-1 px-2 py-1 text-xs font-bold text-green-400 bg-green-900/30 rounded">
                                        En stock ({{ $puzzle->stock }})
                                    </span>
                                @else
                                    <span class="inline-block mt-1 px-2 py-1 text-xs font-bold text-red-400 bg-red-900/30 rounded">
                                        Rupture
                                    </span>
                                @endif

                                <p class="mt-3 text-lg font-bold text-accent">
                                    {{ number_format($puzzle->prix, 2, ',', ' ') }} €
                                </p>
                            </div>

                            <div class="mt-5 flex justify-between">
                                <a href="{{ route('puzzles.show', $puzzle->id) }}" 
                                   class="px-4 py-2 bg-gray-700 text-gray-200 text-sm rounded-lg hover:bg-gray-600 transition">
                                    Voir le détail
                                </a>

                                <form action="{{ route('paniers.add', $puzzle->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 text-sm font-semibold rounded-lg shadow transition 
                                                   @if($puzzle->stock > 0) bg-accent text-gray-900 hover:bg-blue-400 
                                                   @else bg-gray-600 text-gray-400 cursor-not-allowed @endif"
                                            @if($puzzle->stock == 0) disabled @endif>
                                        Ajouter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($categorie->puzzles->isEmpty())
                    <div class="col-span-full text-center py-12 bg-gray-800 rounded-2xl shadow-soft">
                        <p class="text-gray-400 mb-4">Aucun puzzle disponible dans cette catégorie.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
