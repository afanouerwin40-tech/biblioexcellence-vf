@extends('layouts.app')

@section('title', 'Nouvel emprunt — BiblioExcellence')
@section('page-title', 'Nouvel emprunt')
@section('page-subtitle', 'Enregistrer un emprunt')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Étape 1 : Recherche utilisateur --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-5 flex items-center gap-2">
            <span class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center
                         justify-center text-sm font-bold">1</span>
            Identifier l'utilisateur
        </h2>

        <form method="GET" action="{{ route('librarian.loans.create') }}" class="flex gap-3">
            @if(request('book_id'))
                <input type="hidden" name="book_id" value="{{ request('book_id') }}">
            @endif
            <input type="text" name="user_identifier"
                   value="{{ request('user_identifier') }}"
                   placeholder="Matricule ou email de l'étudiant/enseignant"
                   class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white
                           rounded-xl text-sm font-medium transition">
                Rechercher
            </button>
        </form>

        @if(request('user_identifier') && !$user)
            <p class="text-red-500 text-sm mt-3">Aucun utilisateur trouvé avec cet identifiant.</p>
        @endif

        @if($user)
            <div class="mt-4 p-4 rounded-xl border
                {{ $canBorrow['can'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $user->identifier }} —
                            {{ $user->role_type === 'student' ? 'Étudiant' : 'Enseignant' }}
                        </p>
                    </div>
                    @if($canBorrow['can'])
                        <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-lg font-medium">
                            Peut emprunter
                        </span>
                    @else
                        <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-lg font-medium">
                            Bloqué
                        </span>
                    @endif
                </div>
                @if(!$canBorrow['can'])
                    <p class="text-sm text-red-600 mt-2">{{ $canBorrow['reason'] }}</p>
                @endif
            </div>
        @endif
    </div>

    {{-- Étape 2 : Sélection du livre --}}
    @if($user && $canBorrow['can'])
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-700 mb-5 flex items-center gap-2">
                <span class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center
                             justify-center text-sm font-bold">2</span>
                Sélectionner le livre
            </h2>

            <form method="GET" action="{{ route('librarian.loans.create') }}" class="flex gap-3">
                <input type="hidden" name="user_identifier" value="{{ request('user_identifier') }}">
                <select name="book_id"
                        class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Sélectionner un livre disponible --</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}"
                            {{ request('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->titre }} — {{ $book->quantite_disponible }} dispo.
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white
                               rounded-xl text-sm font-medium transition">
                    Voir exemplaires
                </button>
            </form>
        </div>
    @endif

    {{-- Étape 3 : Sélection exemplaire + confirmation --}}
    @if($user && $canBorrow['can'] && request('book_id') && $copies->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-700 mb-5 flex items-center gap-2">
                <span class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center
                             justify-center text-sm font-bold">3</span>
                Confirmer l'emprunt
            </h2>

            <form method="POST" action="{{ route('librarian.loans.store') }}">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sélectionner l'exemplaire
                    </label>
                    <div class="space-y-2">
                        @foreach($copies as $copy)
                            <label class="flex items-center gap-3 p-3 border border-gray-200
                                          rounded-xl cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="book_copy_id" value="{{ $copy->id }}"
                                       class="text-blue-600">
                                <div>
                                    <p class="text-sm font-medium font-mono">{{ $copy->code_exemplaire }}</p>
                                    <p class="text-xs text-gray-400">État : {{ ucfirst($copy->etat) }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-blue-50 rounded-xl p-4 mb-5 text-sm">
                    <p class="font-medium text-blue-700 mb-2">Récapitulatif</p>
                    <p class="text-blue-600">Emprunteur : <strong>{{ $user->name }}</strong></p>
                    <p class="text-blue-600">Durée : <strong>14 jours</strong></p>
                    <p class="text-blue-600">
                        Retour prévu : <strong>{{ now()->addDays(14)->format('d/m/Y') }}</strong>
                    </p>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold
                               py-3 rounded-xl text-sm transition">
                    Valider l'emprunt
                </button>
            </form>
        </div>
    @elseif($user && $canBorrow['can'] && request('book_id') && $copies->isEmpty())
        <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-xl px-4 py-3 text-sm">
            Aucun exemplaire disponible pour ce livre.
        </div>
    @endif

</div>

@endsection