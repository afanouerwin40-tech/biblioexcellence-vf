<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>@yield('title', 'BiblioExcellence')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen overflow-x-hidden" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024 }" 
      x-init="window.addEventListener('resize', () => { sidebarOpen = window.innerWidth >= 1024; })">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <div class="fixed inset-y-0 left-0 z-30 w-64 bg-blue-900 text-white transition-transform duration-300 ease-in-out"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            x-show="sidebarOpen"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full">
            @include('components.sidebar')
        </div>

        {{-- Overlay (visible uniquement sur mobile) --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-20 transition-opacity duration-300 lg:hidden"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;">
        </div>

        {{-- Contenu principal --}}
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 ml-0 lg:ml-64">

            {{-- Navbar --}}
            @include('components.navbar')

            {{-- Page --}}
            <main class="flex-1 p-4 lg:p-6 overflow-x-hidden">
                @if(session('success'))
                <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
                @endif
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="px-4 lg:px-6 py-4 border-t border-gray-100 bg-white">
                <p class="text-xs text-gray-400 text-center">
                    © {{ date('Y') }} BiblioExcellence — Plateforme de gestion de bibliothèque universitaire
                </p>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>