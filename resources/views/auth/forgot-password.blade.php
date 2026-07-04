<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Mot de passe oublié — BiblioExcellence</title>
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

        {{-- Contenu --}}
        <div class="relative z-10 text-center text-white">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white bg-opacity-20
                        rounded-3xl mb-8 backdrop-blur-sm">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                             C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                             C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                             C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h1 class="text-4xl font-bold mb-3">BiblioExcellence</h1>
            <p class="text-blue-100 text-lg mb-12 leading-relaxed">
                Plateforme moderne de gestion<br>de bibliothèque universitaire
            </p>
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
                                 C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800">BiblioExcellence</h1>
            </div>

            {{-- En-tête --}}
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Mot de passe oublié ?</h2>
                <p class="text-gray-500 mt-2">
                    Entrez votre email et nous vous enverrons un lien de réinitialisation.
                </p>
            </div>

            {{-- Message de statut --}}
            @if (session('status'))
            <div class="mb-4 flex items-start gap-3 bg-green-50 border border-green-200
                            text-green-700 rounded-xl px-4 py-3 text-sm">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('status') }}
            </div>
            @endif

            {{-- Erreurs --}}
            @if ($errors->any())
            <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200
                            text-red-700 rounded-xl px-4 py-3 text-sm">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            {{-- Formulaire --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Adresse email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="exemple@email.com"
                            required
                            autofocus
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm
                                   bg-white focus:outline-none focus:ring-2 focus:ring-blue-500
                                   focus:border-transparent transition placeholder-gray-400
                                   @error('email') border-red-400 bg-red-50 @enderror">
                    </div>
                </div>

                {{-- Bouton --}}
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                               text-white font-semibold py-3 rounded-xl transition duration-200
                               text-sm shadow-lg shadow-blue-200 mt-2">
                    Envoyer le lien de réinitialisation
                </button>

            </form>

            {{-- Lien retour connexion --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-500">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 hover:underline font-medium">
                        ← Retour à la connexion
                    </a>
                </p>
            </div>

            <p class="text-center text-xs text-gray-400 mt-8">
                © {{ date('Y') }} BiblioExcellence — Tous droits réservés
            </p>

        </div>
    </div>

</body>

</html>