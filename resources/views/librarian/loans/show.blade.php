@extends('layouts.app')

@section('title', 'Détail emprunt — BiblioExcellence')
@section('page-title', 'Détail de l\'emprunt')

@section('content')

<div class="max-w-2xl mx-auto space-y-6">

    <a href="{{ route('librarian.loans.index') }}"
       class="text-sm text-gray-500 hover:text-gray-700 inline-block mb-2">
        ← Retour à la liste
    </a>

    {{-- Infos emprunt --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-semibold text-gray-700">Emprunt #{{ $loan->id }}</h2>
            @php
                $colors = [
                    'actif'     => 'bg-blue-50 text-blue-600',
                    'retourne'  => 'bg-green-50 text-green-600',
                    'en_retard' => 'bg-red-50 text-red-600',
                    'renouvele' => 'bg-amber-50 text-amber-600',
                ];
            @endphp
            <span class="px-3 py-1 rounded-lg text-sm font-medium
                {{ $colors[$loan->statut] ?? 'bg-gray-50 text-gray-600' }}">
                {{ ucfirst(str_replace('_', ' ', $loan->statut)) }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4">
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
                <p class="text-sm font-medium
                    {{ now()->gt($loan->date_retour_prevue) && $loan->statut !== 'retourne'
                        ? 'text-red-500' : 'text-gray-700' }}">
                    {{ $loan->date_retour_prevue->format('d/m/Y') }}
                </p>
            </div>
            @if($loan->date_retour_effective)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Retourné le</p>
                    <p class="text-sm font-medium text-gray-700">
                        {{ $loan->date_retour_effective->format('d/m/Y') }}
                    </p>
                </div>
            @endif
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Renouvellements</p>
                <p class="text-sm font-medium text-gray-700">{{ $loan->renouvellements }}/1</p>
            </div>
        </div>
    </div>

    {{-- Pénalité --}}
    @if($loan->penalty)
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
            <h3 class="font-semibold text-red-700 mb-4">Pénalité</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-red-400 uppercase tracking-wider mb-1">Jours de retard</p>
                    <p class="text-sm font-medium text-red-700">{{ $loan->penalty->jours_retard }} jours</p>
                </div>
                <div>
                    <p class="text-xs text-red-400 uppercase tracking-wider mb-1">Montant</p>
                    <p class="text-sm font-medium text-red-700">
                        {{ number_format($loan->penalty->montant, 0, ',', ' ') }} FCFA
                    </p>
                </div>
                <div>
                    <p class="text-xs text-red-400 uppercase tracking-wider mb-1">Statut</p>
                    <span class="text-xs px-2.5 py-1 rounded-lg font-medium
                        {{ $loan->penalty->statut === 'payee' ? 'bg-green-50 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ ucfirst($loan->penalty->statut) }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    {{-- Actions --}}
    @if(in_array($loan->statut, ['actif', 'en_retard', 'renouvele']))
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-3">
            <h3 class="font-semibold text-gray-700 mb-4">Actions</h3>

            {{-- Retour --}}
            <form method="POST" action="{{ route('librarian.returns.store') }}">
                @csrf
                <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                <button type="submit"
                        onclick="return confirm('Confirmer le retour de ce livre ?')"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium
                               py-2.5 rounded-xl text-sm transition">
                    Enregistrer le retour
                </button>
            </form>

            {{-- Renouvellement --}}
            @if($loan->renouvellements < 1 && $loan->statut !== 'en_retard')
                <form method="POST" action="{{ route('librarian.loans.renew', $loan) }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Renouveler cet emprunt de 14 jours ?')"
                            class="w-full bg-amber-500 hover:bg-amber-600 text-white font-medium
                                   py-2.5 rounded-xl text-sm transition">
                        Renouveler (+14 jours)
                    </button>
                </form>
            @endif
        </div>
    @endif

</div>

@endsection