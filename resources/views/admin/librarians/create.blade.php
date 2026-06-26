@extends('layouts.app')

@section('title', 'Ajouter bibliothécaire — BiblioExcellence')
@section('page-title', 'Ajouter un bibliothécaire')
@section('page-subtitle', 'Créer un nouveau compte bibliothécaire')

@section('content')

<div class="max-w-2xl mx-auto">

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

    <form method="POST" action="{{ route('admin.librarians.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-700 mb-5">Informations personnelles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" value="{{ old('nom') }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  @error('nom') border-red-400 @enderror">
                    @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Prénom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  @error('prenom') border-red-400 @enderror">
                    @error('prenom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Matricule professionnel <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="matricule_pro" value="{{ old('matricule_pro') }}"
                           placeholder="Ex: BIB2024001"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  @error('matricule_pro') border-red-400 @enderror">
                    @error('matricule_pro')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}"
                           placeholder="+228 90 00 00 00"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                    <input type="text" name="adresse" value="{{ old('adresse') }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-700 mb-5">Compte et accès</h2>
            <div class="space-y-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Mot de passe <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" placeholder="Minimum 8 caractères"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  @error('password') border-red-400 @enderror">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Confirmer le mot de passe <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="bg-blue-50 rounded-xl p-4 text-sm text-blue-700">
                    Le compte sera créé directement avec le statut <strong>Approuvé</strong>.
                    Le bibliothécaire peut se connecter immédiatement avec son matricule.
                </div>

            </div>
        </div>

        <div class="flex items-center justify-between pb-8">
            <a href="{{ route('admin.librarians.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Annuler</a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold
                           px-8 py-3 rounded-xl transition text-sm shadow-lg shadow-blue-200">
                Créer le bibliothécaire
            </button>
        </div>

    </form>
</div>

@endsection