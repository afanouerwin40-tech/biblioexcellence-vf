<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoanReturnedNotification extends Notification
{
    public function __construct(private Loan $loan) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Retour de livre confirmé — BiblioExcellence')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Le livre suivant a été retourné avec succès.')
            ->line('Titre : **' . ($this->loan->bookCopy->book->titre ?? '—') . '**')
            ->line('Exemplaire : **' . ($this->loan->bookCopy->code_exemplaire ?? '—') . '**')
            ->line('Date de retour : **' . ($this->loan->date_retour_effective?->format('d/m/Y') ?? now()->format('d/m/Y')) . '**')
            ->line('Merci d\'avoir rendu ce livre.')
            ->salutation('L\'équipe BiblioExcellence');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Retour de livre',
            'message' => "Le livre '{$this->loan->bookCopy->book->titre}' a été retourné avec succès.",
            'url' => route('student.loans.index'),
            'loan_id' => $this->loan->id,
        ];
    }
}
