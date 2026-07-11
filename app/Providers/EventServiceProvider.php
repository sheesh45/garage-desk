<?php

namespace App\Providers;

use App\Events\JobCardCompleted;
use App\Listeners\SendVehicleReadyNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        JobCardCompleted::class => [
            SendVehicleReadyNotification::class,
        ],
    ];
}
