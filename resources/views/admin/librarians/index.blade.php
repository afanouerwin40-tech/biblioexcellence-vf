@extends('layouts.app')

@section('title', 'Bibliothécaires — BiblioExcellence')
@section('page-title', 'Gestion des bibliothécaires')
@section('page-subtitle', 'Gérez les comptes bibliothécaires')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.librarians.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5
              rounded-xl text-sm transition shadow-lg shadow-blue-200 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter un bibliothécaire
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-700">Liste des bibliothécaires</h2>
    </div>

    @if($librarians->isEmpty())
        <div class="text-center py-16 text-gray-400 text-sm">
            Aucun bibliothécaire enregistré
        </div>
    @else
        <table class="w-full">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Nom</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Matricule</th>
                    <th class="px-6 py-3 text-left">Téléphone</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($librarians as $librarian)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold text-green-600">
                                        {{ strtoupper(substr($librarian->prenom, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">
                                        {{ $librarian->prenom }} {{ $librarian->nom }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $librarian->user->email }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ $librarian->matricule_pro }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $librarian->telephone ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-medium">
                                Actif
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST"
                                  action="{{ route('admin.librarians.destroy', $librarian) }}">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Supprimer ce bibliothécaire ?')"
                                        class="text-xs px-3 py-1.5 bg-red-50 text-red-600
                                               rounded-lg hover:bg-red-100 transition">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($librarians->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $librarians->links() }}</div>
        @endif
    @endif
</div>

@endsection