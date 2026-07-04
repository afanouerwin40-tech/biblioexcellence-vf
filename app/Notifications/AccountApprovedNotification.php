<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AccountApprovedNotification extends Notification
{
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre compte BiblioExcellence a été approuvé')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre compte a été approuvé par l\'administration.')
            ->line('Vous pouvez maintenant vous connecter à la plateforme.')
            ->action('Se connecter', url('/login'))
            ->line('Identifiant : ' . $notifiable->identifier)
            ->salutation('L\'équipe BiblioExcellence');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Compte approuvé',
            'message' => 'Votre compte a été approuvé. Vous pouvez maintenant vous connecter avec votre identifiant : ' . $notifiable->identifier,
            'url' => route('login'),
        ];
    }
}
