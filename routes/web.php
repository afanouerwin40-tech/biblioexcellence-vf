<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterStudentController;
use App\Http\Controllers\Auth\RegisterTeacherController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserValidationController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LibrarianController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Librarian\DashboardController as LibrarianDashboard;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Route de redirection par défaut
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect('/login'));

/*
|--------------------------------------------------------------------------
| Routes d'authentification (invités)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Connexion
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Inscription étudiant
    Route::get('/register/student', [RegisterStudentController::class, 'create'])->name('register.student');
    Route::post('/register/student', [RegisterStudentController::class, 'store']);

    // Inscription enseignant
    Route::get('/register/teacher', [RegisterTeacherController::class, 'create'])->name('register.teacher');
    Route::post('/register/teacher', [RegisterTeacherController::class, 'store']);

    // Réinitialisation du mot de passe
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Déconnexion
|--------------------------------------------------------------------------
| La méthode destroy du contrôleur gère la vérification de l'authentification.
*/
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

/*
|--------------------------------------------------------------------------
| Routes protégées par authentification + vérification du statut
|--------------------------------------------------------------------------
| Toutes les routes ci-dessous nécessitent que l'utilisateur soit connecté
| et que son compte soit actif/approuvé (middleware 'check.status').
*/
Route::middleware(['auth', 'check.status'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Routes Administrateur
    |--------------------------------------------------------------------------
    | Accessible uniquement aux utilisateurs ayant le rôle 'admin'.
    | Middleware 'role' à créer (ex: vérifie $user->role === 'admin').
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Gestion des validations d'utilisateurs
        Route::get('/validations', [UserValidationController::class, 'index'])->name('validations.index');
        Route::get('/validations/{user}', [UserValidationController::class, 'show'])->name('validations.show');
        Route::post('/validations/{user}/approve', [UserValidationController::class, 'approve'])->name('validations.approve');
        Route::post('/validations/{user}/reject', [UserValidationController::class, 'reject'])->name('validations.reject');
        Route::post('/validations/{user}/suspend', [UserValidationController::class, 'suspend'])->name('validations.suspend');

        // Gestion complète des livres (ressource)
        Route::resource('books', BookController::class);

        // Gestion des auteurs (création, liste, suppression)
        Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
        Route::post('/authors', [AuthorController::class, 'store'])->name('authors.store');
        Route::delete('/authors/{author}', [AuthorController::class, 'destroy'])->name('authors.destroy');

        // Gestion des catégories (création, liste, suppression)
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Gestion des bibliothécaires (création, liste, suppression)
        Route::get('/librarians', [LibrarianController::class, 'index'])->name('librarians.index');
        Route::get('/librarians/create', [LibrarianController::class, 'create'])->name('librarians.create');
        Route::post('/librarians', [LibrarianController::class, 'store'])->name('librarians.store');
        Route::delete('/librarians/{librarian}', [LibrarianController::class, 'destroy'])->name('librarians.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Bibliothécaire
    |--------------------------------------------------------------------------
    | Accessible uniquement aux utilisateurs ayant le rôle 'librarian'.
    */
    Route::middleware('role:librarian')->prefix('librarian')->name('librarian.')->group(function () {
        Route::get('/dashboard', [LibrarianDashboard::class, 'index'])->name('dashboard');
        // Ajoutez ici d'autres routes spécifiques au bibliothécaire
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Enseignant
    |--------------------------------------------------------------------------
    | Accessible uniquement aux utilisateurs ayant le rôle 'teacher'.
    */
    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');
        // Ajoutez ici d'autres routes spécifiques à l'enseignant
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Étudiant
    |--------------------------------------------------------------------------
    | Accessible uniquement aux utilisateurs ayant le rôle 'student'.
    */
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
        // Ajoutez ici d'autres routes spécifiques à l'étudiant
    });

    /*
    |--------------------------------------------------------------------------
    | Routes de Profil (communes à tous les rôles)
    |--------------------------------------------------------------------------
    | Accessibles à tout utilisateur authentifié et actif.
    | Définies une seule fois pour éviter les doublons.
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});