<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-700 text-gray-200 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-100" />
                    <span class="font-bold text-xl text-gray-100 tracking-wide">WoodyCraft</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-6 ml-10">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="text-blue-400 hover:text-accent transition font-medium">
                        {{ __('Tableau de bord') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Droite : panier + profil / connexion -->
            <div class="hidden sm:flex items-center space-x-6 ml-auto">

                <!-- Icône du panier (toujours visible, à gauche des autres éléments) -->
                <a href="{{ route('paniers.index') }}" 
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:text-accent transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.2 6.6A1 1 0 007 21h10a1 1 0 001-.8L20 13H7z" />
                    </svg>
                    Mon Panier
                </a>

                @auth
                    <!-- Menu profil -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-800 hover:text-accent focus:outline-none transition">
                                <div>{{ Auth::user()->prenom ?? Auth::user()->name }}</div>
                                <svg class="ml-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="hover:text-accent">
                                {{ __('Profil') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" 
                                    onclick="event.preventDefault(); this.closest('form').submit();" 
                                    class="hover:text-accent">
                                    {{ __('Déconnexion') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Connexion / Inscription -->
                    <a href="{{ route('login') }}" class="text-accent font-medium hover:text-blue-400 transition">
                        Connexion
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-2 text-accent font-medium hover:text-blue-400 transition">
                            Inscription
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Menu mobile -->
            <div class="sm:hidden">
                <button @click="open = ! open" 
                        class="p-2 rounded-md text-gray-400 hover:text-accent hover:bg-gray-800 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" 
                              class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" 
                              class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-800 border-t border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-accent">
                {{ __('Tableau de bord') }}
            </x-responsive-nav-link>

            <!-- Panier toujours visible sur mobile aussi -->
            <x-responsive-nav-link :href="route('paniers.index')" :active="request()->routeIs('paniers.index')" class="text-gray-300 hover:text-accent">
                Mon Panier
            </x-responsive-nav-link>
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-700">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-100">{{ Auth::user()->prenom ?? Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-300 hover:text-accent">
                        {{ __('Profil') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" 
                            onclick="event.preventDefault(); this.closest('form').submit();" 
                            class="text-gray-300 hover:text-accent">
                            {{ __('Déconnexion') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-4 border-t border-gray-700 text-center space-y-2">
                <a href="{{ route('login') }}" class="block text-accent font-medium hover:text-blue-400 transition">
                    Connexion
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block text-accent font-medium hover:text-blue-400 transition">
                        Inscription
                    </a>
                @endif
            </div>
        @endauth
    </div>
</nav>
