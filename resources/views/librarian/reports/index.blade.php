@extends('layouts.app')

@section('title', 'Rapports — BiblioExcellence')
@section('page-title', 'Rapports et statistiques')
@section('page-subtitle', 'Vue d\'ensemble pour le bibliothécaire')

@section('content')

{{-- Statistiques globales --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_emprunts'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total emprunts</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-green-600">{{ $stats['emprunts_en_cours'] }}</p>
        <p class="text-xs text-gray-500 mt-1">En cours</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-red-500">{{ $stats['retards'] }}</p>
        <p class="text-xs text-gray-500 mt-1">En retard</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-2xl font-bold text-amber-500">{{ number_format($stats['total_penalites'], 0, ',', ' ') }} F</p>
        <p class="text-xs text-gray-500 mt-1">Pénalités totales</p>
    </div>
</div>

{{-- Graphiques --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Emprunts par mois</h3>
        <canvas id="loansChart" height="150"></canvas>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Pénalités par mois</h3>
        <canvas id="penaltiesChart" height="150"></canvas>
    </div>
</div>

{{-- Exports --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Exporter les emprunts</h3>
        <div class="space-y-3">
            <a href="{{ route('librarian.reports.loans.pdf') }}" target="_blank"
                class="flex items-center justify-center gap-2 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586" />
                </svg>
                PDF
            </a>
            <a href="{{ route('librarian.reports.loans.excel') }}"
                class="flex items-center justify-center gap-2 py-2.5 bg-green-50 hover:bg-green-100 text-green-600 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586" />
                </svg>
                Excel
            </a>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Exporter les pénalités</h3>
        <div class="space-y-3">
            <a href="{{ route('librarian.reports.penalties.pdf') }}" target="_blank"
                class="flex items-center justify-center gap-2 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586" />
                </svg>
                PDF
            </a>
            <a href="{{ route('librarian.reports.penalties.excel') }}"
                class="flex items-center justify-center gap-2 py-2.5 bg-green-50 hover:bg-green-100 text-green-600 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586" />
                </svg>
                Excel
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('loansChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Emprunts',
                data: @json($chartLoans),
                backgroundColor: '#2563eb',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    new Chart(document.getElementById('penaltiesChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Pénalités (FCFA)',
                data: @json($chartPenalties),
                backgroundColor: '#f59e0b',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush