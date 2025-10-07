<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-100 leading-tight">
            {{ __('Profil utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="p-6 sm:p-8 bg-gray-800 border border-gray-700 shadow-soft rounded-2xl">
                <div class="max-w-xl text-gray-200">
                    <h3 class="text-xl font-semibold text-accent mb-4">Informations du profil</h3>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-gray-800 border border-gray-700 shadow-soft rounded-2xl">
                <div class="max-w-xl text-gray-200">
                    <h3 class="text-xl font-semibold text-accent mb-4">Modifier le mot de passe</h3>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-gray-800 border border-gray-700 shadow-soft rounded-2xl">
                <div class="max-w-xl text-gray-200">
                    <h3 class="text-xl font-semibold text-red-400 mb-4">Supprimer le compte</h3>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
