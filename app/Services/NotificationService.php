<?php

// Service NotificationService
namespace App\Services;

use App\Models\ScheduledNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveReminderNotification;

class NotificationService
{
    public function sendScheduledNotifications()
    {
        $notifications = ScheduledNotification::readyToSend()->get();
        
        foreach ($notifications as $notification) {
            $this->sendNotification($notification);
            $notification->markAsSent();
        }
        
        return $notifications->count();
    }

    private function sendNotification(ScheduledNotification $scheduledNotification)
    {
        $recipients = $this->getRecipients($scheduledNotification->recipient_roles);
        
        foreach ($recipients as $user) {
            $user->notify(new LeaveReminderNotification([
                'title' => $scheduledNotification->title,
                'message' => $scheduledNotification->message,
                'type' => $scheduledNotification->type,
            ]));
        }
    }

    private function getRecipients(array $roles)
    {
        $users = collect();
        
        foreach ($roles as $role) {
            $users = $users->merge(User::role($role)->get());
        }
        
        return $users->unique('id');
    }

    public function createLeaveReminder($title, $message, $sendDate, $roles = ['agent'])
    {
        return ScheduledNotification::create([
            'title' => $title,
            'message' => $message,
            'type' => 'reminder',
            'send_date' => $sendDate,
            'send_time' => '09:00:00',
            'recipient_roles' => $roles,
        ]);
    }
}

// Commande Artisan pour envoyer les notifications
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class SendScheduledNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Envoie les notifications programmées';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(NotificationService $notificationService)
    {
        $count = $notificationService->sendScheduledNotifications();
        
        $this->info("$count notifications ont été envoyées.");
        
        return Command::SUCCESS;
    }
}

// Notification personnalisée
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveReminderNotification extends Notification
{
    use Queueable;

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->data['title'])
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line($this->data['message'])
            ->action('Accéder à mon espace', url('/dashboard'))
            ->line('Merci de votre attention.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'type' => $this->data['type'],
        ];
    }
}

// Seeder pour les paramètres par défaut
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;
use App\Models\LeaveType;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        // Paramètres généraux
        $settings = [
            ['key' => 'company_name', 'value' => 'Mon Entreprise', 'category' => 'general'],
            ['key' => 'max_leave_days_per_year', 'value' => '25', 'type' => 'number', 'category' => 'leave'],
            ['key' => 'advance_notice_days', 'value' => '7', 'type' => 'number', 'category' => 'leave'],
            ['key' => 'auto_approve_requests', 'value' => '0', 'type' => 'boolean', 'category' => 'leave'],
            ['key' => 'weekend_included', 'value' => '0', 'type' => 'boolean', 'category' => 'leave'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }

        // Types de congés par défaut
        $leaveTypes = [
            [
                'name' => 'Congé annuel',
                'code' => 'ANNUAL',
                'description' => 'Congé annuel payé',
                'max_days_per_year' => 25,
                'min_days_request' => 1,
                'max_days_request' => 10,
                'requires_approval' => true,
                'is_paid' => true,
                'advance_notice_days' => 7,
            ],
            [
                'name' => 'Congé maladie',
                'code' => 'SICK',
                'description' => 'Congé pour maladie',
                'max_days_per_year' => 15,
                'min_days_request' => 1,
                'max_days_request' => 5,
                'requires_approval' => false,
                'is_paid' => true,
                'advance_notice_days' => 0,
            ],
            [
                'name' => 'Congé maternité/paternité',
                'code' => 'MATERNITY',
                'description' => 'Congé pour naissance',
                'max_days_per_year' => 90,
                'min_days_request' => 30,
                'max_days_request' => 90,
                'requires_approval' => true,
                'is_paid' => true,
                'advance_notice_days' => 30,
            ],
            [
                'name' => 'Congé sans solde',
                'code' => 'UNPAID',
                'description' => 'Congé sans solde',
                'max_days_per_year' => 30,
                'min_days_request' => 1,
                'max_days_request' => 15,
                'requires_approval' => true,
                'is_paid' => false,
                'advance_notice_days' => 15,
            ],
        ];

        foreach ($leaveTypes as $type) {
            LeaveType::create($type);
        }
    }
}

// Job pour les rappels automatiques
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\NotificationService;

class ProcessScheduledNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(NotificationService $notificationService)
    {
        $notificationService->sendScheduledNotifications();
    }
}