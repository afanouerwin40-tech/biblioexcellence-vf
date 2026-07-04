@extends('layouts.app')

@section('title', 'Mon profil — BiblioExcellence')
@section('page-title', 'Mon profil')
@section('page-subtitle', 'Gérez vos informations personnelles')

@section('content')

<div class="max-w-2xl mx-auto space-y-6">

    {{-- Informations personnelles --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-6">Informations personnelles</h2>

        <form method="POST" action="{{ route('profile.update') }}"
            enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PATCH')

            {{-- Avatar --}}
            <div class="flex items-center gap-5 mb-6">
                <div class="w-20 h-20 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if(auth()->user()->profile_photo)
                    <img src="{{ Storage::url(auth()->user()->profile_photo) }}"
                        class="w-20 h-20 rounded-full object-cover">
                    @else
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Photo de profil</label>
                    <input type="file" name="photo" accept=".jpg,.jpeg,.png"
                        class="w-full text-sm text-gray-500
                                  file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                  file:text-sm file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG — Max 2MB</p>
                    @error('photo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nom complet <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500
                              @error('name') border-red-400 @enderror">
                @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500
                              @error('email') border-red-400 @enderror">
                @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Identifiant</label>
                <input type="text" value="{{ auth()->user()->identifier }}" disabled
                    class="w-full px-4 py-2.5 border border-gray-100 rounded-xl text-sm
                              bg-gray-50 text-gray-400 cursor-not-allowed">
                <p class="text-xs text-gray-400 mt-1">L'identifiant ne peut pas être modifié.</p>
            </div>

            {{-- Champs spécifiques selon le rôle --}}
            @php $role = auth()->user()->role_type; @endphp

            @if($role === 'student')
            @php $student = auth()->user()->student; @endphp
            <div class="border-t border-gray-100 pt-4 mt-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Informations académiques</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', $student->telephone ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                        <input type="text" name="adresse" value="{{ old('adresse', $student->adresse ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
                        <input type="text" name="niveau" value="{{ old('niveau', $student->niveau ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Année académique</label>
                        <input type="text" name="annee_academique" value="{{ old('annee_academique', $student->annee_academique ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            @endif

            @if($role === 'teacher')
            @php $teacher = auth()->user()->teacher; @endphp
            <div class="border-t border-gray-100 pt-4 mt-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Informations professionnelles</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', $teacher->telephone ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                        <input type="text" name="adresse" value="{{ old('adresse', $teacher->adresse ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                        <input type="text" name="grade" value="{{ old('grade', $teacher->grade ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Spécialité</label>
                        <input type="text" name="specialite" value="{{ old('specialite', $teacher->specialite ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            @endif

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium
                           py-2.5 rounded-xl text-sm transition">
                Enregistrer les modifications
            </button>
        </form>
    </div>

    {{-- Changer mot de passe --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-6">Changer le mot de passe</h2>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                <input type="password" name="current_password"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500
                              @error('current_password') border-red-400 @enderror">
                @error('current_password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                <input type="password" name="password" placeholder="Minimum 8 caractères"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500
                              @error('password') border-red-400 @enderror">
                @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirmer le nouveau mot de passe
                </label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium
                           py-2.5 rounded-xl text-sm transition">
                Changer le mot de passe
            </button>
        </form>
    </div>

    {{-- Informations du compte --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-4">Informations du compte</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Rôle</p>
                <p class="text-sm font-medium text-gray-700">{{ ucfirst(auth()->user()->role_type) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Statut</p>
                <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-medium">
                    Approuvé
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Membre depuis</p>
                <p class="text-sm font-medium text-gray-700">
                    {{ auth()->user()->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>

</div>

@endsection