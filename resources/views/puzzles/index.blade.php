<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des puzzles') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($puzzles as $puzzle)
                <div class="bg-white shadow rounded-lg p-4 flex flex-col justify-between">
                    <div>
                        <img src="{{ asset('storage/'.$puzzle->image) }}" alt="{{ $puzzle->nom }}" class="w-full h-40 object-cover rounded mb-3">
                        <h3 class="text-lg font-bold">{{ $puzzle->nom }}</h3>
                        <p class="text-sm text-gray-600">{{ Str::limit($puzzle->description, 80) }}</p>
                        <p class="mt-2 font-semibold">{{ number_format($puzzle->prix, 2, ',', ' ') }} €</p>
                    </div>

                    <div class="mt-4 flex justify-between">
                        <a href="{{ route('puzzles.show', $puzzle->id) }}" 
                           class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                            Voir le détail
                        </a>

                        <form action="{{ route('paniers.add', $puzzle->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                🛒 Ajouter
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
