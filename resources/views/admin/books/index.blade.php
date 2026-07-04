@extends('layouts.app')

@section('title', 'Catalogue — BiblioExcellence')
@section('page-title', 'Catalogue des livres')
@section('page-subtitle', 'Gérez le fonds documentaire')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.books.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5
              rounded-xl text-sm transition shadow-lg shadow-blue-200 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter un livre
    </a>
</div>

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

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-700">Liste des livres</h2>
        <div class="flex gap-3">
            <a href="{{ route('admin.authors.index') }}" class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">Auteurs</a>
            <a href="{{ route('admin.categories.index') }}" class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">Catégories</a>
        </div>
    </div>

    @if($books->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <p class="text-sm">Aucun livre dans le catalogue</p>
        <a href="{{ route('admin.books.create') }}" class="text-blue-500 text-sm hover:underline mt-2 inline-block">Ajouter le premier livre</a>
    </div>
    @else
    <div class="overflow-x-auto -mx-4 lg:-mx-6">
        <div class="inline-block min-w-full align-middle px-4 lg:px-6">
            <table class="min-w-full divide-y divide-gray-200">
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
                            <img src="{{ Storage::url($book->couverture) }}" class="w-10 h-14 object-cover rounded-lg shadow-sm">
                            @else
                            <div class="w-10 h-14 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13" />
                                </svg>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <div class="font-medium text-gray-800 text-sm max-w-xs truncate">{{ $book->titre }}</div>
                            @if($book->isbn)
                            <div class="text-xs text-gray-400 font-mono">{{ $book->isbn }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $book->author->nom }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs">{{ $book->category->nom }}</span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $book->quantite }}</td>
                        <td class="px-6 py-3">
                            @if($book->isAvailable())
                            <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-medium">{{ $book->quantite_disponible }} dispo.</span>
                            @else
                            <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-medium">Épuisé</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.books.show', $book) }}" class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">Voir</a>
                                <a href="{{ route('admin.books.edit', $book) }}" class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100">Modifier</a>
                                <form method="POST" action="{{ route('admin.books.destroy', $book) }}">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Supprimer ce livre ?')" class="text-xs px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($books->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $books->links() }}</div>
    @endif
    @endif
</div>

@endsection