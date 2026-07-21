<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription en attente — BiblioExcellence</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex">

    {{-- Panneau gauche — illustration --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700
                flex-col items-center justify-center p-12 relative overflow-hidden">

        <div class="absolute top-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full translate-x-1/3 translate-y-1/3"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-white opacity-5 rounded-full"></div>

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

    {{-- Panneau droit — statut --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 p-6 lg:p-16">

        <div class="w-full max-w-md">

            {{-- Logo mobile --}}
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

            {{-- Icône statut --}}
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-100
                            rounded-3xl mb-6">
                    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Inscription en attente de validation</h2>
                <p class="text-gray-500 mt-3 leading-relaxed">
                    @if (session('registered_name'))
                    Merci, {{ session('registered_name') }} ! Votre dossier a bien été reçu.
                    @else
                    Votre dossier a bien été reçu.
                    @endif
                    Un administrateur doit vérifier vos informations avant que vous puissiez vous connecter.
                </p>
            </div>

            {{-- Récapitulatif de l'inscription --}}
            @if (session('registered_email'))
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-6 text-sm divide-y divide-gray-100">
                <div class="flex justify-between py-2 first:pt-0">
                    <span class="text-gray-500">Profil</span>
                    <span class="text-gray-800 font-medium">{{ session('registered_role') }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Email</span>
                    <span class="text-gray-800 font-medium">{{ session('registered_email') }}</span>
                </div>
                @if (session('registered_matricule'))
                <div class="flex justify-between py-2 last:pb-0">
                    <span class="text-gray-500">Matricule</span>
                    <span class="text-gray-800 font-medium">{{ session('registered_matricule') }}</span>
                </div>
                @endif
            </div>
            @endif

            {{-- Étapes --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-6">
                <div class="flex items-start gap-3 pb-4">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Dossier soumis</p>
                        <p class="text-xs text-gray-400">Votre formulaire a été enregistré</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 pb-4">
                    <div class="w-6 h-6 rounded-full bg-amber-100 border-2 border-amber-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Vérification par un administrateur</p>
                        <p class="text-xs text-gray-400">En cours — cela peut prendre quelques heures</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-gray-100 border-2 border-gray-300 flex-shrink-0 mt-0.5"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-400">Compte validé</p>
                        <p class="text-xs text-gray-400">Vous pourrez alors vous connecter</p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <a href="{{ route('login') }}"
                class="w-full block text-center bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                       text-white font-semibold py-3 rounded-xl transition duration-200
                       text-sm shadow-lg shadow-blue-200">
                Retour à la connexion
            </a>

            <p class="text-center text-xs text-gray-400 mt-8">
                © {{ date('Y') }} BiblioExcellence — Tous droits réservés
            </p>

        </div>
    </div>

</body>

</html>