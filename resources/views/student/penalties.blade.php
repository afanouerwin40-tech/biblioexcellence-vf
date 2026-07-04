@extends('layouts.app')

@section('title', 'Mes pénalités — BiblioExcellence')
@section('page-title', 'Mes pénalités')
@section('page-subtitle', 'Historique de vos pénalités')

@section('content')

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-700">Liste des pénalités</h2>
    </div>

    @if($penalties->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1" />
        </svg>
        <p class="text-sm">Aucune pénalité enregistrée</p>
    </div>
    @else
    <div class="overflow-x-auto -mx-4 lg:-mx-6">
        <div class="inline-block min-w-full align-middle px-4 lg:px-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Livre</th>
                        <th class="px-6 py-3 text-left">Jours de retard</th>
                        <th class="px-6 py-3 text-left">Montant</th>
                        <th class="px-6 py-3 text-left">Payé</th>
                        <th class="px-6 py-3 text-left">Reste</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($penalties as $penalty)
                    @php
                    $statusColors = [
                    'impayee' => 'bg-red-50 text-red-600',
                    'partiellement_payee'=> 'bg-amber-50 text-amber-600',
                    'payee' => 'bg-green-50 text-green-600',
                    ];
                    $statusLabels = [
                    'impayee' => 'Impayée',
                    'partiellement_payee'=> 'Partiel',
                    'payee' => 'Payée',
                    ];
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-800">{{ $penalty->loan->bookCopy->book->titre ?? '—' }}</p>
                            <p class="text-xs text-gray-400">{{ $penalty->loan->bookCopy->code_exemplaire ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $penalty->jours_retard }} jour(s)</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ number_format($penalty->montant, 0, ',', ' ') }} F</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($penalty->montant_paye, 0, ',', ' ') }} F</td>
                        <td class="px-6 py-4 text-sm font-medium text-red-500">{{ number_format($penalty->reste, 0, ',', ' ') }} F</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium {{ $statusColors[$penalty->statut] ?? 'bg-gray-50 text-gray-600' }}">{{ $statusLabels[$penalty->statut] ?? $penalty->statut }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@endsection