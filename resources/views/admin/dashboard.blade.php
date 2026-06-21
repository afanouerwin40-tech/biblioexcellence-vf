<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — BiblioExcellence</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                             C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                             C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                             C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="font-semibold text-gray-800">BiblioExcellence</span>
            <span class="text-gray-300">|</span>
            <span class="text-sm text-gray-500">Administration</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm text-red-500 hover:text-red-700">Déconnexion</button>
            </form>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-8">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
            <p class="text-gray-500 text-sm mt-1">Bienvenue, {{ auth()->user()->name }}</p>
        </div>

        {{-- Raccourcis --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <a href="{{ route('admin.validations.index') }}"
               class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6
                      hover:shadow-md transition group">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-4
                            group-hover:bg-amber-100 transition">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                                 M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857
                                 m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800">Validation des comptes</h3>
                <p class="text-sm text-gray-500 mt-1">Approuver ou rejeter les inscriptions</p>
            </a>

        </div>
    </div>

</body>
</html>