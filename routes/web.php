<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterStudentController;

// Page d'accueil → redirige vers login
Route::get('/', function () {
    return redirect('/login');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Inscription étudiant
    Route::get('/register/student', [RegisterStudentController::class, 'create'])
         ->name('register.student');
    Route::post('/register/student', [RegisterStudentController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Dashboard Admin
Route::middleware(['auth', 'check.status'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// Dashboard Bibliothécaire
Route::middleware(['auth', 'check.status'])->prefix('librarian')->name('librarian.')->group(function () {
    Route::get('/dashboard', function () {
        return view('librarian.dashboard');
    })->name('dashboard');
});

// Dashboard Enseignant
Route::middleware(['auth', 'check.status'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('dashboard');
});

// Dashboard Étudiant
Route::middleware(['auth', 'check.status'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');
});

// Réinitialisation mot de passe (Breeze)
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');