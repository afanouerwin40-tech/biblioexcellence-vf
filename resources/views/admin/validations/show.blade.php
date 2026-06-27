@extends('layouts.app')

@section('title', 'Détail compte — BiblioExcellence')
@section('page-title', 'Détail du compte')
@section('page-subtitle', $user->name)

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.validations.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">← Retour à la liste</a>
        <div class="flex gap-3">
            @if($user->status === 'pending')
                <form method="POST" action="{{ route('admin.validations.approve', $user) }}">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white
                                   rounded-xl text-sm font-medium transition">
                        Approuver
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.validations.reject', $user) }}">
                    @csrf
                    <button class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600
                                   rounded-xl text-sm font-medium transition">
                        Rejeter
                    </button>
                </form>
            @elseif($user->status === 'approved')
                <form method="POST" action="{{ route('admin.validations.suspend', $user) }}">
                    @csrf
                    <button class="px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-600
                                   rounded-xl text-sm font-medium transition">
                        Suspendre
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Informations compte --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-5 mb-6">
            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-2xl font-bold text-white">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <div class="flex items-center gap-2 mt-2">
                    @php
                        $statusColors = [
                            'pending'   => 'bg-amber-50 text-amber-600',
                            'approved'  => 'bg-green-50 text-green-600',
                            'rejected'  => 'bg-red-50 text-red-600',
                            'suspended' => 'bg-gray-50 text-gray-600',
                        ];
                        $statusLabels = [
                            'pending'   => 'En attente',
                            'approved'  => 'Approuvé',
                            'rejected'  => 'Rejeté',
                            'suspended' => 'Suspendu',
                        ];
                    @endphp
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium
                        {{ $statusColors[$user->status] ?? 'bg-gray-50 text-gray-600' }}">
                        {{ $statusLabels[$user->status] ?? $user->status }}
                    </span>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium
                        {{ $user->role_type === 'student' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                        {{ $user->role_type === 'student' ? 'Étudiant' : 'Enseignant' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Identifiant</p>
                <p class="text-sm font-medium text-gray-700 font-mono">{{ $user->identifier }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Inscrit le</p>
                <p class="text-sm font-medium text-gray-700">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Profil étudiant --}}
    @if($user->students->isNotEmpty())
        @php $student = $user->students->first(); @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-5">Informations académiques</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Nom complet</p>
                    <p class="text-sm font-medium text-gray-700">{{ $student->prenom }} {{ $student->nom }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Matricule</p>
                    <p class="text-sm font-medium text-gray-700 font-mono">{{ $student->matricule }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Département</p>
                    <p class="text-sm font-medium text-gray-700">
                        {{ $student->department->nom ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Faculté</p>
                    <p class="text-sm font-medium text-gray-700">
                        {{ $student->department->faculty->nom ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Niveau</p>
                    <p class="text-sm font-medium text-gray-700">{{ $student->niveau }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Année académique</p>
                    <p class="text-sm font-medium text-gray-700">{{ $student->annee_academique }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Sexe</p>
                    <p class="text-sm font-medium text-gray-700">
                        {{ $student->sexe === 'M' ? 'Masculin' : 'Féminin' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Date de naissance</p>
                    <p class="text-sm font-medium text-gray-700">
                        {{ $student->date_naissance?->format('d/m/Y') ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Nationalité</p>
                    <p class="text-sm font-medium text-gray-700">{{ $student->nationalite }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Téléphone</p>
                    <p class="text-sm font-medium text-gray-700">{{ $student->telephone ?? '—' }}</p>
                </div>
            </div>

            {{-- Documents --}}
            @if($student->carte_etudiante)
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-3">Carte étudiante</p>
                    @php $ext = pathinfo($student->carte_etudiante, PATHINFO_EXTENSION); @endphp
                    @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                        <img src="{{ Storage::url($student->carte_etudiante) }}"
                             class="max-w-sm rounded-xl shadow-sm border border-gray-100">
                    @else
                        <a href="{{ Storage::url($student->carte_etudiante) }}"
                           target="_blank"
                           class="flex items-center gap-2 text-sm text-blue-600 hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Télécharger la carte (PDF)
                        </a>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- Profil enseignant --}}
    @if($user->teachers->isNotEmpty())
        @php $teacher = $user->teachers->first(); @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-5">Informations professionnelles</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Nom complet</p>
                    <p class="text-sm font-medium text-gray-700">{{ $teacher->prenom }} {{ $teacher->nom }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Matricule</p>
                    <p class="text-sm font-medium text-gray-700 font-mono">{{ $teacher->matricule_pro }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Grade</p>
                    <p class="text-sm font-medium text-gray-700">{{ $teacher->grade }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Spécialité</p>
                    <p class="text-sm font-medium text-gray-700">{{ $teacher->specialite ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Département</p>
                    <p class="text-sm font-medium text-gray-700">
                        {{ $teacher->department->nom ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Téléphone</p>
                    <p class="text-sm font-medium text-gray-700">{{ $teacher->telephone ?? '—' }}</p>
                </div>
            </div>

            @if($teacher->carte_professionnelle)
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-3">Carte professionnelle</p>
                    @php $ext = pathinfo($teacher->carte_professionnelle, PATHINFO_EXTENSION); @endphp
                    @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                        <img src="{{ Storage::url($teacher->carte_professionnelle) }}"
                             class="max-w-sm rounded-xl shadow-sm border border-gray-100">
                    @else
                        <a href="{{ Storage::url($teacher->carte_professionnelle) }}"
                           target="_blank"
                           class="flex items-center gap-2 text-sm text-blue-600 hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Télécharger la carte (PDF)
                        </a>
                    @endif
                </div>
            @endif
        </div>
    @endif

</div>

@endsection