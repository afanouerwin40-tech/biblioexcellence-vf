<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationAvailableNotification extends Notification
{
    public function __construct(private Reservation $reservation) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Livre réservé disponible — BiblioExcellence')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Le livre que vous avez réservé est maintenant disponible.')
            ->line('Titre : **' . ($this->reservation->book->titre ?? '—') . '**')
            ->line('Auteur : **' . ($this->reservation->book->author->nom ?? '—') . '**')
            ->line('Vous avez **48 heures** pour venir l\'emprunter.')
            ->action('Voir mes réservations', route('student.reservations.index'))
            ->salutation('L\'équipe BiblioExcellence');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Livre réservé disponible',
            'message' => "Le livre '{$this->reservation->book->titre}' que vous avez réservé est maintenant disponible. Vous avez 48h pour l'emprunter.",
            'url' => route('student.reservations.index'),
            'reservation_id' => $this->reservation->id,
        ];
    }
}
