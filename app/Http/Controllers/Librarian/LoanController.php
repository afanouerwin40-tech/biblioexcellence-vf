<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Loan;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct(private LoanService $loanService) {}

    /**
     * Liste de tous les emprunts actifs.
     */
    public function index()
    {
        $loans = Loan::with(['user', 'bookCopy.book'])
            ->whereIn('statut', ['actif', 'en_retard', 'renouvele'])
            ->latest()
            ->paginate(20);

        $stats = [
            'actifs'    => Loan::where('statut', 'actif')->count(),
            'en_retard' => Loan::where('statut', 'en_retard')->count(),
            'renouvele' => Loan::where('statut', 'renouvele')->count(),
            'total'     => Loan::count(),
        ];

        return view('librarian.loans.index', compact('loans', 'stats'));
    }

    /**
     * Formulaire de création d'un emprunt.
     */
    public function create(Request $request)
    {
        $user      = null;
        $canBorrow = null;
        $copies    = collect();

        // Si un utilisateur est sélectionné
        if ($request->filled('user_identifier')) {
            $user = User::where('identifier', $request->user_identifier)
                ->orWhere('email', $request->user_identifier)
                ->whereIn('role_type', ['student', 'teacher'])
                ->first();

            if ($user) {
                $canBorrow = $this->loanService->canBorrow($user);
            }
        }

        // Si un livre est sélectionné
        if ($request->filled('book_id')) {
            $copies = BookCopy::where('book_id', $request->book_id)
                ->where('disponible', true)
                ->get();
        }

        $books = Book::where('quantite_disponible', '>', 0)
            ->orderBy('titre')
            ->get();

        return view('librarian.loans.create', compact('user', 'canBorrow', 'copies', 'books'));
    }

    /**
     * Enregistre un emprunt.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'      => ['required', 'exists:users,id'],
            'book_copy_id' => ['required', 'exists:book_copies,id'],
        ], [
            'user_id.required'      => 'L\'utilisateur est obligatoire.',
            'book_copy_id.required' => 'L\'exemplaire est obligatoire.',
        ]);

        $user      = User::findOrFail($request->user_id);
        $copy      = BookCopy::findOrFail($request->book_copy_id);
        $librarian = auth()->user();

        // Vérifier que l'exemplaire est disponible
        if (!$copy->disponible) {
            return back()->withErrors(['book_copy_id' => 'Cet exemplaire n\'est plus disponible.']);
        }

        // Vérifier les règles métier
        $check = $this->loanService->canBorrow($user);
        if (!$check['can']) {
            return back()->withErrors(['user_id' => $check['reason']]);
        }

        $loan = $this->loanService->createLoan($user, $copy, $librarian);

        return redirect()->route('librarian.loans.index')
            ->with('success', "Emprunt enregistré. Retour prévu le {$loan->date_retour_prevue->format('d/m/Y')}.");
    }

    /**
     * Détail d'un emprunt.
     */
    public function show(Loan $loan)
    {
        $loan->load(['user', 'bookCopy.book', 'penalty']);
        return view('librarian.loans.show', compact('loan'));
    }
}