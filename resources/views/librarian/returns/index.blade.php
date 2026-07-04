@extends('layouts.app')

@section('title', 'Historique des retours — BiblioExcellence')
@section('page-title', 'Historique des retours')
@section('page-subtitle', 'Consultez et filtrez les retours')

@section('content')

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('librarian.returns.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Date (jour)</label>
            <input type="date" name="date" value="{{ request('date') }}" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Mois</label>
            <input type="month" name="month" value="{{ request('month') }}" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Année</label>
            <input type="number" name="year" value="{{ request('year') }}" placeholder="2026" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 w-24">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Statut</label>
            <select name="statut" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500">
                <option value="">Tous</option>
                <option value="retourne" {{ request('statut') == 'retourne' ? 'selected' : '' }}>Retournés</option>
                <option value="perdu" {{ request('statut') == 'perdu' ? 'selected' : '' }}>Perdus</option>
            </select>
        </div>
        <button type="submit" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium">Filtrer</button>
        <a href="{{ route('librarian.returns.index') }}" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm">Réinitialiser</a>
    </form>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-green-600">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total retours</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-blue-600">{{ $stats['du_jour'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Aujourd'hui</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-amber-500">{{ $stats['du_mois'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Ce mois</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-purple-500">{{ $stats['de_l_annee'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Cette année</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-700">Liste des retours</h2>
    </div>

    @if($returns->isEmpty())
    <div class="text-center py-16 text-gray-400 text-sm">Aucun retour trouvé</div>
    @else
    <div class="overflow-x-auto -mx-4 lg:-mx-6">
        <div class="inline-block min-w-full align-middle px-4 lg:px-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Utilisateur</th>
                        <th class="px-6 py-3 text-left">Livre</th>
                        <th class="px-6 py-3 text-left">Exemplaire</th>
                        <th class="px-6 py-3 text-left">Date retour</th>
                        <th class="px-6 py-3 text-left">Retard</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($returns as $loan)
                    @php
                    $isLate = $loan->date_retour_effective && $loan->date_retour_prevue && $loan->date_retour_effective > $loan->date_retour_prevue;
                    $daysLate = $isLate ? $loan->date_retour_effective->diffInDays($loan->date_retour_prevue) : 0;
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-700">{{ $loan->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $loan->user->identifier }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $loan->bookCopy->book->titre ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $loan->bookCopy->code_exemplaire }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $loan->date_retour_effective?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @if($isLate)
                            <span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded-lg">{{ $daysLate }}j</span>
                            @else
                            <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-lg">OK</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium {{ $loan->statut === 'retourne' ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-600' }}">{{ ucfirst($loan->statut) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($returns->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $returns->links() }}</div>
    @endif
    @endif
</div>

@endsection