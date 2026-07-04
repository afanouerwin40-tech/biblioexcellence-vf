@extends('layouts.app')

@section('title', 'Mes emprunts — BiblioExcellence')
@section('page-title', 'Mes emprunts')
@section('page-subtitle', 'Historique complet de vos emprunts')

@section('content')

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-700">Historique des emprunts</h2>
    </div>

    @if($loans->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13" />
        </svg>
        <p class="text-sm">Aucun emprunt enregistré</p>
    </div>
    @else
    <div class="overflow-x-auto -mx-4 lg:-mx-6">
        <div class="inline-block min-w-full align-middle px-4 lg:px-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Livre</th>
                        <th class="px-6 py-3 text-left">Exemplaire</th>
                        <th class="px-6 py-3 text-left">Emprunté le</th>
                        <th class="px-6 py-3 text-left">Retour prévu</th>
                        <th class="px-6 py-3 text-left">Retourné le</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($loans as $loan)
                    @php
                    $colors = [
                    'actif' => 'bg-blue-50 text-blue-600',
                    'en_retard' => 'bg-red-50 text-red-600',
                    'renouvele' => 'bg-amber-50 text-amber-600',
                    'retourne' => 'bg-green-50 text-green-600',
                    'perdu' => 'bg-gray-50 text-gray-600',
                    ];
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $loan->bookCopy->book->titre ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $loan->bookCopy->code_exemplaire }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $loan->date_emprunt->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $loan->date_retour_prevue->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $loan->date_retour_effective?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium {{ $colors[$loan->statut] ?? 'bg-gray-50 text-gray-600' }}">{{ ucfirst(str_replace('_', ' ', $loan->statut)) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($loans->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $loans->links() }}</div>
    @endif
    @endif
</div>

@endsection