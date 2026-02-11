<nav x-data="{ open: false }" class="bg-[#131316] border-b border-[#27272a]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16 items-center">

            <!-- LEFT -->
            <div class="flex items-center gap-8">
                <!-- Logo -->
               <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
    <!-- LOGO SVG -->
    <svg width="36" height="36" viewBox="0 0 48 48" fill="none"
         xmlns="http://www.w3.org/2000/svg">
        <!-- Fond -->
        <rect x="2" y="2" width="44" height="44" rx="10" fill="#0a0a0c"/>

        <!-- Barres analyse -->
        <rect x="12" y="26" width="4" height="10" rx="2" fill="#3b82f6"/>
        <rect x="20" y="20" width="4" height="16" rx="2" fill="#3b82f6"/>
        <rect x="28" y="14" width="4" height="22" rx="2" fill="#1e40af"/>

        <!-- Point focus -->
        <circle cx="30" cy="12" r="3" fill="#3b82f6"/>
    </svg>

    <!-- TEXTE -->
    <span class="text-white font-extrabold text-lg tracking-tight">
        Analyse<span class="text-blue-500">Projets</span>
    </span>
</a>


        
            </div>

            <!-- RIGHT -->
            <div class="hidden sm:flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center gap-3 px-3 py-2 rounded-lg
                                   text-sm font-medium text-gray-300
                                   hover:bg-blue-500/10 hover:text-white
                                   transition"
                        >
                            

                            <span>{{ Auth::user()->name }}</span>

                            <svg class="fill-current h-4 w-4 text-gray-400"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link
                            :href="route('profile.edit')"
                            class="text-gray-300 hover:bg-blue-500/10 hover:text-white"
                        >
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                class="text-red-400 hover:bg-red-500/10"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                            >
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- MOBILE BUTTON -->
            <div class="sm:hidden">
                <button @click="open = !open"
                        class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open}" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open}" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive Menu -->
    <div x-show="open" class="sm:hidden border-t border-[#27272a]">
        <div class="px-4 py-3 space-y-2">
            <x-responsive-nav-link
                :href="route('dashboard')"
                class="text-gray-300"
            >
                Dashboard
            </x-responsive-nav-link>

            <div class="pt-4 border-t border-[#27272a]">
                <div class="text-white font-medium">
                    {{ Auth::user()->name }}
                </div>
                <div class="text-sm text-gray-400">
                    {{ Auth::user()->email }}
                </div>

                <x-responsive-nav-link
                    :href="route('profile.edit')"
                    class="text-gray-300 mt-2"
                >
                    Profile
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link
                        :href="route('logout')"
                        class="text-red-400"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>