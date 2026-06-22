@extends('layouts.app')

@section('title', 'Catégories — BiblioExcellence')
@section('page-title', 'Gestion des catégories')
@section('page-subtitle', 'Organisez le catalogue par catégories')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Formulaire ajout --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-700 mb-5">Ajouter une catégorie</h2>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" value="{{ old('nom') }}"
                           placeholder="Ex: Informatique"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Catégorie parente
                    </label>
                    <select name="parent_id"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Aucune (catégorie principale) —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('parent_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3"
                              placeholder="Description de la catégorie..."
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                     focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description') }}</textarea>
                </div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium
                               py-2.5 rounded-xl text-sm transition">
                    Ajouter la catégorie
                </button>
            </form>
        </div>
    </div>

    {{-- Liste catégories --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">Liste des catégories</h2>
                <a href="{{ route('admin.books.index') }}"
                   class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">
                    ← Catalogue
                </a>
            </div>

            @if($categories->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <p class="text-sm">Aucune catégorie enregistrée</p>
                </div>
            @else
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Nom</th>
                            <th class="px-6 py-3 text-left">Sous-catégories</th>
                            <th class="px-6 py-3 text-left">Livres</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800 text-sm">
                                        {{ $category->nom }}
                                    </div>
                                    @if($category->description)
                                        <div class="text-xs text-gray-400 mt-0.5">
                                            {{ Str::limit($category->description, 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($category->children->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($category->children as $child)
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">
                                                    {{ $child->nom }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs">
                                        {{ $category->books_count }} livre(s)
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST"
                                          action="{{ route('admin.categories.destroy', $category) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Supprimer cette catégorie ?')"
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
                @if($categories->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $categories->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

</div>

@endsection