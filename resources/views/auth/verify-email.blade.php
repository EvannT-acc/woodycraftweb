<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-900">
        <div class="w-full max-w-md bg-gray-800 border border-gray-700 rounded-2xl shadow-soft p-8">

            <h1 class="text-3xl font-bold text-center text-accent mb-6">Vérifie ton e-mail 📬</h1>
            <p class="text-center text-gray-400 mb-8">
                Merci pour ton inscription !  
                Avant de commencer, confirme ton adresse e-mail en cliquant sur le lien que nous venons de t’envoyer.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-3 bg-green-700/30 border border-green-600 text-green-300 rounded-lg text-sm text-center">
                    {{ __('Un nouveau lien de vérification a été envoyé à ton adresse e-mail.') }}
                </div>
            @endif

            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full sm:w-auto bg-accent text-gray-900 font-semibold px-5 py-2 rounded-lg hover:bg-blue-400 transition shadow-soft">
                        {{ __('Renvoyer l’e-mail de vérification') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="text-sm text-gray-400 hover:text-accent underline transition">
                        {{ __('Se déconnecter') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>
