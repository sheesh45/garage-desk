<?php

namespace App\Listeners;

use App\Events\JobCardCompleted;
use App\Notifications\VehicleReadyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVehicleReadyNotification implements ShouldQueue
{
    public function handle(JobCardCompleted $event): void
    {
        $customer = $event->jobCard->customer;

        if ($customer && $customer->email) {
            $customer->notify(new VehicleReadyNotification($event->jobCard));
        }
    }
}
