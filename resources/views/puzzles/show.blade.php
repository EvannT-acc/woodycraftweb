<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('Afficher un puzzle')
        </h2>
    </x-slot>

    <!-- Bouton retour -->
    <a href="{{ route('categories.show', $puzzle->categorie->id) }}" 
       class="inline-block mb-6 text-blue-600 hover:text-blue-800 text-sm">
        ← Retour à la catégorie {{ $puzzle->categorie->nom }}
    </a>

    <x-puzzles-card>
        <!-- Image du puzzle -->
        <div class="w-full h-60 mb-4 rounded-md overflow-hidden">
            <img src="{{ $puzzle->image ? asset('images/puzzles/' . $puzzle->image) : asset('images/no_image.jpg') }}" 
                 alt="{{ $puzzle->nom }}" 
                 class="w-full h-full object-cover">
        </div>

        <h3 class="font-semibold text-xl text-gray-800"> @lang('Nom') </h3>
        <p>{{ $puzzle->nom }}</p>

        <h3 class="font-semibold text-xl text-gray-800 pt-2"> @lang('Categorie') </h3>
        <p>{{ $puzzle->categorie->nom }}</p>

        <h3 class="font-semibold text-xl text-gray-800 pt-2"> @lang('Description') </h3>
        <p>{{ $puzzle->description }}</p>

        <!-- Badge stock -->
        <h3 class="font-semibold text-xl text-gray-800 pt-2"> @lang('Disponibilité') </h3>
        @if($puzzle->stock > 0)
            <span class="inline-block mt-1 px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded">
                En stock ({{ $puzzle->stock }})
            </span>
        @else
            <span class="inline-block mt-1 px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded">
                Rupture
            </span>
        @endif

        <h3 class="font-semibold text-xl text-gray-800 pt-2"> @lang('Prix') </h3>
        <p class="text-indigo-600 font-bold text-lg">
            {{ number_format($puzzle->prix, 2, ',', ' ') }} €
        </p>

        <h3 class="font-semibold text-xl text-gray-800 pt-2"> @lang('Date création') </h3>
        <p>{{ $puzzle->created_at->format('d/m/Y') }}</p>

        @if($puzzle->created_at != $puzzle->updated_at)
            <h3 class="font-semibold text-xl text-gray-800 pt-2"> @lang('Dernière modification') </h3>
            <p>{{ $puzzle->updated_at->format('d/m/Y') }}</p>
        @endif

        <!-- Bouton Ajouter au panier -->
        <div class="mt-6">
            <form action="{{ route('paniers.add', $puzzle->id) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 text-white rounded-lg shadow transition
                               @if($puzzle->stock > 0) bg-indigo-600 hover:bg-indigo-700 
                               @else bg-gray-300 text-gray-500 cursor-not-allowed @endif"
                        @if($puzzle->stock == 0) disabled @endif>
                     Ajouter au panier
                </button>
            </form>
        </div>
    </x-puzzles-card>
</x-app-layout>
