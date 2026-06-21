<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation des comptes — BiblioExcellence</title>
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

        {{-- En-tête --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Validation des comptes</h1>
                <p class="text-gray-500 text-sm mt-1">Gérez les demandes d'inscription en attente</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                ← Tableau de bord
            </a>
        </div>

        {{-- Messages --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Statistiques --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <p class="text-3xl font-bold text-amber-500">{{ $stats['pending'] }}</p>
                <p class="text-sm text-gray-500 mt-1">En attente</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <p class="text-3xl font-bold text-green-500">{{ $stats['approved'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Approuvés</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <p class="text-3xl font-bold text-red-500">{{ $stats['rejected'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Rejetés</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <p class="text-3xl font-bold text-gray-500">{{ $stats['suspended'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Suspendus</p>
            </div>
        </div>

        {{-- Tableau --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Comptes en attente</h2>
            </div>

            @if($pending->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm">Aucun compte en attente de validation</p>
                </div>
            @else
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Nom</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Rôle</th>
                            <th class="px-6 py-3 text-left">Matricule</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pending as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800 text-sm">{{ $user->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->role_type === 'student')
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium">
                                            Étudiant
                                        </span>
                                    @elseif($user->role_type === 'teacher')
                                        <span class="px-2.5 py-1 bg-purple-50 text-purple-600 rounded-lg text-xs font-medium">
                                            Enseignant
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-mono">
                                    {{ $user->identifier }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">

                                        {{-- Voir détails --}}
                                        <a href="{{ route('admin.validations.show', $user) }}"
                                           class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600
                                                  rounded-lg hover:bg-gray-200 transition">
                                            Détails
                                        </a>

                                        {{-- Approuver --}}
                                        <form method="POST"
                                              action="{{ route('admin.validations.approve', $user) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs px-3 py-1.5 bg-green-50 text-green-600
                                                           rounded-lg hover:bg-green-100 transition font-medium">
                                                Approuver
                                            </button>
                                        </form>

                                        {{-- Rejeter --}}
                                        <form method="POST"
                                              action="{{ route('admin.validations.reject', $user) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs px-3 py-1.5 bg-red-50 text-red-600
                                                           rounded-lg hover:bg-red-100 transition font-medium">
                                                Rejeter
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                @if($pending->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $pending->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

</body>
</html>