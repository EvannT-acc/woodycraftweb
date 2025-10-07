<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-900 px-4">
        <div class="w-full max-w-2xl bg-gray-800 border border-gray-700 rounded-2xl shadow-xl p-10">
            
            <h1 class="text-4xl font-bold text-center text-accent mb-4">Connexion</h1>
            <p class="text-center text-gray-400 mb-10 text-lg">
                Heureux de te revoir 👋 <br class="hidden md:block">Connecte-toi pour continuer.
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-green-400" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Adresse e-mail')" class="text-gray-300 text-lg" />
                    <x-text-input id="email" 
                                  class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 text-base focus:ring-accent focus:border-accent rounded-lg px-4 py-3" 
                                  type="email" 
                                  name="email" 
                                  :value="old('email')" 
                                  required 
                                  autofocus 
                                  autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-300 text-lg" />
                    <x-text-input id="password" 
                                  class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 text-base focus:ring-accent focus:border-accent rounded-lg px-4 py-3"
                                  type="password" 
                                  name="password" 
                                  required 
                                  autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center text-gray-400">
                        <input id="remember_me" 
                               type="checkbox" 
                               class="rounded border-gray-600 bg-gray-900 text-accent shadow-sm focus:ring-accent" 
                               name="remember">
                        <span class="ml-2 text-sm">{{ __('Se souvenir de moi') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-accent hover:text-blue-400 transition" href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié ?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full bg-accent text-gray-900 font-semibold px-6 py-3 rounded-lg hover:bg-blue-400 transition-all shadow-lg text-lg">
                        {{ __('Connexion') }}
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-400 text-sm">
                        Pas encore de compte ?
                        <a href="{{ route('register') }}" class="text-accent hover:text-blue-400 transition">
                            Créez-en un ici
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
