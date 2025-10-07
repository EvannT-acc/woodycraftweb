<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-900 px-4">
        <div class="w-full max-w-2xl bg-gray-800 border border-gray-700 rounded-2xl shadow-xl p-10">
            
            <h1 class="text-4xl font-bold text-center text-accent mb-4">Créer un compte</h1>
            <p class="text-center text-gray-400 mb-10 text-lg">
                Rejoins-nous et découvre les puzzles les plus stylés 🔥
            </p>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Prénom -->
                <div>
                    <x-input-label for="prenom" :value="__('Prénom')" class="text-gray-300 text-lg" />
                    <x-text-input id="prenom"
                                  class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 text-base focus:ring-accent focus:border-accent rounded-lg px-4 py-3"
                                  type="text" name="prenom" :value="old('prenom')" required autofocus />
                    <x-input-error :messages="$errors->get('prenom')" class="mt-2 text-red-400" />
                </div>

                <!-- Nom -->
                <div>
                    <x-input-label for="nom" :value="__('Nom')" class="text-gray-300 text-lg" />
                    <x-text-input id="nom"
                                  class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 text-base focus:ring-accent focus:border-accent rounded-lg px-4 py-3"
                                  type="text" name="nom" :value="old('nom')" required />
                    <x-input-error :messages="$errors->get('nom')" class="mt-2 text-red-400" />
                </div>

                <!-- Téléphone -->
                <div>
                    <x-input-label for="telephone" :value="__('Téléphone')" class="text-gray-300 text-lg" />
                    <x-text-input id="telephone"
                                  class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 text-base focus:ring-accent focus:border-accent rounded-lg px-4 py-3"
                                  type="text" name="telephone" :value="old('telephone')" />
                    <x-input-error :messages="$errors->get('telephone')" class="mt-2 text-red-400" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Adresse e-mail')" class="text-gray-300 text-lg" />
                    <x-text-input id="email"
                                  class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 text-base focus:ring-accent focus:border-accent rounded-lg px-4 py-3"
                                  type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                <!-- Mot de passe -->
                <div>
                    <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-300 text-lg" />
                    <x-text-input id="password"
                                  class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 text-base focus:ring-accent focus:border-accent rounded-lg px-4 py-3"
                                  type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                <!-- Confirmation du mot de passe -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-gray-300 text-lg" />
                    <x-text-input id="password_confirmation"
                                  class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 text-base focus:ring-accent focus:border-accent rounded-lg px-4 py-3"
                                  type="password" name="password_confirmation" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
                </div>

                <!-- Bouton -->
                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('login') }}" class="text-sm text-accent hover:text-blue-400 transition">
                        Déjà un compte ?
                    </a>

                    <button type="submit" 
                            class="bg-accent text-gray-900 font-semibold px-6 py-3 rounded-lg hover:bg-blue-400 transition-all shadow-lg text-lg">
                        {{ __('Créer mon compte') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
