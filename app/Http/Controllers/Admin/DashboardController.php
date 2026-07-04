<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books'      => Book::count(),
            'total_students'   => Student::count(),
            'total_teachers'   => Teacher::count(),
            'pending_accounts' => User::where('status', 'pending')->count(),
            'available_books'  => Book::where('quantite_disponible', '>', 0)->count(),
            'borrowed_books'   => Book::where('quantite_disponible', 0)->count(),
        ];

        // Inscriptions 6 derniers mois
        $months = ['Jan','Fév','Mar','Avr','Mai','Jui','Jul','Aoû','Sep','Oct','Nov','Déc'];
        $registrationLabels = [];
        $registrationData   = [];

        $registrations = User::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->get();

        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $month = (int) $date->format('m');
            $year  = (int) $date->format('Y');
            $registrationLabels[] = $months[$month - 1] . ' ' . $year;
            $found = $registrations->first(fn($r) => (int)$r->month === $month && (int)$r->year === $year);
            $registrationData[] = $found ? $found->total : 0;
        }

        // Livres par catégorie
        $booksByCategory = Category::withCount('books')
            ->having('books_count', '>', 0)
            ->orderByDesc('books_count')
            ->limit(6)
            ->get();

        // Statuts des comptes
        $accountStats = [
            'approved'  => User::where('status', 'approved')->count(),
            'pending'   => User::where('status', 'pending')->count(),
            'rejected'  => User::where('status', 'rejected')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
        ];

        // Derniers comptes en attente
        $recentUsers = User::where('status', 'pending')
            ->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'registrationLabels',
            'registrationData',
            'booksByCategory',
            'accountStats',
            'recentUsers'
        ));
    }
}