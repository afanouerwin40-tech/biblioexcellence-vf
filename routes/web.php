<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterStudentController;
use App\Http\Controllers\Auth\RegisterTeacherController;
use App\Http\Controllers\Auth\PendingApprovalController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Librarian\PenaltyController;
use App\Http\Controllers\Librarian\LoanController;
use App\Http\Controllers\Librarian\ReturnController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserValidationController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LibrarianController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Librarian\DashboardController as LibrarianDashboard;
use App\Http\Controllers\Teacher\ReservationController as TeacherReservation;
use App\Http\Controllers\Student\ReservationController as StudentReservation;
use App\Http\Controllers\CatalogueController;      // <-- Ajout
use App\Http\Controllers\NotificationController;   // <-- Ajout

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect('/login'));

/*
|--------------------------------------------------------------------------
| Routes d'authentification (invité)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register/student', [RegisterStudentController::class, 'create'])->name('register.student');
    Route::post('/register/student', [RegisterStudentController::class, 'store']);

    Route::get('/register/teacher', [RegisterTeacherController::class, 'create'])->name('register.teacher');
    Route::post('/register/teacher', [RegisterTeacherController::class, 'store']);

    // Page d'attente affichée juste après une inscription (étudiant ou enseignant),
    // tant que l'admin n'a pas validé le compte.
    Route::get('/inscription/en-attente', [PendingApprovalController::class, 'show'])->name('pending.approval');

    Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Déconnexion
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

/*
|--------------------------------------------------------------------------
| Routes protégées (authentification + statut actif)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.status'])->group(function () {

    // ---- Routes communes à tous les utilisateurs connectés ----
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/catalogue', fn() => view('catalogue'))->name('catalogue');
    Route::get('/catalogue/{book}', [CatalogueController::class, 'show'])->name('catalogue.show');   // <-- Nouvelle route

    // ---- Notifications (AJOUT) ----
    Route::get('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');

    // ---- Administrateur ----
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::get('/validations', [UserValidationController::class, 'index'])->name('validations.index');
        Route::get('/validations/{user}', [UserValidationController::class, 'show'])->name('validations.show');
        Route::post('/validations/{user}/approve', [UserValidationController::class, 'approve'])->name('validations.approve');
        Route::post('/validations/{user}/reject', [UserValidationController::class, 'reject'])->name('validations.reject');
        Route::post('/validations/{user}/suspend', [UserValidationController::class, 'suspend'])->name('validations.suspend');

        Route::resource('books', BookController::class);

        Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
        Route::post('/authors', [AuthorController::class, 'store'])->name('authors.store');
        Route::delete('/authors/{author}', [AuthorController::class, 'destroy'])->name('authors.destroy');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Routes pour les bibliothécaires (CRUD complet)
        Route::get('/librarians', [LibrarianController::class, 'index'])->name('librarians.index');
        Route::get('/librarians/create', [LibrarianController::class, 'create'])->name('librarians.create');
        Route::post('/librarians', [LibrarianController::class, 'store'])->name('librarians.store');
        Route::get('/librarians/{librarian}', [LibrarianController::class, 'show'])->name('librarians.show');
        Route::delete('/librarians/{librarian}', [LibrarianController::class, 'destroy'])->name('librarians.destroy');

        // Rapports Admin
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/loans/pdf', [ReportController::class, 'exportLoansPdf'])->name('reports.loans.pdf');
        Route::get('/reports/loans/excel', [ReportController::class, 'exportLoansExcel'])->name('reports.loans.excel');
        Route::get('/reports/penalties/pdf', [ReportController::class, 'exportPenaltiesPdf'])->name('reports.penalties.pdf');
        Route::get('/reports/penalties/excel', [ReportController::class, 'exportPenaltiesExcel'])->name('reports.penalties.excel');
    });

    // ---- Bibliothécaire ----
    Route::prefix('librarian')->name('librarian.')->group(function () {
        Route::get('/dashboard', [LibrarianDashboard::class, 'index'])->name('dashboard');

        // Pénalités
        Route::get('/penalties', [PenaltyController::class, 'index'])->name('penalties.index');
        Route::post('/penalties/{penalty}/pay', [PenaltyController::class, 'pay'])->name('penalties.pay');
        Route::get('/penalties/{penalty}/receipt', [PenaltyController::class, 'receipt'])->name('penalties.receipt');

        // Retours
        Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');   // <-- Explicite
        Route::get('/returns/create', [ReturnController::class, 'create'])->name('returns.create');
        Route::post('/returns', [ReturnController::class, 'store'])->name('returns.store');

        // Emprunts
        Route::resource('loans', LoanController::class);
        Route::post('/loans/{loan}/renew', [ReturnController::class, 'renew'])->name('loans.renew');

        // Rapports Bibliothécaire (AJOUT)
        Route::get('/reports', [ReportController::class, 'librarianIndex'])->name('reports.index');
        Route::get('/reports/loans/pdf', [ReportController::class, 'librarianLoansPdf'])->name('reports.loans.pdf');
        Route::get('/reports/loans/excel', [ReportController::class, 'librarianLoansExcel'])->name('reports.loans.excel');
        Route::get('/reports/penalties/pdf', [ReportController::class, 'librarianPenaltiesPdf'])->name('reports.penalties.pdf');
        Route::get('/reports/penalties/excel', [ReportController::class, 'librarianPenaltiesExcel'])->name('reports.penalties.excel');
    });

    // ---- Enseignant ----
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');

        // Emprunts (AJOUT)
        Route::get('/loans', [TeacherDashboard::class, 'loans'])->name('loans.index');

        // Pénalités (AJOUT)
        Route::get('/penalties', [TeacherDashboard::class, 'penalties'])->name('penalties');

        // Réservations
        Route::get('/reservations', [TeacherReservation::class, 'index'])->name('reservations.index');
        Route::post('/reservations', [TeacherReservation::class, 'store'])->name('reservations.store');
        Route::delete('/reservations/{reservation}', [TeacherReservation::class, 'destroy'])->name('reservations.destroy');
    });

    // ---- Étudiant ----
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

        // Emprunts (AJOUT)
        Route::get('/loans', [StudentDashboard::class, 'loans'])->name('loans.index');

        // Pénalités (AJOUT)
        Route::get('/penalties', [StudentDashboard::class, 'penalties'])->name('penalties');

        // Réservations
        Route::get('/reservations', [StudentReservation::class, 'index'])->name('reservations.index');
        Route::post('/reservations', [StudentReservation::class, 'store'])->name('reservations.store');
        Route::delete('/reservations/{reservation}', [StudentReservation::class, 'destroy'])->name('reservations.destroy');
    });
});
