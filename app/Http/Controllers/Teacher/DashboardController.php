<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Penalty;
use App\Models\Reservation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'emprunts_actifs' => Loan::where('user_id', $user->id)
                ->whereIn('statut', ['actif', 'en_retard', 'renouvele'])->count(),
            'emprunts_total'  => Loan::where('user_id', $user->id)->count(),
            'reservations'    => Reservation::where('user_id', $user->id)
                ->where('statut', 'en_attente')->count(),
            'penalites'       => Penalty::where('user_id', $user->id)
                ->where('statut', 'impayee')->sum('montant'),
        ];

        $emprunts = Loan::with('bookCopy.book')
            ->where('user_id', $user->id)
            ->whereIn('statut', ['actif', 'en_retard', 'renouvele'])
            ->latest()
            ->get();

        $historique = Loan::with('bookCopy.book')
            ->where('user_id', $user->id)
            ->whereIn('statut', ['retourne', 'perdu'])
            ->latest()
            ->limit(10)
            ->get();

        return view('teacher.dashboard', compact('stats', 'emprunts', 'historique'));
    }

    public function penalties()
    {
        $user = auth()->user();
        $penalties = Penalty::with(['loan.bookCopy.book'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.penalties', compact('penalties'));
    }

    public function loans()
    {
        $loans = Loan::with('bookCopy.book')
            ->where('user_id', auth()->id())
            ->orderBy('date_emprunt', 'desc')
            ->paginate(15);

        return view('teacher.loans', compact('loans'));
    }


}
