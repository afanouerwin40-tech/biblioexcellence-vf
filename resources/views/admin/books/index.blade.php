<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue — BiblioExcellence</title>
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
            <span class="text-sm text-gray-500">Catalogue</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
                Tableau de bord
            </a>
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
                <h1 class="text-2xl font-bold text-gray-800">Catalogue des livres</h1>
                <p class="text-gray-500 text-sm mt-1">Gérez le fonds documentaire</p>
            </div>
            <a href="{{ route('admin.books.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5
                      rounded-xl text-sm transition shadow-lg shadow-blue-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter un livre
            </a>
        </div>

        {{-- Message --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Statistiques --}}
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Total livres</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <p class="text-3xl font-bold text-green-500">{{ $stats['disponibles'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Disponibles</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <p class="text-3xl font-bold text-red-500">{{ $stats['epuises'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Épuisés</p>
            </div>
        </div>

        {{-- Tableau --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">Liste des livres</h2>
                <div class="flex gap-3">
                    <a href="{{ route('admin.authors.index') }}"
                       class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">
                        Auteurs
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                       class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">
                        Catégories
                    </a>
                </div>
            </div>

            @if($books->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                                 C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                                 C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                                 C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-sm">Aucun livre dans le catalogue</p>
                    <a href="{{ route('admin.books.create') }}" class="text-blue-500 text-sm hover:underline mt-2 inline-block">
                        Ajouter le premier livre
                    </a>
                </div>
            @else
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Couverture</th>
                            <th class="px-6 py-3 text-left">Titre</th>
                            <th class="px-6 py-3 text-left">Auteur</th>
                            <th class="px-6 py-3 text-left">Catégorie</th>
                            <th class="px-6 py-3 text-left">Stock</th>
                            <th class="px-6 py-3 text-left">Disponible</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($books as $book)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-3">
                                    @if($book->couverture)
                                        <img src="{{ Storage::url($book->couverture) }}"
                                             alt="{{ $book->titre }}"
                                             class="w-10 h-14 object-cover rounded-lg shadow-sm">
                                    @else
                                        <div class="w-10 h-14 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <div class="font-medium text-gray-800 text-sm max-w-xs truncate">
                                        {{ $book->titre }}
                                    </div>
                                    @if($book->isbn)
                                        <div class="text-xs text-gray-400 font-mono">{{ $book->isbn }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">
                                    {{ $book->author->full_name ?? $book->author->nom }}
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs">
                                        {{ $book->category->nom }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">
                                    {{ $book->quantite }}
                                </td>
                                <td class="px-6 py-3">
                                    @if($book->isAvailable())
                                        <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-medium">
                                            {{ $book->quantite_disponible }} dispo.
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-medium">
                                            Épuisé
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.books.show', $book) }}"
                                           class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">
                                            Voir
                                        </a>
                                        <a href="{{ route('admin.books.edit', $book) }}"
                                           class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100">
                                            Modifier
                                        </a>
                                        <form method="POST" action="{{ route('admin.books.destroy', $book) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Supprimer ce livre ?')"
                                                    class="text-xs px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($books->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $books->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</body>
</html>