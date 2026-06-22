@extends('layouts.app')

@section('title', 'Validation des comptes — BiblioExcellence')
@section('page-title', 'Validation des comptes')
@section('page-subtitle', 'Gérez les demandes d\'inscription en attente')

@section('content')

    {{-- Statistiques --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-3xl font-bold text-amber-500">{{ $stats['pending'] }}</p>
            <p class="text-sm text-gray-500 mt-1">En attente</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-3xl font-bold text-green-500">{{ $stats['approved'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Approuvés</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-3xl font-bold text-red-500">{{ $stats['rejected'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Rejetés</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-3xl font-bold text-gray-500">{{ $stats['suspended'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Suspendus</p>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-700">Comptes en attente</h2>
        </div>

        @if($pending->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm">Aucun compte en attente de validation</p>
            </div>
        @else
            <table class="w-full">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Nom</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Rôle</th>
                        <th class="px-6 py-3 text-left">Matricule</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($pending as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-800 text-sm">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role_type === 'student')
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium">Étudiant</span>
                                @elseif($user->role_type === 'teacher')
                                    <span class="px-2.5 py-1 bg-purple-50 text-purple-600 rounded-lg text-xs font-medium">Enseignant</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $user->identifier }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.validations.show', $user) }}"
                                       class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">Détails</a>
                                    <form method="POST" action="{{ route('admin.validations.approve', $user) }}">
                                        @csrf
                                        <button class="text-xs px-3 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 font-medium">Approuver</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.validations.reject', $user) }}">
                                        @csrf
                                        <button class="text-xs px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-medium">Rejeter</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($pending->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $pending->links() }}</div>
            @endif
        @endif
    </div>

@endsection