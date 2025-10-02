<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des puzzles') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($puzzles as $puzzle)
                    <div class="bg-white shadow rounded-lg p-4 flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <img src="{{ asset('storage/'.$puzzle->image) }}" alt="{{ $puzzle->nom }}" class="w-full h-40 object-cover rounded mb-3">
                            
                            <h3 class="text-lg font-bold text-gray-900">{{ $puzzle->nom }}</h3>
                            <p class="text-sm text-gray-600">{{ Str::limit($puzzle->description, 80) }}</p>

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

                            <!-- Prix -->
                            <p class="mt-2 font-semibold text-indigo-600 text-lg">
                                {{ number_format($puzzle->prix, 2, ',', ' ') }} €
                            </p>
                        </div>

                        <div class="mt-4 flex justify-between">
                            <a href="{{ route('puzzles.show', $puzzle->id) }}" 
                               class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                                Voir le détail
                            </a>

                            <form action="{{ route('paniers.add', $puzzle->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-3 py-1 text-white rounded shadow 
                                               @if($puzzle->stock > 0) bg-indigo-600 hover:bg-indigo-700 
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
