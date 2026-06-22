<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Student;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books'      => Book::count(),
            'total_students'   => Student::count(),
            'pending_accounts' => User::where('status', 'pending')->count(),
            'available_books'  => Book::where('quantite_disponible', '>', 0)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}