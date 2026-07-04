@extends('layouts.app')

@section('title', $book->titre . ' — BiblioExcellence')
@section('page-title', $book->titre)
@section('page-subtitle', $book->author->prenom . ' ' . $book->author->nom)

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Colonne gauche --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
            @if($book->couverture)
            <img src="{{ Storage::url($book->couverture) }}" alt="{{ $book->titre }}" class="w-48 mx-auto rounded-xl shadow-md mb-4">
            @else
            <div class="w-48 h-64 bg-gray-100 rounded-xl mx-auto flex items-center justify-center mb-4">
                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            @endif
            <h2 class="font-bold text-gray-800 text-lg">{{ $book->titre }}</h2>
            <p class="text-sm text-gray-500">{{ $book->author->prenom }} {{ $book->author->nom }}</p>
            <div class="mt-4 flex justify-center gap-3">
                @if($book->quantite_disponible > 0)
                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-sm font-medium">Disponible</span>
                @else
                <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-sm font-medium">Indisponible</span>
                @endif
                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-sm font-medium">{{ $book->quantite_disponible }} exemplaire(s)</span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-2">
            <a href="{{ route('catalogue') }}" class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-sm hover:bg-gray-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour au catalogue
            </a>
            @auth
            @php $user = auth()->user(); @endphp
            @if(in_array($user->role_type, ['student', 'teacher']))
            <form method="POST" action="{{ route($user->role_type . '.reservations.store') }}">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-50 text-amber-600 rounded-xl text-sm hover:bg-amber-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    Réserver ce livre
                </button>
            </form>
            @endif
            @endauth
        </div>
    </div>

    {{-- Colonne droite --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-5">Informations</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Catégorie</p>
                    <p class="text-sm font-medium text-gray-700">{{ $book->category->nom }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">ISBN</p>
                    <p class="text-sm font-medium text-gray-700 font-mono">{{ $book->isbn ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Éditeur</p>
                    <p class="text-sm font-medium text-gray-700">{{ $book->editeur ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Année</p>
                    <p class="text-sm font-medium text-gray-700">{{ $book->annee ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Langue</p>
                    <p class="text-sm font-medium text-gray-700">{{ $book->langue }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Emplacement</p>
                    <p class="text-sm font-medium text-gray-700">{{ $book->emplacement ?? '—' }}</p>
                </div>
            </div>
            @if($book->description)
            <div class="mt-5 pt-5 border-t border-gray-100">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Description</p>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $book->description }}</p>
            </div>
            @endif
        </div>

        {{-- Exemplaires --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-700">Exemplaires disponibles <span class="text-gray-400 font-normal text-sm ml-2">{{ $book->copies->count() }} sur {{ $book->quantite }}</span></h3>
            </div>
            <div class="overflow-x-auto -mx-4 lg:-mx-6">
                <div class="inline-block min-w-full align-middle px-4 lg:px-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Code</th>
                                <th class="px-6 py-3 text-left">État</th>
                                <th class="px-6 py-3 text-left">Disponibilité</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($book->copies as $copy)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm font-mono text-gray-700">{{ $copy->code_exemplaire }}</td>
                                <td class="px-6 py-3">
                                    @php
                                    $etatColors = [
                                    'neuf' => 'bg-green-50 text-green-600',
                                    'bon' => 'bg-blue-50 text-blue-600',
                                    'acceptable' => 'bg-amber-50 text-amber-600',
                                    'abime' => 'bg-red-50 text-red-600',
                                    ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium
                                            {{ $etatColors[$copy->etat] ?? 'bg-gray-50 text-gray-600' }}">
                                        {{ ucfirst($copy->etat) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    @if($copy->disponible)
                                    <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-medium">Disponible</span>
                                    @else
                                    <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-medium">Emprunté</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-400 text-sm">Aucun exemplaire disponible</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection