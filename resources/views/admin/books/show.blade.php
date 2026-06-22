@extends('layouts.app')

@section('title', $book->titre . ' — BiblioExcellence')
@section('page-title', 'Fiche livre')
@section('page-subtitle', $book->titre)

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Colonne gauche : couverture + QR code --}}
    <div class="lg:col-span-1 space-y-4">

        {{-- Couverture --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
            @if($book->couverture)
                <img src="{{ Storage::url($book->couverture) }}"
                     alt="{{ $book->titre }}"
                     class="w-40 mx-auto rounded-xl shadow-md mb-4">
            @else
                <div class="w-40 h-56 bg-gray-100 rounded-xl mx-auto flex items-center
                            justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                                 C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                    </svg>
                </div>
            @endif
            <h2 class="font-bold text-gray-800 text-lg leading-tight">{{ $book->titre }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $book->author->prenom }} {{ $book->author->nom }}</p>
        </div>

        {{-- QR Code --}}
        @if($book->qrcode_path)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
                <h3 class="font-semibold text-gray-700 mb-4">QR Code</h3>
                <img src="{{ Storage::url($book->qrcode_path) }}"
                     alt="QR Code {{ $book->titre }}"
                     class="w-40 mx-auto">
                <p class="text-xs text-gray-400 mt-3">Scanner pour accéder à la fiche</p>
            </div>
        @endif

        {{-- Actions --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-2">
            <a href="{{ route('admin.books.edit', $book) }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-blue-50 text-blue-600 rounded-xl
                      text-sm font-medium hover:bg-blue-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0
                             112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Modifier ce livre
            </a>
            <form method="POST" action="{{ route('admin.books.destroy', $book) }}">
                @csrf @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Supprimer ce livre définitivement ?')"
                        class="w-full flex items-center gap-2 px-4 py-2.5 bg-red-50 text-red-600
                               rounded-xl text-sm font-medium hover:bg-red-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5
                                 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Supprimer ce livre
                </button>
            </form>
            <a href="{{ route('admin.books.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl
                      text-sm hover:bg-gray-100 transition">
                ← Retour au catalogue
            </a>
        </div>

    </div>

    {{-- Colonne droite : informations + exemplaires --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Informations --}}
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
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Stock total</p>
                    <p class="text-sm font-medium text-gray-700">{{ $book->quantite }} exemplaire(s)</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Disponibles</p>
                    @if($book->isAvailable())
                        <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-medium">
                            {{ $book->quantite_disponible }} disponible(s)
                        </span>
                    @else
                        <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-medium">
                            Épuisé
                        </span>
                    @endif
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
                <h3 class="font-semibold text-gray-700">
                    Exemplaires
                    <span class="text-gray-400 font-normal text-sm ml-2">
                        {{ $book->copies->count() }} au total
                    </span>
                </h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Code</th>
                        <th class="px-6 py-3 text-left">État</th>
                        <th class="px-6 py-3 text-left">Disponibilité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($book->copies as $copy)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-sm font-mono text-gray-700">
                                {{ $copy->code_exemplaire }}
                            </td>
                            <td class="px-6 py-3">
                                @php
                                    $etatColors = [
                                        'neuf'       => 'bg-green-50 text-green-600',
                                        'bon'        => 'bg-blue-50 text-blue-600',
                                        'acceptable' => 'bg-amber-50 text-amber-600',
                                        'abime'      => 'bg-red-50 text-red-600',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium
                                             {{ $etatColors[$copy->etat] ?? 'bg-gray-50 text-gray-600' }}">
                                    {{ ucfirst($copy->etat) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                @if($copy->disponible)
                                    <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-medium">
                                        Disponible
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-medium">
                                        Emprunté
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection