@extends('layouts.app')

@section('title', 'Pénalités — BiblioExcellence')
@section('page-title', 'Gestion des pénalités')
@section('page-subtitle', 'Pénalités et paiements')

@section('content')

{{-- Statistiques --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-red-500">
            {{ number_format($stats['total_impayees'], 0, ',', ' ') }}
        </p>
        <p class="text-xs text-gray-500 mt-1">FCFA impayés</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-green-500">
            {{ number_format($stats['total_payees'], 0, ',', ' ') }}
        </p>
        <p class="text-xs text-gray-500 mt-1">FCFA encaissés</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-amber-500">{{ $stats['nb_impayees'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Pénalités impayées</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-gray-700">{{ $stats['nb_payees'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Pénalités réglées</p>
    </div>
</div>

{{-- Filtres --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('librarian.penalties.index') }}"
        class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Rechercher par nom ou matricule..."
            class="flex-1 min-w-48 px-4 py-2 border border-gray-200 rounded-xl text-sm
                      focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="statut"
            class="px-4 py-2 border border-gray-200 rounded-xl text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Tous les statuts</option>
            <option value="impayee" {{ request('statut') === 'impayee' ? 'selected' : '' }}>
                Impayées
            </option>
            <option value="partiellement_payee"
                {{ request('statut') === 'partiellement_payee' ? 'selected' : '' }}>
                Partiellement payées
            </option>
            <option value="payee" {{ request('statut') === 'payee' ? 'selected' : '' }}>
                Payées
            </option>
        </select>
        <button type="submit"
            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white
                       rounded-xl text-sm font-medium transition">
            Filtrer
        </button>
        @if(request('search') || request('statut'))
        <a href="{{ route('librarian.penalties.index') }}"
            class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600
                      rounded-xl text-sm transition">
            Réinitialiser
        </a>
        @endif
    </form>
</div>

{{-- Liste --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-700">Liste des pénalités</h2>
    </div>

    @if($penalties->isEmpty())
    <div class="text-center py-16 text-gray-400 text-sm">
        Aucune pénalité trouvée
    </div>
    @else
    <table class="w-full">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
            <tr>
                <th class="px-6 py-3 text-left">Utilisateur</th>
                <th class="px-6 py-3 text-left">Livre</th>
                <th class="px-6 py-3 text-left">Retard</th>
                <th class="px-6 py-3 text-left">Montant</th>
                <th class="px-6 py-3 text-left">Payé</th>
                <th class="px-6 py-3 text-left">Restant</th> {{-- NOUVEAU --}}
                <th class="px-6 py-3 text-left">Statut</th>
                <th class="px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($penalties as $penalty)
            @php
            $colors = [
            'impayee' => 'bg-red-50 text-red-600',
            'partiellement_payee'=> 'bg-amber-50 text-amber-600',
            'payee' => 'bg-green-50 text-green-600',
            ];
            $labels = [
            'impayee' => 'Impayée',
            'partiellement_payee'=> 'Partiel',
            'payee' => 'Payée',
            ];
            @endphp
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <p class="text-sm font-medium text-gray-700">
                        {{ $penalty->user->name }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $penalty->user->identifier }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                    <p class="truncate">
                        {{ $penalty->loan->bookCopy->book->titre ?? '—' }}
                    </p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $penalty->jours_retard }} jour(s)
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-700">
                    {{ number_format($penalty->montant, 0, ',', ' ') }} F
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ number_format($penalty->montant_paye, 0, ',', ' ') }} F
                </td>
                <td class="px-6 py-4 text-sm font-medium text-red-500">
                    {{ number_format($penalty->reste, 0, ',', ' ') }} F {{-- Accesseur --}}
                </td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium
                                {{ $colors[$penalty->statut] ?? 'bg-gray-50 text-gray-600' }}">
                        {{ $labels[$penalty->statut] ?? $penalty->statut }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        @if($penalty->reste > 0)
                        <button onclick="openPayModal({{ $penalty->id }}, {{ $penalty->reste }})"
                            class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600
                                                   rounded-lg hover:bg-blue-100 transition">
                            Payer
                        </button>
                        @endif
                        @if($penalty->montant_paye > 0)
                        <a href="{{ route('librarian.penalties.receipt', $penalty) }}"
                            class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600
                                              rounded-lg hover:bg-gray-200 transition">
                            Reçu PDF
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($penalties->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $penalties->links() }}
    </div>
    @endif
    @endif
</div>

{{-- Modal paiement (inchangée) --}}
<div id="payModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4">
        <h3 class="font-semibold text-gray-700 mb-5">Enregistrer un paiement</h3>

        <form id="payForm" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Montant à payer (FCFA) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="montant_paye" id="modalMontant"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p id="modalRestant" class="text-xs text-gray-400 mt-1"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Méthode de paiement <span class="text-red-500">*</span>
                </label>
                <select name="methode"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="especes">Espèces</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="virement">Virement</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Référence (optionnel)
                </label>
                <input type="text" name="reference"
                    placeholder="Numéro de transaction..."
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closePayModal()"
                    class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-600
                               rounded-xl text-sm hover:bg-gray-200 transition">
                    Annuler
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700
                               text-white rounded-xl text-sm font-medium transition">
                    Valider le paiement
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openPayModal(penaltyId, montantRestant) {
        const modal = document.getElementById('payModal');
        const form = document.getElementById('payForm');
        const montant = document.getElementById('modalMontant');
        const restant = document.getElementById('modalRestant');

        form.action = `/librarian/penalties/${penaltyId}/pay`;
        montant.value = montantRestant;
        montant.max = montantRestant;
        restant.textContent = `Montant restant : ${montantRestant.toLocaleString()} FCFA`;

        modal.classList.remove('hidden');
    }

    function closePayModal() {
        document.getElementById('payModal').classList.add('hidden');
    }

    document.getElementById('payModal').addEventListener('click', function(e) {
        if (e.target === this) closePayModal();
    });
</script>
@endpush