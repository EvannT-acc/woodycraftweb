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
                <x-input-label for="name" :value="__('Nom')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text"
                    name="name" :value="old('name', $puzzle->name)" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Catégorie -->
            <div class="mt-4">
                <x-input-label for="category" :value="__('Categorie')" />
                <x-textarea id="category" class="block mt-1 w-full" name="category">{{ old('category', $puzzle->category) }}</x-textarea>
                <x-input-error :messages="$errors->get('category')" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="mt-4">
                <x-input-label for="description" :value="__('Description')" />
                <x-textarea id="description" class="block mt-1 w-full" name="description">{{ old('description', $puzzle->description) }}</x-textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Prix -->
            <div class="mt-4">
                <x-input-label for="price" :value="__('Prix')" />
                <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01"
                    name="price" :value="old('price', $puzzle->price)" required />
                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-3">
                    {{ __('Modifier') }}
                </x-primary-button>
            </div>
        </form>
    </x-puzzles-card>
</x-app-layout>
