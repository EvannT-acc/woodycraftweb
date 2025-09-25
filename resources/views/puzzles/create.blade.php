<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a puzzle') }}
        </h2>
    </x-slot>

    <x-puzzles-card>
        <!-- Message de succès -->
        @if(session()->has('message'))
            <div class="mt-3 mb-4 list-disc list-inside text-sm text-green-600">
                {{ session('message') }}
            </div>
        @endif

        <form action="{{ route('puzzles.store') }}" method="POST">
            @csrf

            <!-- Nom -->
            <div>
                <x-input-label for="nom" :value="__('nom')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" 
                    name="nom" :value="old('nom')" required autofocus />
                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
            </div>

            <!-- Catégorie -->
            <div class="mt-4">
                <x-input-label for="categories" :value="__('categories')" />
                <x-text-input id="categories" class="block mt-1 w-full" type="text" 
                    name="categories" :value="old('categories')" required />
                <x-input-error :messages="$errors->get('categories')" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="mt-4">
                <x-input-label for="description" :value="__('Description')" />
                <x-textarea id="description" class="block mt-1 w-full" name="description">{{ old('description') }}</x-textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Prix -->
            <div class="mt-4">
                <x-input-label for="prix" :value="__('prix')" />
                <x-text-input id="prix" class="block mt-1 w-full" type="number" step="0.01" 
                    name="prix" :value="old('prix')" required />
                <x-input-error :messages="$errors->get('prix')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-3">
                    {{ __('Send') }}
                </x-primary-button>
            </div>
        </form>
    </x-puzzles-card>
</x-app-layout>

