<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoanDueReminderNotification extends Notification
{
    public function __construct(private Loan $loan) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Rappel : retour dans 3 jours — BiblioExcellence')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Vous avez un emprunt qui arrive à échéance dans 3 jours.')
            ->line('Livre : **' . ($this->loan->bookCopy->book->titre ?? '—') . '**')
            ->line('Date de retour prévue : **' . $this->loan->date_retour_prevue->format('d/m/Y') . '**')
            ->line('Pensez à retourner le livre à temps pour éviter des pénalités.')
            ->action('Voir mes emprunts', route('student.loans.index'))
            ->salutation('L\'équipe BiblioExcellence');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Retour dans 3 jours',
            'message' => "Le livre '{$this->loan->bookCopy->book->titre}' doit être retourné le {$this->loan->date_retour_prevue->format('d/m/Y')}.",
            'url' => route('student.loans.index'),
            'loan_id' => $this->loan->id,
        ];
    }
}
