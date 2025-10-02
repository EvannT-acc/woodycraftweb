<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('Afficher un puzzle')
        </h2>
    </x-slot>

    <x-puzzles-card>
        <h3 class="font-semibold text-xl text-gray-800"> @lang('Nom') </h3>
        <p>{{ $puzzle->nom }}</p>

        <h3 class="font-semibold text-xl text-gray-800 pt-2"> @lang('Categorie') </h3>
        <p>{{ $puzzle->categorie->nom }}</p>

        <h3 class="font-semibold text-xl text-gray-800 pt-2"> @lang('Description') </h3>
        <p>{{ $puzzle->description }}</p>

        <h3 class="font-semibold text-xl text-gray-800 pt-2"> @lang('Prix') </h3>
        <p>{{ number_format($puzzle->prix, 2, ',', ' ') }} €</p>

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
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                     Ajouter au panier
                </button>
            </form>
        </div>
    </x-puzzles-card>
</x-app-layout>
