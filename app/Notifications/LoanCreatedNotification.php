<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoanCreatedNotification extends Notification
{
    public function __construct(private Loan $loan) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation d\'emprunt — BiblioExcellence')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre emprunt a été enregistré avec succès.')
            ->line('Livre : **' . ($this->loan->bookCopy->book->titre ?? '—') . '**')
            ->line('Exemplaire : **' . ($this->loan->bookCopy->code_exemplaire ?? '—') . '**')
            ->line('Date d\'emprunt : ' . $this->loan->date_emprunt->format('d/m/Y'))
            ->line('Date de retour prévue : **' . $this->loan->date_retour_prevue->format('d/m/Y') . '**')
            ->line('Merci de retourner le livre avant la date prévue.')
            ->salutation('L\'équipe BiblioExcellence');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Nouvel emprunt',
            'message' => "Vous avez emprunté le livre '{$this->loan->bookCopy->book->titre}' à retourner le {$this->loan->date_retour_prevue->format('d/m/Y')}.",
            'url' => route('student.loans.index'),
            'loan_id' => $this->loan->id,
        ];
    }
}
