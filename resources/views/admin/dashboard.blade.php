@extends('layouts.app')

@section('title', 'Dashboard — BiblioExcellence')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de la bibliothèque')

@section('content')

{{-- Statistiques principales --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_books'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Livres</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['available_books'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Disponibles</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['borrowed_books'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Épuisés</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 14l9-5-9-5-9 5 9 5z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_students'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Étudiants</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_teachers'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Enseignants</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_accounts'] }}</p>
        <p class="text-xs text-gray-500 mt-1">En attente</p>
    </div>

</div>

{{-- Graphiques --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Graphique inscriptions --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-semibold text-gray-700">Inscriptions</h3>
                <p class="text-xs text-gray-400 mt-0.5">6 derniers mois</p>
            </div>
        </div>
        <canvas id="registrationsChart" height="120"></canvas>
    </div>

    {{-- Graphique répartition comptes --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="mb-6">
            <h3 class="font-semibold text-gray-700">Statuts des comptes</h3>
            <p class="text-xs text-gray-400 mt-0.5">Répartition actuelle</p>
        </div>
        <canvas id="accountsChart" height="180"></canvas>
        <div class="mt-4 space-y-2">
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    <span class="text-gray-600">Approuvés</span>
                </div>
                <span class="font-medium text-gray-700">{{ $accountStats['approved'] }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                    <span class="text-gray-600">En attente</span>
                </div>
                <span class="font-medium text-gray-700">{{ $accountStats['pending'] }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <span class="text-gray-600">Rejetés</span>
                </div>
                <span class="font-medium text-gray-700">{{ $accountStats['rejected'] }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                    <span class="text-gray-600">Suspendus</span>
                </div>
                <span class="font-medium text-gray-700">{{ $accountStats['suspended'] }}</span>
            </div>
        </div>
    </div>

</div>

{{-- Livres par catégorie + Comptes en attente --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Livres par catégorie --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-6">Livres par catégorie</h3>
        <canvas id="categoriesChart" height="200"></canvas>
    </div>

    {{-- Derniers comptes en attente --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-700">Dernières inscriptions</h3>
            <a href="{{ route('admin.validations.index') }}"
               class="text-xs text-blue-600 hover:underline">Voir tout</a>
        </div>

        @if($recentUsers->isEmpty())
            <div class="text-center py-12 text-gray-400 text-sm">
                Aucun compte en attente
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold text-blue-600">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->identifier }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2.5 py-1 rounded-lg
                                {{ $user->role_type === 'student' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                                {{ $user->role_type === 'student' ? 'Étudiant' : 'Enseignant' }}
                            </span>
                            <form method="POST" action="{{ route('admin.validations.approve', $user) }}">
                                @csrf
                                <button class="text-xs px-2.5 py-1 bg-green-50 text-green-600
                                               rounded-lg hover:bg-green-100 transition font-medium">
                                    Approuver
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique inscriptions
    new Chart(document.getElementById('registrationsChart'), {
        type: 'line',
        data: {
            labels: @json($registrationLabels),
            datasets: [{
                label: 'Inscriptions',
                data: @json($registrationData),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#2563eb',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Graphique statuts comptes
    new Chart(document.getElementById('accountsChart'), {
        type: 'doughnut',
        data: {
            labels: ['Approuvés', 'En attente', 'Rejetés', 'Suspendus'],
            datasets: [{
                data: [
                    {{ $accountStats['approved'] }},
                    {{ $accountStats['pending'] }},
                    {{ $accountStats['rejected'] }},
                    {{ $accountStats['suspended'] }}
                ],
                backgroundColor: ['#4ade80', '#fbbf24', '#f87171', '#9ca3af'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });

    // Graphique livres par catégorie
    new Chart(document.getElementById('categoriesChart'), {
        type: 'bar',
        data: {
            labels: @json($booksByCategory->pluck('nom')),
            datasets: [{
                label: 'Livres',
                data: @json($booksByCategory->pluck('books_count')),
                backgroundColor: [
                    'rgba(37,99,235,0.8)',
                    'rgba(124,58,237,0.8)',
                    'rgba(16,185,129,0.8)',
                    'rgba(245,158,11,0.8)',
                    'rgba(239,68,68,0.8)',
                    'rgba(107,114,128,0.8)',
                ],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush