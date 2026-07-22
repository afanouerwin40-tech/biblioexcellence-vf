<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statut de mon compte — BiblioExcellence</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (($account['status'] ?? null) === 'approved')
        <meta http-equiv="refresh" content="5;url={{ $account['login_url'] }}">
    @endif
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

            @if (! $account)
                {{-- Aucune info disponible : ni inscription récente, ni tentative de connexion --}}
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-3xl mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Statut de compte</h2>
                    <p class="text-gray-500 mt-3 leading-relaxed">
                        Aucune information de compte à afficher pour le moment.
                        Connectez-vous pour vérifier l'état de votre dossier.
                    </p>
                </div>
                <a href="{{ route('login') }}"
                    class="w-full block text-center bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                           text-white font-semibold py-3 rounded-xl transition duration-200
                           text-sm shadow-lg shadow-blue-200">
                    Aller à la connexion
                </a>

            @elseif ($account['status'] === 'approved')
                {{-- Compte approuvé : bonne nouvelle, redirection vers login --}}
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-3xl mb-6">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Bonne nouvelle, {{ $account['name'] }} !</h2>
                    <p class="text-gray-500 mt-3 leading-relaxed">
                        Votre compte a été <span class="text-green-600 font-semibold">approuvé</span> par
                        un administrateur. Vous pouvez maintenant vous connecter.
                    </p>
                </div>

                <a href="{{ $account['login_url'] }}"
                    class="w-full block text-center bg-green-600 hover:bg-green-700 active:bg-green-800
                           text-white font-semibold py-3 rounded-xl transition duration-200
                           text-sm shadow-lg shadow-green-200">
                    Se connecter maintenant
                </a>
                <p class="text-center text-xs text-gray-400 mt-4">
                    Redirection automatique dans quelques secondes...
                </p>

            @elseif (in_array($account['status'], ['rejected', 'suspended']))
                {{-- Compte rejeté ou suspendu --}}
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-3xl mb-6">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $account['status'] === 'rejected' ? 'Inscription refusée' : 'Compte suspendu' }}
                    </h2>
                    <p class="text-gray-500 mt-3 leading-relaxed">
                        Bonjour {{ $account['name'] }}, votre compte a été
                        {{ $account['status'] === 'rejected' ? 'refusé' : 'suspendu' }} par un administrateur.
                    </p>
                </div>

                @if ($account['rejection_reason'])
                <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-6 text-sm">
                    <p class="text-red-700 font-medium mb-1">Motif indiqué :</p>
                    <p class="text-red-600">{{ $account['rejection_reason'] }}</p>
                </div>
                @else
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 mb-6 text-sm text-gray-500">
                    Aucun motif détaillé n'a été renseigné. Contactez l'administration pour plus d'informations.
                </div>
                @endif

                <a href="mailto:bibliotheque@excellence.tg"
                    class="w-full block text-center bg-gray-800 hover:bg-gray-900 text-white font-semibold
                           py-3 rounded-xl transition duration-200 text-sm">
                    Contacter l'administration
                </a>

            @else
                {{-- En attente de validation (statut par défaut) --}}
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
                        Merci, {{ $account['name'] }} ! Un administrateur doit vérifier vos informations
                        avant que vous puissiez vous connecter.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-6 text-sm divide-y divide-gray-100">
                    <div class="flex justify-between py-2 first:pt-0">
                        <span class="text-gray-500">Profil</span>
                        <span class="text-gray-800 font-medium">{{ $account['role'] }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Email</span>
                        <span class="text-gray-800 font-medium">{{ $account['email'] }}</span>
                    </div>
                    @if ($account['matricule'])
                    <div class="flex justify-between py-2 last:pb-0">
                        <span class="text-gray-500">Matricule</span>
                        <span class="text-gray-800 font-medium">{{ $account['matricule'] }}</span>
                    </div>
                    @endif
                </div>

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

                <div class="flex flex-col gap-3">
                    <a href="{{ route('pending.approval') }}"
                        class="w-full text-center bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                               text-white font-semibold py-3 rounded-xl transition duration-200
                               text-sm shadow-lg shadow-blue-200">
                        Vérifier à nouveau mon statut
                    </a>
                    <a href="{{ route('login') }}" class="w-full text-center text-sm text-gray-500 hover:text-gray-700 py-2">
                        ← Retour à la connexion
                    </a>
                </div>
            @endif

            <p class="text-center text-xs text-gray-400 mt-8">
                © {{ date('Y') }} BiblioExcellence — Tous droits réservés
            </p>

        </div>
    </div>

</body>

</html>
