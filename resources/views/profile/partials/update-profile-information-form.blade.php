<section>
    <header>
        <h2 class="text-2xl font-semibold text-accent">
            {{ __('Informations du profil') }}
        </h2>

        <p class="mt-2 text-sm text-gray-400">
            {{ __("Modifiez vos informations personnelles et votre adresse e-mail.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nom complet')" class="text-gray-300" />
            <x-text-input id="name" name="name" type="text" 
                class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100 focus:ring-accent focus:border-accent" 
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-red-400" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Adresse e-mail')" class="text-gray-300" />
            <x-text-input id="email" name="email" type="email" 
                class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100 focus:ring-accent focus:border-accent" 
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 text-red-400" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-400">
                        {{ __('Votre adresse e-mail n\'est pas vérifiée.') }}
                        <button form="send-verification" class="underline text-sm text-accent hover:text-blue-400">
                            {{ __('Cliquez ici pour renvoyer l’e-mail de vérification.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-400">
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" 
                    class="bg-accent text-gray-900 font-semibold px-5 py-2 rounded-lg hover:bg-blue-400 transition shadow-soft">
                {{ __('Enregistrer') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-400">
                    {{ __('Modifications enregistrées.') }}
                </p>
            @endif
        </div>
    </form>
</section>
