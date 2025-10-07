<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-900">
        <div class="w-full max-w-md bg-gray-800 border border-gray-700 rounded-2xl shadow-soft p-8">

            <h1 class="text-3xl font-bold text-center text-accent mb-6">Réinitialiser le mot de passe</h1>
            <p class="text-center text-gray-400 mb-8">
                Entre ton nouveau mot de passe pour retrouver l’accès à ton compte 🔐
            </p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Adresse e-mail')" class="text-gray-300" />
                    <x-text-input id="email" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:ring-accent focus:border-accent"
                                  type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                <!-- Nouveau mot de passe -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Nouveau mot de passe')" class="text-gray-300" />
                    <x-text-input id="password" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:ring-accent focus:border-accent"
                                  type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                <!-- Confirmation -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-gray-300" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:ring-accent focus:border-accent"
                                  type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-accent hover:text-blue-400 transition">
                        Retour à la connexion
                    </a>

                    <button type="submit" 
                            class="bg-accent text-gray-900 font-semibold px-5 py-2 rounded-lg hover:bg-blue-400 transition shadow-soft">
                        {{ __('Réinitialiser le mot de passe') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
