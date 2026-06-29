<?php

namespace App\Services;

use App\Models\BookCopy;
use App\Models\Loan;
use App\Models\Penalty;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoanService
{
    /**
     * Vérifie si un utilisateur peut emprunter un livre.
     * Retourne un tableau avec 'can' (bool) et 'reason' (string).
     */
    public function canBorrow(User $user): array
    {
        // Compte suspendu
        if ($user->status === 'suspended') {
            return [
                'can'    => false,
                'reason' => 'Ce compte est suspendu.',
            ];
        }

        // Compte non approuvé
        if ($user->status !== 'approved') {
            return [
                'can'    => false,
                'reason' => 'Ce compte n\'est pas approuvé.',
            ];
        }

        // Pénalités impayées
        $penalites = Penalty::where('user_id', $user->id)
            ->where('statut', 'impayee')
            ->sum('montant');

        if ($penalites > 0) {
            return [
                'can'    => false,
                'reason' => "Cet utilisateur a {$penalites} FCFA de pénalités impayées.",
            ];
        }

        // Quota d'emprunts
        $maxEmprunts = $this->getMaxEmprunts($user);
        $empruntsActifs = Loan::where('user_id', $user->id)
            ->whereIn('statut', ['actif', 'en_retard', 'renouvele'])
            ->count();

        if ($empruntsActifs >= $maxEmprunts) {
            return [
                'can'    => false,
                'reason' => "Quota atteint ({$empruntsActifs}/{$maxEmprunts} livres).",
            ];
        }

        return ['can' => true, 'reason' => ''];
    }

    /**
     * Crée un emprunt.
     */
    public function createLoan(User $user, BookCopy $copy, User $librarian): Loan
    {
        return DB::transaction(function () use ($user, $copy, $librarian) {
            $duree = (int) Setting::get('duree_emprunt_jours', 14);

            $loan = Loan::create([
                'user_id'           => $user->id,
                'book_copy_id'      => $copy->id,
                'date_emprunt'      => now(),
                'date_retour_prevue'=> now()->addDays($duree),
                'statut'            => 'actif',
                'traite_par'        => $librarian->id,
            ]);

            // Marquer l'exemplaire comme indisponible
            $copy->update(['disponible' => false]);

            // Décrémenter la quantité disponible du livre
            $copy->book->decrement('quantite_disponible');

            return $loan;
        });
    }

    /**
     * Traite le retour d'un livre.
     */
    public function processReturn(Loan $loan, User $librarian): array
    {
        return DB::transaction(function () use ($loan, $librarian) {
            $now       = now();
            $retard    = 0;
            $penalite  = null;

            // Calcul du retard
            if ($now->gt($loan->date_retour_prevue)) {
                $retard = $now->diffInDays($loan->date_retour_prevue);
            }

            // Mise à jour de l'emprunt
            $loan->update([
                'date_retour_effective' => $now,
                'statut'                => 'retourne',
                'traite_par'            => $librarian->id,
            ]);

            // Libérer l'exemplaire
            $loan->bookCopy->update(['disponible' => true]);

            // Incrémenter la quantité disponible
            $loan->bookCopy->book->increment('quantite_disponible');

            // Créer la pénalité si retard
            if ($retard > 0) {
                $tarifJour = (float) Setting::get('penalite_par_jour', 100);
                $montant   = $retard * $tarifJour;

                $penalite = Penalty::create([
                    'loan_id'      => $loan->id,
                    'user_id'      => $loan->user_id,
                    'jours_retard' => $retard,
                    'montant'      => $montant,
                    'statut'       => 'impayee',
                ]);
            }

            return [
                'retard'   => $retard,
                'penalite' => $penalite,
            ];
        });
    }

    /**
     * Renouvelle un emprunt.
     */
    public function renewLoan(Loan $loan): array
    {
        $maxRenouvellements = (int) Setting::get('max_renouvellements', 1);

        if ($loan->renouvellements >= $maxRenouvellements) {
            return [
                'success' => false,
                'reason'  => "Maximum {$maxRenouvellements} renouvellement(s) autorisé(s).",
            ];
        }

        if ($loan->statut === 'en_retard') {
            return [
                'success' => false,
                'reason'  => 'Impossible de renouveler un emprunt en retard.',
            ];
        }

        $duree = (int) Setting::get('duree_emprunt_jours', 14);

        $loan->update([
            'date_retour_prevue' => now()->addDays($duree),
            'renouvellements'    => $loan->renouvellements + 1,
            'statut'             => 'renouvele',
        ]);

        return ['success' => true];
    }

    /**
     * Retourne le quota d'emprunts selon le rôle.
     */
    private function getMaxEmprunts(User $user): int
    {
        if ($user->role_type === 'teacher') {
            return (int) Setting::get('max_emprunts_enseignant', 5);
        }

        return (int) Setting::get('max_emprunts_etudiant', 2);
    }
}