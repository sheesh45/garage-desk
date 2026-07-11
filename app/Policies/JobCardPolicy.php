<?php

namespace App\Policies;

use App\Models\JobCard;
use App\Models\User;

class JobCardPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('jobs.view');
    }

    public function view(User $user, JobCard $jobCard): bool
    {
        return $user->can('jobs.view');
    }

    public function create(User $user): bool
    {
        return $user->can('jobs.manage');
    }

    public function update(User $user, JobCard $jobCard): bool
    {
        return $user->can('jobs.manage') || ($user->hasRole('mechanic') && $jobCard->mechanic?->user_id === $user->id);
    }
}
