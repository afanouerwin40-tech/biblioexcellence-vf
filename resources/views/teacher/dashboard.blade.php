@extends('layouts.app')

@section('title', 'Mon espace — BiblioExcellence')
@section('page-title', 'Mon espace enseignant')
@section('page-subtitle', 'Bienvenue, ' . auth()->user()->name)

@section('content')

<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['emprunts_actifs'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Emprunts actifs</p>
        <p class="text-xs text-purple-500 mt-0.5">Max 5 livres</p>
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

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-700">Mes emprunts actifs</h3>
    </div>

    @if($emprunts->isEmpty())
        <div class="text-center py-16 text-gray-400 text-sm">Aucun emprunt actif</div>
    @else
        <table class="w-full">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Livre</th>
                    <th class="px-6 py-3 text-left">Date emprunt</th>
                    <th class="px-6 py-3 text-left">Retour prévu</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($emprunts as $emprunt)
                    @php $isLate = now()->gt($emprunt->date_retour_prevue); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-700">
                            {{ $emprunt->bookCopy->book->titre ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $emprunt->date_emprunt->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm {{ $isLate ? 'text-red-500 font-medium' : 'text-gray-600' }}">
                            {{ $emprunt->date_retour_prevue->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium
                                {{ $isLate ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                                {{ $isLate ? 'En retard' : 'En cours' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection