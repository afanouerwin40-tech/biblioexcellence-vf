<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Penalty;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PenaltyController extends Controller
{
    /**
     * Liste toutes les pénalités.
     */
    public function index(Request $request)
    {
        $query = Penalty::with(['user', 'loan.bookCopy.book'])
            ->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('identifier', 'like', '%' . $request->search . '%');
            });
        }

        $penalties = $query->paginate(20);

        $stats = [
            'total_impayees'  => Penalty::where('statut', 'impayee')->sum('montant'),
            'total_payees'    => Penalty::where('statut', 'payee')->sum('montant'),
            'nb_impayees'     => Penalty::where('statut', 'impayee')->count(),
            'nb_payees'       => Penalty::where('statut', 'payee')->count(),
        ];

        return view('librarian.penalties.index', compact('penalties', 'stats'));
    }

    /**
     * Enregistre un paiement.
     */
    public function pay(Request $request, Penalty $penalty)
    {
        $request->validate([
            'montant_paye' => ['required', 'numeric', 'min:1'],
            'methode'      => ['required', 'in:especes,mobile_money,virement'],
        ], [
            'montant_paye.required' => 'Le montant est obligatoire.',
            'montant_paye.min'      => 'Le montant doit être positif.',
            'methode.required'      => 'La méthode de paiement est obligatoire.',
        ]);

        $montantPaye   = (float) $request->montant_paye;
        $montantRestant = $penalty->montant - $penalty->montant_paye;

        if ($montantPaye > $montantRestant) {
            return back()->withErrors(['montant_paye' => 'Le montant dépasse la somme due.']);
        }

        // Créer le paiement
        Payment::create([
            'penalty_id'   => $penalty->id,
            'recu_par'     => auth()->id(),
            'montant_paye' => $montantPaye,
            'methode'      => $request->methode,
            'reference'    => $request->reference,
            'paid_at'      => now(),
        ]);

        // Mettre à jour la pénalité
        $nouveauMontantPaye = $penalty->montant_paye + $montantPaye;
        $statut = $nouveauMontantPaye >= $penalty->montant ? 'payee' : 'partiellement_payee';

        $penalty->update([
            'montant_paye' => $nouveauMontantPaye,
            'statut'       => $statut,
        ]);

        return redirect()->route('librarian.penalties.index')
            ->with('success', "Paiement de {$montantPaye} FCFA enregistré.");
    }

    /**
     * Génère un reçu PDF.
     */
    public function receipt(Penalty $penalty)
    {
        $penalty->load(['user', 'loan.bookCopy.book', 'payments']);

        $pdf = Pdf::loadView('librarian.penalties.receipt', compact('penalty'));

        return $pdf->download("recu_penalite_{$penalty->id}.pdf");
    }
}