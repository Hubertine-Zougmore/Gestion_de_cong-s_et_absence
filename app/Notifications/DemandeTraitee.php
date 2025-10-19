<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandeTraitee extends Notification
{
    use Queueable;
    
    private $demande;
    /**
     * Create a new notification instance.
     */
    public function __construct($demande)
    {
        $this->demande = $demande;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
         return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

     public function toDatabase($notifiable)
    {
        return [
            'demande_id' => $this->demande->id,
            'etat' => $this->demande->etat,
            'message' => "Votre demande de {$this->demande->type} a été {$this->demande->etat}.",
        ];
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
