<section>
    <header>
        <h2 class="text-2xl font-semibold text-accent">
            {{ __('Modifier le mot de passe') }}
        </h2>

        <p class="mt-2 text-sm text-gray-400">
            {{ __('Assurez-vous d’utiliser un mot de passe long et unique pour sécuriser votre compte.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Mot de passe actuel')" class="text-gray-300" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100 focus:ring-accent focus:border-accent"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-400" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Nouveau mot de passe')" class="text-gray-300" />
            <x-text-input id="update_password_password" name="password" type="password"
                class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100 focus:ring-accent focus:border-accent"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-400" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le mot de passe')" class="text-gray-300" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100 focus:ring-accent focus:border-accent"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-400" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                    class="bg-accent text-gray-900 font-semibold px-5 py-2 rounded-lg hover:bg-blue-400 transition shadow-soft">
                {{ __('Enregistrer') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-400">
                    {{ __('Mot de passe mis à jour.') }}
                </p>
            @endif
        </div>
    </form>
</section>
