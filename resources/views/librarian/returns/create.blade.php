@extends('layouts.app')

@section('title', 'Retour de livre — BiblioExcellence')
@section('page-title', 'Enregistrer un retour')
@section('page-subtitle', 'Rechercher par code exemplaire')

@section('content')

<div class="max-w-2xl mx-auto space-y-6">

    {{-- Recherche --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-5">Rechercher l'emprunt</h2>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="GET" action="{{ route('librarian.returns.create') }}" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Code exemplaire (ex: EX-1-001)"
                   autofocus
                   class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                          focus:outline-none focus:ring-2 focus:ring-green-500">
            <button type="submit"
                    class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white
                           rounded-xl text-sm font-medium transition">
                Rechercher
            </button>
        </form>
    </div>

    {{-- Résultat --}}
    @if($loan)
        @php
            $isLate  = now()->gt($loan->date_retour_prevue);
            $jours   = $isLate ? now()->diffInDays($loan->date_retour_prevue) : 0;
            $penalite = $jours * 100;
        @endphp

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-700 mb-5">Détails de l'emprunt</h2>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Emprunteur</p>
                    <p class="text-sm font-medium text-gray-700">{{ $loan->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $loan->user->identifier }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Livre</p>
                    <p class="text-sm font-medium text-gray-700">{{ $loan->bookCopy->book->titre }}</p>
                    <p class="text-xs font-mono text-gray-400">{{ $loan->bookCopy->code_exemplaire }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Date emprunt</p>
                    <p class="text-sm font-medium text-gray-700">{{ $loan->date_emprunt->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Retour prévu</p>
                    <p class="text-sm font-medium {{ $isLate ? 'text-red-500' : 'text-gray-700' }}">
                        {{ $loan->date_retour_prevue->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            {{-- Alerte retard --}}
            @if($isLate)
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
                    <p class="text-sm font-medium text-red-700">
                        Retard de {{ $jours }} jour(s) — Pénalité estimée :
                        <strong>{{ number_format($penalite, 0, ',', ' ') }} FCFA</strong>
                    </p>
                    <p class="text-xs text-red-500 mt-1">
                        La pénalité sera créée automatiquement.
                    </p>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-5">
                    <p class="text-sm text-green-700">Retour dans les délais. Aucune pénalité.</p>
                </div>
            @endif

            {{-- Confirmation --}}
            <form method="POST" action="{{ route('librarian.returns.store') }}">
                @csrf
                <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                <button type="submit"
                        onclick="return confirm('Confirmer le retour ?')"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold
                               py-3 rounded-xl text-sm transition">
                    Confirmer le retour
                </button>
            </form>
        </div>
    @endif

</div>

@endsection