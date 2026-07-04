<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Notifications\LoanDueReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendDueReminders extends Command
{
    protected $signature = 'notifications:due-reminders';
    protected $description = 'Envoie un rappel 3 jours avant la date de retour';

    public function handle()
    {
        $loans = Loan::where('statut', 'actif')
            ->whereDate('date_retour_prevue', Carbon::today()->addDays(3))
            ->with('user')
            ->get();

        foreach ($loans as $loan) {
            $loan->user->notify(new LoanDueReminderNotification($loan));
        }

        $this->info('Rappels envoyés.');
    }
}
