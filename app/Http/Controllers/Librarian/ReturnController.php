<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function __construct(private LoanService $loanService) {}

    /**
     * Formulaire de retour — recherche par code exemplaire ou matricule.
     */
    public function create(Request $request)
    {
        $loan = null;

        if ($request->filled('search')) {
            $loan = Loan::with(['user', 'bookCopy.book'])
                ->whereHas('bookCopy', function ($q) use ($request) {
                    $q->where('code_exemplaire', $request->search);
                })
                ->whereIn('statut', ['actif', 'en_retard', 'renouvele'])
                ->first();

            if (!$loan) {
                return back()->withErrors(['search' => 'Aucun emprunt actif trouvé pour ce code.']);
            }
        }

        return view('librarian.returns.create', compact('loan'));
    }

    /**
     * Traite le retour.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => ['required', 'exists:loans,id'],
        ]);

        $loan      = Loan::with(['bookCopy.book', 'user'])->findOrFail($request->loan_id);
        $librarian = auth()->user();

        $result = $this->loanService->processReturn($loan, $librarian);

        if ($result['retard'] > 0) {
            return redirect()->route('librarian.loans.index')
                ->with('warning', "Retour enregistré avec {$result['retard']} jour(s) de retard. Pénalité : {$result['penalite']->montant} FCFA.");
        }

        return redirect()->route('librarian.loans.index')
            ->with('success', 'Retour enregistré avec succès. Aucun retard.');
    }

    /**
     * Renouvellement d'un emprunt.
     */
    public function renew(Loan $loan)
    {
        $result = $this->loanService->renewLoan($loan);

        if (!$result['success']) {
            return back()->withErrors(['loan' => $result['reason']]);
        }

        return back()->with('success', 'Emprunt renouvelé. Nouveau retour prévu le ' .
            $loan->fresh()->date_retour_prevue->format('d/m/Y') . '.');
    }
}