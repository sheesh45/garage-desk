<?php

namespace App\Repositories;

use App\Models\JobCard;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobCardRepository
{
    public function paginated(array $filters = []): LengthAwarePaginator
    {
        return JobCard::query()
            ->with(['customer', 'vehicle', 'mechanic', 'services', 'parts'])
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($innerQuery) use ($search): void {
                    $innerQuery
                        ->where('job_number', 'like', "%{$search}%")
                        ->orWhere('complaint', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn ($customerQuery) => $customerQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('vehicle', fn ($vehicleQuery) => $vehicleQuery->where('registration_number', 'like', "%{$search}%"));
                });
            })
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['mechanic_id'] ?? null, fn ($query, int $mechanicId) => $query->where('mechanic_id', $mechanicId))
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}
