<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoanOverdueNotification extends Notification
{
    public function __construct(private Loan $loan) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $retard = now()->diffInDays($this->loan->date_retour_prevue);

        return (new MailMessage)
            ->subject('⚠️ Retour en retard — BiblioExcellence')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Le livre suivant devait déjà être rendu.')
            ->line('Livre : **' . ($this->loan->bookCopy->book->titre ?? '—') . '**')
            ->line('Exemplaire : **' . ($this->loan->bookCopy->code_exemplaire ?? '—') . '**')
            ->line('Date de retour prévue : **' . $this->loan->date_retour_prevue->format('d/m/Y') . '**')
            ->line("Retard actuel : **{$retard} jour(s)**")
            ->line('Merci de le rapporter au plus vite afin d\'éviter une pénalité supplémentaire.')
            ->action('Voir mes emprunts', route('student.loans.index'))
            ->salutation('L\'équipe BiblioExcellence');
    }

    public function toDatabase($notifiable): array
    {
        $retard = now()->diffInDays($this->loan->date_retour_prevue);

        return [
            'title' => 'Emprunt en retard',
            'message' => "Le livre '{$this->loan->bookCopy->book->titre}' devait être rendu le {$this->loan->date_retour_prevue->format('d/m/Y')} ({$retard} jour(s) de retard).",
            'url' => route('student.loans.index'),
            'loan_id' => $this->loan->id,
        ];
    }
}
