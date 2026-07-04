@extends('layouts.app')

@section('title', 'Mon espace — BiblioExcellence')
@section('page-title', 'Mon espace enseignant')
@section('page-subtitle', 'Bienvenue, ' . auth()->user()->name)

@section('content')

<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                </svg>
            </div>
            <span class="text-xs font-medium px-2 py-1 rounded-lg bg-purple-50 text-purple-600">
                Max 5
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['emprunts_actifs'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Emprunts actifs</p>
        <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
            <div class="bg-purple-500 h-1.5 rounded-full"
                 style="width: {{ min(($stats['emprunts_actifs'] / 5) * 100, 100) }}%"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['emprunts_total'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total emprunts</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['reservations'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Réservations</p>
    </div>

</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-700">Mes emprunts en cours</h3>
        <span class="text-xs text-gray-400">{{ $stats['emprunts_actifs'] }}/5 livres</span>
    </div>

    @if($emprunts->isEmpty())
        <div class="text-center py-12">
            <p class="text-sm text-gray-400">Aucun emprunt en cours</p>
        </div>
    @else
        <div class="divide-y divide-gray-50">
            @foreach($emprunts as $emprunt)
                @php
                    $isLate   = now()->gt($emprunt->date_retour_prevue);
                    $daysLeft = now()->diffInDays($emprunt->date_retour_prevue, false);
                @endphp
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $emprunt->bookCopy->book->titre ?? '—' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Emprunté le {{ $emprunt->date_emprunt->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium {{ $isLate ? 'text-red-500' : 'text-gray-700' }}">
                            {{ $emprunt->date_retour_prevue->format('d/m/Y') }}
                        </p>
                        @if($isLate)
                            <span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded-lg">
                                {{ abs($daysLeft) }}j de retard
                            </span>
                        @else
                            <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-lg">
                                {{ $daysLeft }}j restant(s)
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-700">Historique des emprunts</h3>
    </div>
    @if($historique->isEmpty())
        <div class="text-center py-12 text-gray-400 text-sm">Aucun historique</div>
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
                            {{ $h->date_retour_effective?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-green-50 text-green-600">
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