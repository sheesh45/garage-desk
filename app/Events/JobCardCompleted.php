<?php

namespace App\Events;

use App\Models\JobCard;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobCardCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public JobCard $jobCard,
    ) {
    }
}
