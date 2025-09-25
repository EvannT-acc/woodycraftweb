<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editer un puzzle') }}
        </h2>
    </x-slot>

    <x-puzzles-card>
        @if(session()->has('message'))
            <div class="mt-3 mb-4 list-disc list-inside text-sm text-green-600">
                {{ session('message') }}
            </div>
        @endif

        <form action="{{ route('puzzles.update', $puzzle->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nom -->
            <div>
                <x-input-label for="Nom" :value="__('Nom')" />
                <x-text-input id="Nom" class="block mt-1 w-full" type="text"
                    name="Nom" :value="old('Nom', $puzzle->Nom)" required autofocus />
                <x-input-error :messages="$errors->get('Nom')" class="mt-2" />
            </div>

            <!-- Catégorie -->
            <div class="mt-4">
                <x-input-label for="categories" :value="__('categories')" />
                <x-textarea id="categories" class="block mt-1 w-full" name="categories">{{ old('category', $puzzle->category) }}</x-textarea>
                <x-input-error :messages="$errors->get('categories')" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="mt-4">
                <x-input-label for="description" :value="__('Description')" />
                <x-textarea id="description" class="block mt-1 w-full" name="description">{{ old('description', $puzzle->description) }}</x-textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Prix -->
            <div class="mt-4">
                <x-input-label for="prix" :value="__('Prix')" />
                <x-text-input id="prix" class="block mt-1 w-full" type="number" step="0.01"
                    name="prix" :value="old('prix', $puzzle->prix)" required />
                <x-input-error :messages="$errors->get('prix')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-3">
                    {{ __('Modifier') }}
                </x-primary-button>
            </div>
        </form>
    </x-puzzles-card>
</x-app-layout>
