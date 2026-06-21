<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — BiblioExcellence</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex">

    {{-- Panneau gauche — illustration --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700
                flex-col items-center justify-center p-12 relative overflow-hidden">

        {{-- Cercles décoratifs --}}
        <div class="absolute top-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full translate-x-1/3 translate-y-1/3"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-white opacity-5 rounded-full"></div>

        {{-- Contenu gauche --}}
        <div class="relative z-10 text-center text-white">

            {{-- Icône livre --}}
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white bg-opacity-20
                        rounded-3xl mb-8 backdrop-blur-sm">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                             C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                             C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                             C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>

            <h1 class="text-4xl font-bold mb-3">BiblioExcellence</h1>
            <p class="text-blue-100 text-lg mb-12 leading-relaxed">
                Plateforme moderne de gestion<br>de bibliothèque universitaire
            </p>

            {{-- Statistiques --}}
            <div class="grid grid-cols-3 gap-6 mt-4">
                <div class="bg-white bg-opacity-10 rounded-2xl p-4 backdrop-blur-sm">
                    <p class="text-3xl font-bold">50K+</p>
                    <p class="text-blue-200 text-xs mt-1">Étudiants</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-2xl p-4 backdrop-blur-sm">
                    <p class="text-3xl font-bold">10K+</p>
                    <p class="text-blue-200 text-xs mt-1">Livres</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-2xl p-4 backdrop-blur-sm">
                    <p class="text-3xl font-bold">4</p>
                    <p class="text-blue-200 text-xs mt-1">Rôles</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Panneau droit — formulaire --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 p-6 lg:p-16">

        <div class="w-full max-w-md">

            {{-- Logo mobile uniquement --}}
            <div class="lg:hidden text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-2xl mb-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                                 C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                                 C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                                 C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800">BiblioExcellence</h1>
            </div>

            {{-- En-tête formulaire --}}
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Bon retour 👋</h2>
                <p class="text-gray-500 mt-2">Connectez-vous à votre espace personnel</p>
            </div>

            {{-- Message d'erreur --}}
            @if ($errors->any())
                <div class="flex items-start gap-3 bg-red-50 border border-red-200
                            text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first('identifier') }}</span>
                </div>
            @endif

            {{-- Formulaire --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Identifiant --}}
                <div>
                    <label for="identifier" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Identifiant
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="identifier"
                            name="identifier"
                            value="{{ old('identifier') }}"
                            placeholder="Email, matricule étudiant ou professionnel"
                            autofocus
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm
                                   bg-white focus:outline-none focus:ring-2 focus:ring-blue-500
                                   focus:border-transparent transition placeholder-gray-400
                                   @error('identifier') border-red-400 bg-red-50 @enderror"
                        >
                    </div>
                </div>

                {{-- Mot de passe --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Mot de passe
                        </label>
                        <a href="/forgot-password"
                            class="text-xs text-blue-600 hover:text-blue-700 hover:underline">
                            Mot de passe oublié ?
                        </a>
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            :type="show ? 'text' : 'password'"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            class="w-full pl-11 pr-12 py-3 border border-gray-200 rounded-xl text-sm
                                   bg-white focus:outline-none focus:ring-2 focus:ring-blue-500
                                   focus:border-transparent transition placeholder-gray-400"
                        >
                        {{-- Bouton afficher/masquer --}}
                        <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                         -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                                         a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878
                                         l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59
                                         m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025
                                         0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Bouton connexion --}}
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                               text-white font-semibold py-3 rounded-xl transition duration-200
                               text-sm shadow-lg shadow-blue-200 mt-2">
                    Se connecter
                </button>

            </form>

            {{-- Liens inscription --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-500 mb-4">Pas encore de compte ?</p>
                <div class="grid grid-cols-2 gap-3">
                    <a href="/register/student"
                       class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200
                              rounded-xl text-sm text-gray-600 hover:bg-gray-50 hover:border-blue-300
                              hover:text-blue-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0
                                     01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0
                                     00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                        Étudiant
                    </a>
                    <a href="/register/teacher"
                       class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200
                              rounded-xl text-sm text-gray-600 hover:bg-gray-50 hover:border-blue-300
                              hover:text-blue-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183
                                     0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0
                                     00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2
                                     2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Enseignant
                    </a>
                </div>
            </div>

            <p class="text-center text-xs text-gray-400 mt-8">
                © {{ date('Y') }} BiblioExcellence — Tous droits réservés
            </p>

        </div>
    </div>

</body>
</html>