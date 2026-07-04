@extends('layouts.app')

@section('title', 'Rapports — BiblioExcellence')
@section('page-title', 'Rapports')
@section('page-subtitle', 'Exports et statistiques')

@section('content')

{{-- Statistiques --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_loans'] ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1">Total emprunts</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-green-600">{{ $stats['active_loans'] ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1">Emprunts actifs</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-red-500">{{ $stats['overdue_loans'] ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1">En retard</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-amber-500">
            {{ number_format($stats['total_penalties'] ?? 0, 0, ',', ' ') }} F
        </p>
        <p class="text-xs text-gray-500 mt-1">Total pénalités</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-green-500">
            {{ number_format($stats['paid_penalties'] ?? 0, 0, ',', ' ') }} F
        </p>
        <p class="text-xs text-gray-500 mt-1">Pénalités encaissées</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-purple-600">{{ $stats['total_users'] ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1">Utilisateurs</p>
    </div>
</div>

{{-- Exports --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Rapport Emprunts --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                             01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-700">Rapport des emprunts</h3>
                <p class="text-xs text-gray-400">Tous les emprunts enregistrés</p>
            </div>
        </div>

        {{-- Filtres --}}
        <form method="GET" action="{{ route('admin.reports.loans.pdf') }}"
            target="_blank" class="space-y-3 mb-4">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Du</label>
                    <input type="date" name="from"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Au</label>
                    <input type="date" name="to"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <select name="statut"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les statuts</option>
                <option value="actif">Actifs</option>
                <option value="retourne">Retournés</option>
                <option value="en_retard">En retard</option>
            </select>
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-2.5 bg-red-50
                           hover:bg-red-100 text-red-600 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586" />
                </svg>
                Exporter PDF
            </button>
        </form>

        <a href="{{ route('admin.reports.loans.excel') }}"
            class="flex items-center justify-center gap-2 py-2.5 bg-green-50
                  hover:bg-green-100 text-green-600 rounded-xl text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586" />
            </svg>
            Exporter Excel
        </a>
    </div>

    {{-- Rapport Pénalités --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2" />
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-700">Rapport des pénalités</h3>
                <p class="text-xs text-gray-400">Toutes les pénalités enregistrées</p>
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('admin.reports.penalties.pdf') }}" target="_blank"
                class="flex items-center justify-center gap-2 py-2.5 bg-red-50
                      hover:bg-red-100 text-red-600 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586" />
                </svg>
                Exporter PDF
            </a>
            <a href="{{ route('admin.reports.penalties.excel') }}"
                class="flex items-center justify-center gap-2 py-2.5 bg-green-50
                      hover:bg-green-100 text-green-600 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586" />
                </svg>
                Exporter Excel
            </a>
        </div>
    </div>

</div>

@endsection