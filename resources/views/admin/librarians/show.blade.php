@extends('layouts.app')

@section('title', 'Détails du bibliothécaire — BiblioExcellence')
@section('page-title', 'Détails du bibliothécaire')
@section('page-subtitle', $librarian->prenom . ' ' . $librarian->nom)

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Bouton retour --}}
    <div class="mb-6">
        <a href="{{ route('admin.librarians.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>

    {{-- Carte principale --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- En-tête avec photo --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-4">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-xl font-bold text-green-600">
                {{ strtoupper(substr($librarian->prenom, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">{{ $librarian->prenom }} {{ $librarian->nom }}</h1>
                <p class="text-sm text-gray-500">Matricule : <span class="font-mono">{{ $librarian->matricule_pro }}</span></p>
            </div>
            <div class="ml-auto">
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600">
                    {{ ucfirst($librarian->user->role_type ?? '') }}
                </span>
            </div>
        </div>

        {{-- Corps --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Colonne gauche : infos personnelles --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Informations personnelles</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-xs text-gray-400">Nom complet</span>
                        <p class="text-sm font-medium text-gray-800">{{ $librarian->prenom }} {{ $librarian->nom }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">Téléphone</span>
                        <p class="text-sm text-gray-700">{{ $librarian->telephone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">Adresse</span>
                        <p class="text-sm text-gray-700">{{ $librarian->adresse ?? 'Non renseignée' }}</p>
                    </div>
                </div>
            </div>

            {{-- Colonne droite : compte utilisateur --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Compte utilisateur</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-xs text-gray-400">Email</span>
                        <p class="text-sm text-gray-700">{{ $librarian->user->email ?? '—' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">Identifiant de connexion</span>
                        <p class="text-sm font-mono text-gray-700">{{ $librarian->matricule_pro }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">Statut du compte</span>
                        @php
                        $statusColors = [
                        'approved' => 'text-green-600 bg-green-50',
                        'pending' => 'text-yellow-600 bg-yellow-50',
                        'rejected' => 'text-red-600 bg-red-50',
                        'suspended'=> 'text-gray-600 bg-gray-50',
                        ];
                        $status = $librarian->user->status ?? 'unknown';
                        $color = $statusColors[$status] ?? 'text-gray-600 bg-gray-50';
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $color }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">Date d'inscription</span>
                        <p class="text-sm text-gray-700">{{ $librarian->created_at ? $librarian->created_at->format('d/m/Y à H:i') : '—' }}</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Actions en bas --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
            <a href="{{ route('admin.librarians.index') }}"
                class="text-sm px-4 py-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                Fermer
            </a>
            <form method="POST" action="{{ route('admin.librarians.destroy', $librarian) }}" class="inline">
                @csrf @method('DELETE')
                <button onclick="return confirm('Supprimer ce bibliothécaire ?')"
                    class="text-sm px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

@endsection