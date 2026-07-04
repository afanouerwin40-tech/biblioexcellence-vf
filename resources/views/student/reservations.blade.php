@extends('layouts.app')

@section('title', 'Mes réservations — BiblioExcellence')
@section('page-title', 'Mes réservations')
@section('page-subtitle', 'Livres en attente de disponibilité')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-700">Réservations actives</h2>
    </div>

    @if($reservations->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
        </svg>
        <p class="text-sm">Aucune réservation active</p>
        <a href="{{ route('catalogue') }}" class="text-blue-500 text-sm hover:underline mt-2 inline-block">Parcourir le catalogue</a>
    </div>
    @else
    <div class="divide-y divide-gray-50">
        @foreach($reservations as $reservation)
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if($reservation->book->couverture)
                    <img src="{{ Storage::url($reservation->book->couverture) }}" class="w-full h-full object-cover">
                    @else
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13" />
                    </svg>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $reservation->book->titre }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $reservation->book->author->prenom ?? '' }} {{ $reservation->book->author->nom ?? '' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Réservé le {{ $reservation->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
            <div class="text-right flex items-center gap-3">
                @if($reservation->statut === 'disponible')
                <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-medium">Disponible !</span>
                @else
                <span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-medium">Position {{ $reservation->position_file }}</span>
                @endif
                <form method="POST" action="{{ route('student.reservations.destroy', $reservation) }}">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Annuler cette réservation ?')" class="text-xs px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">Annuler</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection