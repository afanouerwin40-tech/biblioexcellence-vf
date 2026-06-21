<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin — BiblioExcellence</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow p-8 text-center">
        <h1 class="text-2xl font-bold text-blue-600 mb-2">Tableau de bord Administrateur</h1>
        <p class="text-gray-500">Bienvenue, {{ auth()->user()->name }}</p>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600">
                Se déconnecter
            </button>
        </form>
    </div>
</body>
</html>