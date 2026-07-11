<?php

namespace App\Jobs;

use App\Notifications\UpcomingServiceReminderNotification;
use App\Models\ServiceReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendUpcomingServiceReminders implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        ServiceReminder::query()
            ->with(['customer', 'vehicle'])
            ->whereNull('sent_at')
            ->whereBetween('due_at', [now(), now()->copy()->addDays(3)])
            ->each(function (ServiceReminder $reminder): void {
                if ($reminder->customer?->email) {
                    $reminder->customer->notify(new UpcomingServiceReminderNotification($reminder));
                }

                $reminder->forceFill([
                    'sent_at' => now(),
                    'status' => 'sent',
                ])->save();
            });
    }
}
