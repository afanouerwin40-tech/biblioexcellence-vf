@extends('layouts.app')

@section('title', 'Emprunts — BiblioExcellence')
@section('page-title', 'Gestion des emprunts')
@section('page-subtitle', 'Emprunts en cours')

@section('content')

{{-- Actions rapides --}}
<div class="flex gap-3 mb-6">
    <a href="{{ route('librarian.loans.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5
              rounded-xl text-sm transition shadow-lg shadow-blue-200 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvel emprunt
    </a>
    <a href="{{ route('librarian.returns.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2.5
              rounded-xl text-sm transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Enregistrer un retour
    </a>
</div>

{{-- Statistiques --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-blue-600">{{ $stats['actifs'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Emprunts actifs</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-red-500">{{ $stats['en_retard'] }}</p>
        <p class="text-xs text-gray-500 mt-1">En retard</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-amber-500">{{ $stats['renouvele'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Renouvelés</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-gray-700">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total emprunts</p>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-700">Emprunts en cours</h2>
    </div>

    @if($loans->isEmpty())
        <div class="text-center py-16 text-gray-400 text-sm">
            Aucun emprunt en cours
        </div>
    @else
        <table class="w-full">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Utilisateur</th>
                    <th class="px-6 py-3 text-left">Livre</th>
                    <th class="px-6 py-3 text-left">Exemplaire</th>
                    <th class="px-6 py-3 text-left">Emprunté le</th>
                    <th class="px-6 py-3 text-left">Retour prévu</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($loans as $loan)
                    @php
                        $isLate   = now()->gt($loan->date_retour_prevue);
                        $daysLeft = now()->diffInDays($loan->date_retour_prevue, false);
                        $colors   = [
                            'actif'     => 'bg-blue-50 text-blue-600',
                            'en_retard' => 'bg-red-50 text-red-600',
                            'renouvele' => 'bg-amber-50 text-amber-600',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50 transition {{ $isLate ? 'bg-red-50/30' : '' }}">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-700">{{ $loan->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $loan->user->identifier }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700 max-w-xs truncate">
                                {{ $loan->bookCopy->book->titre ?? '—' }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-500">
                            {{ $loan->bookCopy->code_exemplaire }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $loan->date_emprunt->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm {{ $isLate ? 'text-red-500 font-medium' : 'text-gray-600' }}">
                                {{ $loan->date_retour_prevue->format('d/m/Y') }}
                            </p>
                            @if($isLate)
                                <p class="text-xs text-red-400">{{ abs($daysLeft) }}j de retard</p>
                            @elseif($daysLeft <= 3)
                                <p class="text-xs text-amber-400">{{ $daysLeft }}j restant(s)</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium
                                {{ $colors[$loan->statut] ?? 'bg-gray-50 text-gray-600' }}">
                                {{ ucfirst(str_replace('_', ' ', $loan->statut)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('librarian.loans.show', $loan) }}"
                                   class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600
                                          rounded-lg hover:bg-gray-200">
                                    Détails
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($loans->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $loans->links() }}
            </div>
        @endif
    @endif
</div>

@endsection