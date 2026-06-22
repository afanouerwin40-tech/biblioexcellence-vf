@extends('layouts.app')

@section('title', 'Auteurs — BiblioExcellence')
@section('page-title', 'Gestion des auteurs')
@section('page-subtitle', 'Ajoutez et gérez les auteurs du catalogue')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Formulaire ajout --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-700 mb-5">Ajouter un auteur</h2>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.authors.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" value="{{ old('nom') }}"
                           placeholder="Ex: HUGO"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}"
                           placeholder="Ex: Victor"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nationalité</label>
                    <input type="text" name="nationalite" value="{{ old('nationalite') }}"
                           placeholder="Ex: Française"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Biographie</label>
                    <textarea name="biographie" rows="3"
                              placeholder="Courte biographie..."
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                     focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('biographie') }}</textarea>
                </div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium
                               py-2.5 rounded-xl text-sm transition">
                    Ajouter l'auteur
                </button>
            </form>
        </div>
    </div>

    {{-- Liste auteurs --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">
                    Liste des auteurs
                    <span class="text-gray-400 font-normal text-sm ml-2">{{ $authors->total() }} au total</span>
                </h2>
                <a href="{{ route('admin.books.index') }}"
                   class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">
                    ← Catalogue
                </a>
            </div>

            @if($authors->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <p class="text-sm">Aucun auteur enregistré</p>
                </div>
            @else
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Nom</th>
                            <th class="px-6 py-3 text-left">Nationalité</th>
                            <th class="px-6 py-3 text-left">Livres</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($authors as $author)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800 text-sm">
                                        {{ $author->prenom }} {{ $author->nom }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $author->nationalite ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs">
                                        {{ $author->books_count }} livre(s)
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST"
                                          action="{{ route('admin.authors.destroy', $author) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Supprimer cet auteur ?')"
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
                @if($authors->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $authors->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

</div>

@endsection