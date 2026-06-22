<header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-20">

    {{-- Toggle sidebar + Titre page --}}
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <div>
            <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Tableau de bord')</h1>
            @hasSection('page-subtitle')
                <p class="text-xs text-gray-400">@yield('page-subtitle')</p>
            @endif
        </div>
    </div>

    {{-- Actions droite --}}
    <div class="flex items-center gap-3">

        {{-- Notification badge --}}
        <button class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </button>

        {{-- Avatar avec lien profil --}}
<div class="flex items-center gap-2 pl-3 border-l border-gray-200">
    <a href="{{ route('profile.edit') }}"
       class="flex items-center gap-2 hover:opacity-80 transition">
        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
            <span class="text-xs font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
        </div>
        <div class="hidden md:block">
            <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role_type) }}</p>
        </div>
    </a>
</div>

    </div>

</header>