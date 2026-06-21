<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterStudentController;
use App\Http\Controllers\Admin\UserValidationController;
use App\Http\Controllers\Auth\RegisterTeacherController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;

// ============================================================
// Page d'accueil → redirige vers login
// ============================================================
Route::get('/', function () {
    return redirect('/login');
});

// ============================================================
// Routes d'authentification (accès réservé aux invités)
// ============================================================
Route::middleware('guest')->group(function () {
    // Affichage du formulaire de connexion
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    // Traitement du formulaire de connexion
    Route::post('/login', [LoginController::class, 'store']);

    // Inscription étudiant
    Route::get('/register/student', [RegisterStudentController::class, 'create'])
         ->name('register.student');
    Route::post('/register/student', [RegisterStudentController::class, 'store']);

    // Inscription enseignant
    Route::get('/register/teacher', [RegisterTeacherController::class, 'create'])
     ->name('register.teacher');
    Route::post('/register/teacher', [RegisterTeacherController::class, 'store']);
});

// Déconnexion (accessible à tous les utilisateurs authentifiés)
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// ============================================================
// Dashboard Admin
// ============================================================
Route::middleware(['auth', 'check.status'])->prefix('admin')->name('admin.')->group(function () {
    // Page du tableau de bord administrateur
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestion des validations de comptes
    Route::get('/validations', [UserValidationController::class, 'index'])
         ->name('validations.index');
    Route::get('/validations/{user}', [UserValidationController::class, 'show'])
         ->name('validations.show');
    Route::post('/validations/{user}/approve', [UserValidationController::class, 'approve'])
         ->name('validations.approve');
    Route::post('/validations/{user}/reject', [UserValidationController::class, 'reject'])
         ->name('validations.reject');
    Route::post('/validations/{user}/suspend', [UserValidationController::class, 'suspend'])
         ->name('validations.suspend');
});

// ============================================================
// Dashboard Bibliothécaire
// ============================================================
Route::middleware(['auth', 'check.status'])->prefix('librarian')->name('librarian.')->group(function () {
    Route::get('/dashboard', function () {
        return view('librarian.dashboard');
    })->name('dashboard');
});

// ============================================================
// Dashboard Enseignant
// ============================================================
Route::middleware(['auth', 'check.status'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('dashboard');
});

// ============================================================
// Dashboard Étudiant
// ============================================================
Route::middleware(['auth', 'check.status'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');
});

// ============================================================
// Réinitialisation du mot de passe (fourni par Breeze)
// ============================================================
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

// ============================================================
// Routes Admin (redéfinies pour inclure les ressources livres, auteurs, catégories)
// Note : cette section répète partiellement les routes admin ci-dessus.
// ============================================================
Route::middleware(['auth', 'check.status'])->prefix('admin')->name('admin.')->group(function () {

    // Tableau de bord (déjà défini plus haut, mais redéfini ici)
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Validation des comptes (déjà défini plus haut, redéfini ici)
    Route::get('/validations', [UserValidationController::class, 'index'])->name('validations.index');
    Route::get('/validations/{user}', [UserValidationController::class, 'show'])->name('validations.show');
    Route::post('/validations/{user}/approve', [UserValidationController::class, 'approve'])->name('validations.approve');
    Route::post('/validations/{user}/reject', [UserValidationController::class, 'reject'])->name('validations.reject');
    Route::post('/validations/{user}/suspend', [UserValidationController::class, 'suspend'])->name('validations.suspend');

    // Gestion des livres (CRUD complet via resource)
    Route::resource('books', BookController::class);

    // Gestion des auteurs (index, création, suppression)
    Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
    Route::post('/authors', [AuthorController::class, 'store'])->name('authors.store');
    Route::delete('/authors/{author}', [AuthorController::class, 'destroy'])->name('authors.destroy');

    // Gestion des catégories (index, création, suppression)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});