@extends('layouts.app')

@section('title', 'Mon espace — BiblioExcellence')
@section('page-title', 'Mon espace étudiant')
@section('page-subtitle', 'Bienvenue, ' . auth()->user()->name)

@section('content')

{{-- Statistiques --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['emprunts_actifs'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Emprunts actifs</p>
        <p class="text-xs text-blue-500 mt-0.5">Max 2 livres</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['emprunts_total'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total emprunts</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 {{ $stats['penalites'] > 0 ? 'bg-red-50' : 'bg-gray-50' }} rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 {{ $stats['penalites'] > 0 ? 'text-red-500' : 'text-gray-400' }}"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold {{ $stats['penalites'] > 0 ? 'text-red-500' : 'text-gray-800' }}">
            {{ number_format($stats['penalites'], 0, ',', ' ') }} FCFA
        </p>
        <p class="text-xs text-gray-500 mt-1">Pénalités dues</p>
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

{{-- Emprunts actifs --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-700">Mes emprunts actifs</h3>
    </div>

    @if($emprunts->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
            </svg>
            <p class="text-sm">Vous n'avez aucun emprunt actif</p>
        </div>
    @else
        <table class="w-full">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Livre</th>
                    <th class="px-6 py-3 text-left">Date emprunt</th>
                    <th class="px-6 py-3 text-left">Date retour prévue</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($emprunts as $emprunt)
                    @php
                        $isLate = now()->gt($emprunt->date_retour_prevue);
                        $daysLeft = now()->diffInDays($emprunt->date_retour_prevue, false);
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-700">
                                {{ $emprunt->bookCopy->book->titre ?? '—' }}
                            </p>
                            <p class="text-xs text-gray-400 font-mono">
                                {{ $emprunt->bookCopy->code_exemplaire }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $emprunt->date_emprunt->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="{{ $isLate ? 'text-red-500 font-medium' : 'text-gray-600' }}">
                                {{ $emprunt->date_retour_prevue->format('d/m/Y') }}
                            </span>
                            @if($isLate)
                                <span class="text-xs text-red-400 block">
                                    {{ abs($daysLeft) }} jour(s) de retard
                                </span>
                            @elseif($daysLeft <= 3)
                                <span class="text-xs text-amber-400 block">
                                    {{ $daysLeft }} jour(s) restant(s)
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($isLate)
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-medium">
                                    En retard
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-medium">
                                    En cours
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection