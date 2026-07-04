<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Penalty;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'emprunts_jour'   => Loan::whereDate('created_at', today())->count(),
            'retours_jour'    => Loan::whereDate('date_retour_effective', today())->count(),
            'en_retard'       => Loan::where('statut', 'en_retard')->count(),
            'penalites_dues'  => Penalty::where('statut', 'impayee')->sum('montant'),
            'nouveaux_inscrits'=> User::whereDate('created_at', today())->count(),
            'livres_dispo'    => Book::where('quantite_disponible', '>', 0)->count(),
        ];

        $emprunts_recents = Loan::with(['user', 'bookCopy.book'])
            ->latest()
            ->limit(8)
            ->get();

        return view('librarian.dashboard', compact('stats', 'emprunts_recents'));
    }
}