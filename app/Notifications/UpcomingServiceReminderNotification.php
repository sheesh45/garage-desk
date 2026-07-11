<?php

namespace App\Notifications;

use App\Models\ServiceReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingServiceReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected ServiceReminder $reminder,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Upcoming vehicle reminder')
            ->greeting("Hello {$notifiable->name},")
            ->line("Reminder for {$this->reminder->vehicle?->registration_number}: {$this->reminder->type?->value} due on {$this->reminder->due_at?->toFormattedDateString()}.");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'service_reminder',
            'reminder_type' => $this->reminder->type?->value,
            'vehicle' => $this->reminder->vehicle?->registration_number,
            'due_at' => $this->reminder->due_at?->toDateString(),
        ];
    }
}
