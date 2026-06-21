<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un livre — BiblioExcellence</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen py-8">

    <div class="max-w-4xl mx-auto px-4">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Ajouter un livre</h1>
                <p class="text-gray-500 text-sm mt-1">Remplissez les informations du livre</p>
            </div>
            <a href="{{ route('admin.books.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Retour au catalogue
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
                <p class="font-medium mb-1">Veuillez corriger les erreurs :</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.books.store') }}"
              enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Informations principales --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center
                                 justify-center text-sm font-bold">1</span>
                    Informations principales
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Titre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="titre" value="{{ old('titre') }}"
                               placeholder="Titre du livre"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      @error('titre') border-red-400 @enderror">
                        @error('titre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Auteur <span class="text-red-500">*</span>
                        </label>
                        <select name="author_id"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500
                                       @error('author_id') border-red-400 @enderror">
                            <option value="">-- Sélectionner un auteur --</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}"
                                    {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                    {{ $author->prenom }} {{ $author->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('author_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500
                                       @error('category_id') border-red-400 @enderror">
                            <option value="">-- Sélectionner une catégorie --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nom }}
                                </option>
                                @foreach($category->children as $child)
                                    <option value="{{ $child->id }}"
                                        {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;— {{ $child->nom }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                        <input type="text" name="isbn" value="{{ old('isbn') }}"
                               placeholder="Ex: 978-2-1234-5678-9"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Éditeur</label>
                        <input type="text" name="editeur" value="{{ old('editeur') }}"
                               placeholder="Ex: Dunod"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                        <input type="number" name="annee" value="{{ old('annee') }}"
                               placeholder="{{ date('Y') }}" min="1000" max="{{ date('Y') }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Langue <span class="text-red-500">*</span>
                        </label>
                        <select name="langue"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['Français', 'Anglais', 'Espagnol', 'Arabe', 'Allemand', 'Portugais'] as $langue)
                                <option value="{{ $langue }}"
                                    {{ old('langue', 'Français') == $langue ? 'selected' : '' }}>
                                    {{ $langue }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Quantité <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantite" value="{{ old('quantite', 1) }}"
                               min="1"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      @error('quantite') border-red-400 @enderror">
                        @error('quantite')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Emplacement</label>
                        <input type="text" name="emplacement" value="{{ old('emplacement') }}"
                               placeholder="Ex: Étagère A-12"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3"
                                  placeholder="Résumé du livre..."
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                         focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- Couverture --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center
                                 justify-center text-sm font-bold">2</span>
                    Couverture
                </h2>
                <input type="file" name="couverture" accept=".jpg,.jpeg,.png"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                              file:text-sm file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                <p class="text-xs text-gray-400 mt-1">JPG, JPEG, PNG — Max 2MB — Sera redimensionnée en 400×600px</p>
                @error('couverture')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Boutons --}}
            <div class="flex items-center justify-between pb-8">
                <a href="{{ route('admin.books.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Annuler
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold
                               px-8 py-3 rounded-xl transition text-sm shadow-lg shadow-blue-200">
                    Enregistrer le livre
                </button>
            </div>

        </form>
    </div>
</body>
</html>