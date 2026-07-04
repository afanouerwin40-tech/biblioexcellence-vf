<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AccountRejectedNotification extends Notification
{
    public function __construct(private string $raison = '') {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Votre compte BiblioExcellence a été rejeté')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous regrettons de vous informer que votre compte n\'a pas été validé par l\'administration.');

        if (!empty($this->raison)) {
            $mail->line('Raison : ' . $this->raison);
        }

        $mail->line('Vous pouvez contacter le support si vous avez des questions.')
            ->salutation('L\'équipe BiblioExcellence');

        return $mail;
    }

    public function toDatabase($notifiable): array
    {
        $message = 'Votre demande d\'inscription a été rejetée.';
        if (!empty($this->raison)) {
            $message .= ' Raison : ' . $this->raison;
        }

        return [
            'title' => 'Compte rejeté',
            'message' => $message,
            'url' => route('register'),
        ];
    }
}
