<?php

namespace App\Notifications;

use App\Models\JobCard;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleReadyNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected JobCard $jobCard,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Your vehicle is ready for pickup')
            ->greeting("Hello {$notifiable->name},")
            ->line("Job {$this->jobCard->job_number} for {$this->jobCard->vehicle?->registration_number} has been completed.")
            ->line('Our service advisor will help you with final delivery and billing.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'vehicle_ready',
            'job_number' => $this->jobCard->job_number,
            'vehicle' => $this->jobCard->vehicle?->registration_number,
        ];
    }
}
