<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotificationGenerale extends Notification
{
    use Queueable;

    protected $message;
    protected $demandeId;



    public function __construct($message, $demandeId)
    {
        $this->message = $message;
        $this->demandeId = $demandeId;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // stockage en BDD
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => $this->message,
             'demande_id' => $this->demandeId,
            'sender' => auth()->user()->name ?? 'DRH',
        ];
    }
}
