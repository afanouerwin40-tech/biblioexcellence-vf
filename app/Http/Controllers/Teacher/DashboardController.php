<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'emprunts_actifs' => Loan::where('user_id', $user->id)
                                     ->where('statut', 'actif')->count(),
            'emprunts_total'  => Loan::where('user_id', $user->id)->count(),
            'reservations'    => Reservation::where('user_id', $user->id)
                                            ->where('statut', 'en_attente')->count(),
        ];

        $emprunts = Loan::with('bookCopy.book')
            ->where('user_id', $user->id)
            ->where('statut', 'actif')
            ->latest()
            ->limit(5)
            ->get();

        return view('teacher.dashboard', compact('stats', 'emprunts'));
    }
}