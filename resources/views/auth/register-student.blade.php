<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Étudiant — BiblioExcellence</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen py-8">

    <div class="max-w-4xl mx-auto px-4">

        {{-- En-tête --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-2xl mb-4">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952
                             11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0
                             01.665-6.479L12 14z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Inscription Étudiant</h1>
            <p class="text-gray-500 text-sm mt-1">BiblioExcellence — Bibliothèque Universitaire</p>
        </div>

        {{-- Navigation rapide + légende --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="#section-personnel" class="px-3 py-1.5 rounded-full bg-white border border-gray-200 text-xs font-medium text-gray-600 hover:border-blue-300 hover:text-blue-600 transition">1. Personnel</a>
                <a href="#section-academique" class="px-3 py-1.5 rounded-full bg-white border border-gray-200 text-xs font-medium text-gray-600 hover:border-blue-300 hover:text-blue-600 transition">2. Académique</a>
                <a href="#section-compte" class="px-3 py-1.5 rounded-full bg-white border border-gray-200 text-xs font-medium text-gray-600 hover:border-blue-300 hover:text-blue-600 transition">3. Compte</a>
                <a href="#section-documents" class="px-3 py-1.5 rounded-full bg-white border border-gray-200 text-xs font-medium text-gray-600 hover:border-blue-300 hover:text-blue-600 transition">4. Documents</a>
            </div>
            <p class="text-xs text-gray-400"><span class="text-red-500">*</span> Champs obligatoires</p>
        </div>

        {{-- Message succès --}}
        @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6 text-sm">
            {{ session('success') }}
        </div>
        @endif

        {{-- Erreurs globales --}}
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
            <p class="font-medium mb-1">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register.student') }}"
            enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Section 1 : Informations personnelles --}}
            <div id="section-personnel" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 scroll-mt-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center
                                 justify-center text-sm font-bold">1</span>
                    Informations personnelles
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Nom --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nom" value="{{ old('nom') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      @error('nom') border-red-400 @enderror">
                        @error('nom')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Prénom --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      @error('prenom') border-red-400 @enderror">
                        @error('prenom')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sexe --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Sexe <span class="text-red-500">*</span>
                        </label>
                        <select name="sexe"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500
                                       @error('sexe') border-red-400 @enderror">
                            <option value="">-- Sélectionner --</option>
                            <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                        @error('sexe')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date de naissance --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Date de naissance <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      @error('date_naissance') border-red-400 @enderror">
                        @error('date_naissance')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nationalité --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nationalité <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nationalite" value="{{ old('nationalite') }}"
                            placeholder="Ex: Togolaise"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      @error('nationalite') border-red-400 @enderror">
                        @error('nationalite')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Téléphone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Téléphone
                        </label>
                        <input type="text" name="telephone" value="{{ old('telephone') }}"
                            placeholder="Ex: +228 90 00 00 00"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    {{-- Adresse --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Adresse
                        </label>
                        <input type="text" name="adresse" value="{{ old('adresse') }}"
                            placeholder="Ex: Quartier Bè, Lomé"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                </div>
            </div>

            {{-- Section 2 : Informations académiques --}}
            <div id="section-academique" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 scroll-mt-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center
                                 justify-center text-sm font-bold">2</span>
                    Informations académiques
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Matricule (généré automatiquement) --}}
                    <div class="md:col-span-2 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 text-sm text-blue-700">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Votre matricule sera généré automatiquement</span>
                        — vous n'avez pas besoin de le saisir.
                    </div>

                    {{-- Niveau --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Niveau <span class="text-red-500">*</span>
                        </label>
                        <select name="niveau"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500
                                       @error('niveau') border-red-400 @enderror">
                            <option value="">-- Sélectionner --</option>
                            @foreach(['L1','L2','L3','M1','M2','D1','D2','D3'] as $n)
                            <option value="{{ $n }}" {{ old('niveau') == $n ? 'selected' : '' }}>
                                {{ $n }}
                            </option>
                            @endforeach
                        </select>
                        @error('niveau')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Département --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Département <span class="text-red-500">*</span>
                        </label>
                        <select name="department_id"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500
                                       @error('department_id') border-red-400 @enderror">
                            <option value="">-- Sélectionner --</option>
                            @foreach($faculties as $faculty)
                            <optgroup label="{{ $faculty->nom }}">
                                @foreach($faculty->departments as $dept)
                                <option value="{{ $dept->id }}"
                                    {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->nom }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        @error('department_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Année académique --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Année académique <span class="text-red-500">*</span>
                        </label>
                        <select name="annee_academique"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Sélectionner --</option>
                            @php
                            $year = date('Y');
                            @endphp
                            @for($i = 0; $i < 3; $i++)
                                @php $y=$year - $i; @endphp
                                <option value="{{ $y }}-{{ $y+1 }}"
                                {{ old('annee_academique') == "$y-".($y+1) ? 'selected' : '' }}>
                                {{ $y }}-{{ $y+1 }}
                                </option>
                                @endfor
                        </select>
                    </div>

                </div>
            </div>

            {{-- Section 3 : Compte --}}
            <div id="section-compte" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 scroll-mt-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center
                                 justify-center text-sm font-bold">3</span>
                    Compte et sécurité
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Email --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="exemple@email.com"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      transition-colors duration-150 hover:border-gray-300
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      @error('email') border-red-400 @enderror">
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mot de passe --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mot de passe <span class="text-red-500">*</span>
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" name="password"
                                placeholder="Minimum 8 caractères"
                                class="w-full px-4 py-2.5 pr-11 border border-gray-200 rounded-xl text-sm
                                          transition-colors duration-150 hover:border-gray-300
                                          focus:outline-none focus:ring-2 focus:ring-blue-500
                                          @error('password') border-red-400 @enderror">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                             -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                                             a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878
                                             l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59
                                             m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025
                                             0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirmation mot de passe --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmer le mot de passe <span class="text-red-500">*</span>
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                placeholder="Répétez le mot de passe"
                                class="w-full px-4 py-2.5 pr-11 border border-gray-200 rounded-xl text-sm
                                          transition-colors duration-150 hover:border-gray-300
                                          focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                             -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                                             a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878
                                             l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59
                                             m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025
                                             0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Section 4 : Documents --}}
            <div id="section-documents" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 scroll-mt-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center
                                 justify-center text-sm font-bold">4</span>
                    Documents
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Photo de profil --}}
                    <div x-data="{ fileName: '' }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Photo de profil
                            <span class="text-gray-400 font-normal">(optionnel)</span>
                        </label>
                        <input type="file" name="photo" accept=".jpg,.jpeg,.png"
                            @change="fileName = $event.target.files[0]?.name || ''"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      transition-colors duration-150 hover:border-gray-300
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                                      file:text-sm file:bg-blue-50 file:text-blue-600
                                      hover:file:bg-blue-100">
                        <p class="text-xs text-gray-400 mt-1" x-show="!fileName">JPG, JPEG, PNG — Max 2MB</p>
                        <p class="text-xs text-blue-600 mt-1 flex items-center gap-1" x-show="fileName" x-cloak>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span x-text="fileName"></span>
                        </p>
                        @error('photo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Carte étudiante --}}
                    <div x-data="{ fileName: '' }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Carte étudiante <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="carte_etudiante"
                            accept=".jpg,.jpeg,.png,.pdf"
                            @change="fileName = $event.target.files[0]?.name || ''"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                      transition-colors duration-150 hover:border-gray-300
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                                      file:text-sm file:bg-blue-50 file:text-blue-600
                                      hover:file:bg-blue-100
                                      @error('carte_etudiante') border-red-400 @enderror">
                        <p class="text-xs text-gray-400 mt-1" x-show="!fileName">JPG, JPEG, PNG, PDF — Max 2MB</p>
                        <p class="text-xs text-blue-600 mt-1 flex items-center gap-1" x-show="fileName" x-cloak>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span x-text="fileName"></span>
                        </p>
                        @error('carte_etudiante')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Barre d'action fixe --}}
            <div class="sticky bottom-0 -mx-4 px-4 py-4 bg-gray-50/90 backdrop-blur border-t border-gray-200
                        flex items-center justify-between">
                <a href="/login" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Retour à la connexion
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold
                               px-8 py-3 rounded-xl transition duration-200 text-sm shadow-lg shadow-blue-200">
                    Soumettre l'inscription
                </button>
            </div>

        </form>
    </div>

</body>

</html>