@extends('layouts.app')

@section('title', 'Modifier — ' . $book->titre)
@section('page-title', 'Modifier un livre')
@section('page-subtitle', $book->titre)

@section('content')

@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
        <p class="font-medium mb-1">Veuillez corriger les erreurs :</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.books.update', $book) }}"
      enctype="multipart/form-data" class="space-y-6">
    @csrf @method('PUT')

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-5">Informations du livre</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Titre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="titre" value="{{ old('titre', $book->titre) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Auteur *</label>
                <select name="author_id"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}"
                            {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                            {{ $author->prenom }} {{ $author->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                <select name="category_id"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->nom }}
                        </option>
                        @foreach($category->children as $child)
                            <option value="{{ $child->id }}"
                                {{ old('category_id', $book->category_id) == $child->id ? 'selected' : '' }}>
                                &nbsp;&nbsp;— {{ $child->nom }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Éditeur</label>
                <input type="text" name="editeur" value="{{ old('editeur', $book->editeur) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                <input type="number" name="annee" value="{{ old('annee', $book->annee) }}"
                       min="1000" max="{{ date('Y') }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Langue *</label>
                <select name="langue"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['Français', 'Anglais', 'Espagnol', 'Arabe', 'Allemand', 'Portugais'] as $langue)
                        <option value="{{ $langue }}"
                            {{ old('langue', $book->langue) == $langue ? 'selected' : '' }}>
                            {{ $langue }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Emplacement</label>
                <input type="text" name="emplacement" value="{{ old('emplacement', $book->emplacement) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description', $book->description) }}</textarea>
            </div>

        </div>
    </div>

    {{-- Couverture --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Couverture</h2>
        @if($book->couverture)
            <div class="flex items-center gap-4 mb-4">
                <img src="{{ Storage::url($book->couverture) }}"
                     class="w-16 h-24 object-cover rounded-lg shadow-sm">
                <p class="text-sm text-gray-500">Couverture actuelle. Uploadez une nouvelle pour la remplacer.</p>
            </div>
        @endif
        <input type="file" name="couverture" accept=".jpg,.jpeg,.png"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                      file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                      file:text-sm file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
        <p class="text-xs text-gray-400 mt-1">JPG, JPEG, PNG — Max 2MB</p>
    </div>

    {{-- Boutons --}}
    <div class="flex items-center justify-between pb-8">
        <a href="{{ route('admin.books.show', $book) }}"
           class="text-sm text-gray-500 hover:text-gray-700">← Annuler</a>
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold
                       px-8 py-3 rounded-xl transition text-sm shadow-lg shadow-blue-200">
            Enregistrer les modifications
        </button>
    </div>

</form>

@endsection