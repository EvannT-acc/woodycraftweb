<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-semibold text-red-400">
            {{ __('Supprimer le compte') }}
        </h2>

        <p class="mt-2 text-sm text-gray-400">
            {{ __('Une fois votre compte supprimé, toutes vos données seront définitivement effacées. Téléchargez ou sauvegardez vos informations avant de continuer.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600/80 hover:bg-red-600 text-white font-semibold px-5 py-2 rounded-lg shadow transition">
        {{ __('Supprimer le compte') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-gray-800 border border-gray-700 rounded-2xl">
            @csrf
            @method('delete')

            <h2 class="text-xl font-semibold text-gray-100">
                {{ __('Êtes-vous sûr de vouloir supprimer votre compte ?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-400">
                {{ __('Toutes vos ressources et données seront supprimées définitivement. Veuillez confirmer avec votre mot de passe pour continuer.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Mot de passe') }}" class="text-gray-300" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-2 block w-3/4 bg-gray-900 border-gray-700 text-gray-100 focus:ring-accent focus:border-accent"
                    placeholder="{{ __('Mot de passe') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-400" />
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" 
                        x-on:click="$dispatch('close')"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg transition">
                    {{ __('Annuler') }}
                </button>

                <button type="submit"
                        class="px-5 py-2 bg-red-600 hover:bg-red-500 text-white font-semibold rounded-lg shadow transition">
                    {{ __('Supprimer le compte') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
