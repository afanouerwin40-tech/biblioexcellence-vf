@extends('layouts.app')

@section('title', 'Mon espace — BiblioExcellence')
@section('page-title', 'Mon espace')
@section('page-subtitle', 'Bienvenue, ' . auth()->user()->name)

@section('content')

{{-- Statistiques --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                </svg>
            </div>
            <span class="text-xs font-medium px-2 py-1 rounded-lg bg-blue-50 text-blue-600">
                Max 2
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['emprunts_actifs'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Emprunts actifs</p>
        <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
            <div class="bg-blue-500 h-1.5 rounded-full"
                 style="width: {{ min(($stats['emprunts_actifs'] / 2) * 100, 100) }}%"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['emprunts_total'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total emprunts</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 {{ $stats['penalites'] > 0 ? 'bg-red-50' : 'bg-gray-50' }}
                        rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 {{ $stats['penalites'] > 0 ? 'text-red-500' : 'text-gray-400' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            @if($stats['penalites'] > 0)
                <span class="text-xs font-medium px-2 py-1 rounded-lg bg-red-50 text-red-600">
                    À payer
                </span>
            @endif
        </div>
        <p class="text-2xl font-bold {{ $stats['penalites'] > 0 ? 'text-red-500' : 'text-gray-800' }}">
            {{ number_format($stats['penalites'], 0, ',', ' ') }} F
        </p>
        <p class="text-xs text-gray-500 mt-1">Pénalités dues</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['reservations'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Réservations actives</p>
    </div>

</div>

{{-- Alerte pénalités --}}
@if($stats['penalites'] > 0)
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700
                rounded-xl px-5 py-4 mb-6 text-sm">
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="font-medium">Pénalités impayées</p>
            <p class="mt-0.5">Vous avez <strong>{{ number_format($stats['penalites'], 0, ',', ' ') }} FCFA</strong>
               de pénalités impayées. Contactez la bibliothèque pour régulariser votre situation.
               Les nouveaux emprunts sont bloqués tant que les pénalités ne sont pas réglées.</p>
        </div>
    </div>
@endif

{{-- Emprunts actifs --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-700">Mes emprunts en cours</h3>
        <span class="text-xs text-gray-400">{{ $stats['emprunts_actifs'] }}/2 livres</span>
    </div>

    @if($emprunts->isEmpty())
        <div class="text-center py-12">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
            </svg>
            <p class="text-sm text-gray-400">Aucun emprunt en cours</p>
            <p class="text-xs text-gray-400 mt-1">Rendez-vous à la bibliothèque pour emprunter un livre</p>
        </div>
    @else
        <div class="divide-y divide-gray-50">
            @foreach($emprunts as $emprunt)
                @php
                    $isLate   = now()->gt($emprunt->date_retour_prevue);
                    $daysLeft = now()->diffInDays($emprunt->date_retour_prevue, false);
                    $isUrgent = !$isLate && $daysLeft <= 3;
                @endphp
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-14 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            @if($emprunt->bookCopy->book->couverture ?? false)
                                <img src="{{ Storage::url($emprunt->bookCopy->book->couverture) }}"
                                     class="w-10 h-14 object-cover rounded-lg">
                            @else
                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $emprunt->bookCopy->book->titre ?? '—' }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Emprunté le {{ $emprunt->date_emprunt->format('d/m/Y') }}
                            </p>
                            <p class="text-xs font-mono text-gray-400">
                                {{ $emprunt->bookCopy->code_exemplaire ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs text-gray-500 mb-1">Retour prévu</p>
                        <p class="text-sm font-medium {{ $isLate ? 'text-red-500' : ($isUrgent ? 'text-amber-500' : 'text-gray-700') }}">
                            {{ $emprunt->date_retour_prevue->format('d/m/Y') }}
                        </p>
                        @if($isLate)
                            <span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded-lg mt-1 inline-block">
                                {{ round(abs($daysLeft)) }}j de retard
                            </span>
                        @elseif($isUrgent)
                            <span class="text-xs bg-amber-50 text-amber-600 px-2 py-0.5 rounded-lg mt-1 inline-block">
                                {{ round($daysLeft) }}j restant(s)
                            </span>
                        @else
                            <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-lg mt-1 inline-block">
                                {{ round($daysLeft) }}j restant(s)
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Historique emprunts --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-700">Historique des emprunts</h3>
    </div>

    @if($historique->isEmpty())
        <div class="text-center py-12 text-gray-400 text-sm">
            Aucun historique disponible
        </div>
    @else
        <table class="w-full">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Livre</th>
                    <th class="px-6 py-3 text-left">Emprunté le</th>
                    <th class="px-6 py-3 text-left">Retourné le</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($historique as $h)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm font-medium text-gray-700">
                            {{ $h->bookCopy->book->titre ?? '—' }}
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500">
                            {{ $h->date_emprunt->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500">
                            {{ $h->date_retour_effective ? $h->date_retour_effective->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $colors = [
                                    'retourne'  => 'bg-green-50 text-green-600',
                                    'en_retard' => 'bg-red-50 text-red-600',
                                    'actif'     => 'bg-blue-50 text-blue-600',
                                    'perdu'     => 'bg-gray-50 text-gray-600',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium
                                {{ $colors[$h->statut] ?? 'bg-gray-50 text-gray-600' }}">
                                {{ ucfirst(str_replace('_', ' ', $h->statut)) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection