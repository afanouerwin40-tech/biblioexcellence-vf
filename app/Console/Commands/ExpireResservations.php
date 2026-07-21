<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Services\LoanService;
use Illuminate\Console\Command;

class ExpireReservations extends Command
{
    protected $signature = 'reservations:expire';
    protected $description = 'Expire les réservations "disponible" dont le délai de retrait est dépassé et fait avancer la file d\'attente';

    public function handle(LoanService $loanService)
    {
        $expirees = Reservation::where('statut', 'disponible')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expirees as $reservation) {
            $reservation->update(['statut' => 'expiree']);

            // Fait avancer la file : la réservation suivante devient disponible
            $loanService->fulfillNextReservation($reservation->book_id);
        }

        $this->info("{$expirees->count()} réservation(s) expirée(s).");
    }
}
