<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-900">
        <div class="w-full max-w-md bg-gray-800 border border-gray-700 rounded-2xl shadow-soft p-8">

            <h1 class="text-3xl font-bold text-center text-accent mb-6">Confirme ton mot de passe 🔒</h1>
            <p class="text-center text-gray-400 mb-8">
                Par mesure de sécurité, nous devons vérifier ton identité avant de continuer.
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Mot de passe -->
                <div>
                    <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-300" />
                    <x-text-input id="password" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:ring-accent focus:border-accent"
                                  type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                <div class="flex justify-between items-center mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-accent hover:text-blue-400 transition">
                        Retour
                    </a>

                    <button type="submit" 
                            class="bg-accent text-gray-900 font-semibold px-5 py-2 rounded-lg hover:bg-blue-400 transition shadow-soft">
                        {{ __('Confirmer') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
