<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Penalty;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'emprunts_actifs' => Loan::where('user_id', $user->id)
                         ->whereIn('statut', ['actif', 'en_retard', 'renouvele'])->count(),
            'emprunts_total'  => Loan::where('user_id', $user->id)->count(),
            'penalites'       => Penalty::where('user_id', $user->id)
                                        ->where('statut', 'impayee')->sum('montant'),
            'reservations'    => Reservation::where('user_id', $user->id)
                                            ->where('statut', 'en_attente')->count(),
        ];

        // Emprunts actifs
        $emprunts = Loan::with('bookCopy.book')
            ->where('user_id', $user->id)
            ->whereIn('statut', ['actif', 'en_retard', 'renouvele'])
            ->latest()
            ->get();

        // Historique
        $historique = Loan::with('bookCopy.book')
            ->where('user_id', $user->id)
            ->whereIn('statut', ['retourne', 'perdu'])
            ->latest()
            ->limit(10)
            ->get();

        return view('student.dashboard', compact('stats', 'emprunts', 'historique'));
    }
}