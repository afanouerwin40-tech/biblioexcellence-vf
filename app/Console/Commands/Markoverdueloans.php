<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Notifications\LoanOverdueNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MarkOverdueLoans extends Command
{
    protected $signature = 'loans:mark-overdue';
    protected $description = 'Marque les emprunts dont la date de retour est dépassée comme "en_retard" et notifie les emprunteurs';

    public function handle()
    {
        // On ne cible que les emprunts encore "actif"/"renouvele" pour n'envoyer
        // la notification de retard qu'une seule fois, au moment du passage
        // en statut "en_retard".
        $loans = Loan::whereIn('statut', ['actif', 'renouvele'])
            ->whereDate('date_retour_prevue', '<', Carbon::today())
            ->with('user', 'bookCopy.book')
            ->get();

        foreach ($loans as $loan) {
            $loan->update(['statut' => 'en_retard']);
            $loan->user->notify(new LoanOverdueNotification($loan));
        }

        $this->info("{$loans->count()} emprunt(s) marqué(s) en retard.");
    }
}
