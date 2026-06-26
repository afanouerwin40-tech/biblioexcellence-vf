<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BiblioExcellence')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: true }">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Contenu principal --}}
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300"
             :class="sidebarOpen ? 'ml-64' : 'ml-16'">

            {{-- Navbar --}}
            @include('components.navbar')

            {{-- Page --}}
            <main class="flex-1 p-6">

                {{-- Alertes --}}
                @if(session('success'))
                    <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200
                                text-green-700 rounded-xl px-4 py-3 text-sm">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200
                                text-red-700 rounded-xl px-4 py-3 text-sm">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </main>

            {{-- Footer --}}
            <footer class="px-6 py-4 border-t border-gray-100 bg-white">
                <p class="text-xs text-gray-400 text-center">
                    © {{ date('Y') }} BiblioExcellence — Plateforme de gestion de bibliothèque universitaire
                </p>
            </footer>

        </div>
    </div>
@stack('scripts')
</body>
</html>