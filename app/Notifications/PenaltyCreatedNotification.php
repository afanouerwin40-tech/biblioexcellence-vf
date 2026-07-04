<?php

namespace App\Notifications;

use App\Models\Penalty;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PenaltyCreatedNotification extends Notification
{
    public function __construct(private Penalty $penalty) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Pénalité de retard — BiblioExcellence')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une pénalité a été appliquée suite au retard de retour d\'un livre.')
            ->line('Livre : **' . ($this->penalty->loan->bookCopy->book->titre ?? '—') . '**')
            ->line('Jours de retard : **' . $this->penalty->jours_retard . ' jour(s)**')
            ->line('Montant : **' . number_format($this->penalty->montant, 0, ',', ' ') . ' FCFA**')
            ->line('Veuillez régler cette pénalité à la bibliothèque.')
            ->line('Tout nouvel emprunt est bloqué tant que la pénalité n\'est pas payée.')
            ->salutation('L\'équipe BiblioExcellence');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => ' Pénalité appliquée',
            'message' => "Une pénalité de {$this->penalty->montant} FCFA a été appliquée pour le livre '{$this->penalty->loan->bookCopy->book->titre}' ({$this->penalty->jours_retard} jour(s) de retard).",
            'url' => route('student.penalties'),
            'penalty_id' => $this->penalty->id,
        ];
    }
}
