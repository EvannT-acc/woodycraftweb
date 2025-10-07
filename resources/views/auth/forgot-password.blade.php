<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-900">
        <div class="w-full max-w-md bg-gray-800 border border-gray-700 rounded-2xl shadow-soft p-8">

            <h1 class="text-3xl font-bold text-center text-accent mb-6">Mot de passe oublié ?</h1>
            <p class="text-center text-gray-400 mb-6">
                Pas de panique 😌. Indique ton adresse e-mail et nous t’enverrons un lien pour réinitialiser ton mot de passe.
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-green-400" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Adresse e-mail')" class="text-gray-300" />
                    <x-text-input id="email" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:ring-accent focus:border-accent"
                                  type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-accent hover:text-blue-400 transition">
                        Retour à la connexion
                    </a>

                    <button type="submit" 
                            class="bg-accent text-gray-900 font-semibold px-5 py-2 rounded-lg hover:bg-blue-400 transition shadow-soft">
                        {{ __('Envoyer le lien de réinitialisation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
