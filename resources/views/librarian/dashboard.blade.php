@extends('layouts.app')

@section('title', 'Dashboard Bibliothécaire — BiblioExcellence')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Activité de la bibliothèque')

@section('content')

<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['emprunts_jour'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Emprunts aujourd'hui</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['retours_jour'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Retours aujourd'hui</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-red-500">{{ $stats['en_retard'] }}</p>
        <p class="text-xs text-gray-500 mt-1">En retard</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-amber-500">
            {{ number_format($stats['penalites_dues'], 0, ',', ' ') }}
        </p>
        <p class="text-xs text-gray-500 mt-1">FCFA pénalités</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['nouveaux_inscrits'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Inscrits aujourd'hui</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['livres_dispo'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Livres disponibles</p>
    </div>

</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-700">Emprunts récents</h3>
    </div>

    @if($emprunts_recents->isEmpty())
        <div class="text-center py-16 text-gray-400 text-sm">Aucun emprunt enregistré</div>
    @else
        <table class="w-full">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Utilisateur</th>
                    <th class="px-6 py-3 text-left">Livre</th>
                    <th class="px-6 py-3 text-left">Date emprunt</th>
                    <th class="px-6 py-3 text-left">Retour prévu</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($emprunts_recents as $emprunt)
                    @php
                        $colors = [
                            'actif'     => 'bg-blue-50 text-blue-600',
                            'retourne'  => 'bg-green-50 text-green-600',
                            'en_retard' => 'bg-red-50 text-red-600',
                            'renouvele' => 'bg-amber-50 text-amber-600',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-700">
                            {{ $emprunt->user->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $emprunt->bookCopy->book->titre ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $emprunt->date_emprunt->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $emprunt->date_retour_prevue->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium
                                {{ $colors[$emprunt->statut] ?? 'bg-gray-50 text-gray-600' }}">
                                {{ ucfirst(str_replace('_', ' ', $emprunt->statut)) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection