@extends('layouts.app')

@section('title', 'Dashboard Admin — BiblioExcellence')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de la bibliothèque')

@section('content')

    {{-- Statistiques --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-xs text-green-500 font-medium bg-green-50 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_books'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Livres</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5z"/>
                    </svg>
                </div>
                <span class="text-xs text-purple-500 font-medium bg-purple-50 px-2 py-1 rounded-lg">Inscrits</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_students'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Étudiants</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857"/>
                    </svg>
                </div>
                <span class="text-xs text-amber-500 font-medium bg-amber-50 px-2 py-1 rounded-lg">En attente</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_accounts'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Comptes à valider</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-green-500 font-medium bg-green-50 px-2 py-1 rounded-lg">Dispo.</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['available_books'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Livres disponibles</p>
        </div>

    </div>

    {{-- Raccourcis --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <a href="{{ route('admin.validations.index') }}"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-100 transition">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                             M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857
                             m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800">Validation des comptes</h3>
            <p class="text-sm text-gray-500 mt-1">
                <span class="text-amber-500 font-medium">{{ $stats['pending_accounts'] }}</span> en attente
            </p>
        </a>

        <a href="{{ route('admin.books.index') }}"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-100 transition">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                             C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                             C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                             C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800">Catalogue des livres</h3>
            <p class="text-sm text-gray-500 mt-1">
                <span class="text-blue-500 font-medium">{{ $stats['total_books'] }}</span> livres enregistrés
            </p>
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-100 transition">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800">Catégories</h3>
            <p class="text-sm text-gray-500 mt-1">Organiser le catalogue</p>
        </a>

    </div>

@endsection