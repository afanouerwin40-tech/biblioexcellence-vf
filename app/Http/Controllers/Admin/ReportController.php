<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Penalty;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoansExport;
use App\Exports\PenaltiesExport;

class ReportController extends Controller
{
    // ======================== ADMIN ========================

    public function index()
    {
        $stats = [
            'total_loans'      => Loan::count(),
            'active_loans'     => Loan::whereIn('statut', ['actif', 'en_retard', 'renouvele'])->count(),
            'overdue_loans'    => Loan::where('statut', 'en_retard')->count(),
            'total_penalties'  => Penalty::sum('montant') ?? 0,
            'paid_penalties'   => Penalty::where('statut', 'payee')->sum('montant') ?? 0,
            'total_users'      => User::count(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    public function exportLoansPdf(Request $request)
    {
        $loans = Loan::with(['user', 'bookCopy.book'])
            ->when($request->from, fn($q) => $q->whereDate('date_emprunt', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('date_emprunt', '<=', $request->to))
            ->when($request->statut, fn($q) => $q->where('statut', $request->statut))
            ->get();

        $pdf = Pdf::loadView('admin.reports.loans-pdf', compact('loans'));
        return $pdf->download('rapport_emprunts.pdf');
    }

    public function exportLoansExcel(Request $request)
    {
        return Excel::download(new LoansExport($request->all()), 'rapport_emprunts.xlsx');
    }

    public function exportPenaltiesPdf()
    {
        $penalties = Penalty::with(['user', 'loan.bookCopy.book'])->get();
        $pdf = Pdf::loadView('admin.reports.penalties-pdf', compact('penalties'));
        return $pdf->download('rapport_penalites.pdf');
    }

    public function exportPenaltiesExcel()
    {
        return Excel::download(new PenaltiesExport, 'rapport_penalites.xlsx');
    }

    // ======================== BIBLIOTHÉCAIRE ========================

    public function librarianIndex()
    {
        $stats = [
            'total_emprunts'       => Loan::count(),
            'emprunts_en_cours'    => Loan::whereIn('statut', ['actif', 'en_retard', 'renouvele'])->count(),
            'retards'              => Loan::where('statut', 'en_retard')->count(),
            'total_penalites'      => Penalty::sum('montant') ?? 0,
        ];

        // 12 derniers mois pour les graphiques
        $months = collect(range(0, 11))->map(fn($i) => now()->subMonths($i)->format('M Y'))->reverse();
        $chartLabels = $months->values();
        $chartLoans = $months->map(function ($month) {
            $parts = explode(' ', $month);
            $monthNum = date('m', strtotime($parts[0] . ' 1'));
            $year = $parts[1];
            return Loan::whereMonth('date_emprunt', $monthNum)->whereYear('date_emprunt', $year)->count();
        });
        $chartPenalties = $months->map(function ($month) {
            $parts = explode(' ', $month);
            $monthNum = date('m', strtotime($parts[0] . ' 1'));
            $year = $parts[1];
            return Penalty::whereMonth('created_at', $monthNum)->whereYear('created_at', $year)->sum('montant') ?? 0;
        });

        return view('librarian.reports.index', compact('stats', 'chartLabels', 'chartLoans', 'chartPenalties'));
    }

    public function librarianLoansPdf(Request $request)
    {
        $loans = Loan::with(['user', 'bookCopy.book'])
            ->when($request->from, fn($q) => $q->whereDate('date_emprunt', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('date_emprunt', '<=', $request->to))
            ->when($request->statut, fn($q) => $q->where('statut', $request->statut))
            ->get();

        $pdf = Pdf::loadView('librarian.reports.loans-pdf', compact('loans'));
        return $pdf->download('rapport_emprunts.pdf');
    }

    public function librarianLoansExcel(Request $request)
    {
        return Excel::download(new LoansExport($request->all()), 'rapport_emprunts.xlsx');
    }

    public function librarianPenaltiesPdf()
    {
        $penalties = Penalty::with(['user', 'loan.bookCopy.book'])->get();
        $pdf = Pdf::loadView('librarian.reports.penalties-pdf', compact('penalties'));
        return $pdf->download('rapport_penalites.pdf');
    }

    public function librarianPenaltiesExcel()
    {
        return Excel::download(new PenaltiesExport, 'rapport_penalites.xlsx');
    }
}
