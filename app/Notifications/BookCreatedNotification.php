<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookCreatedNotification extends Notification
{
    public function __construct(private Book $book) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau livre ajouté — BiblioExcellence')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un nouveau livre a été ajouté au catalogue.')
            ->line('Titre : **' . $this->book->titre . '**')
            ->line('Auteur : **' . ($this->book->author->nom ?? '—') . '**')
            ->line('Catégorie : **' . ($this->book->category->nom ?? '—') . '**')
            ->action('Voir le livre', route('catalogue.show', $this->book))
            ->salutation('L\'équipe BiblioExcellence');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Nouveau livre ajouté',
            'message' => "Le livre '{$this->book->titre}' de {$this->book->author->nom} a été ajouté au catalogue.",
            'url' => route('catalogue.show', $this->book),
            'book_id' => $this->book->id,
        ];
    }
}
