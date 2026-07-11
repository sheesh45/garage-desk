<?php

namespace App\Services;

use App\Repositories\DashboardRepository;

class DashboardMetricsService
{
    public function __construct(
        protected DashboardRepository $dashboardRepository,
    ) {
    }

    public function summary(): array
    {
        return [
            'metrics' => $this->dashboardRepository->metrics(),
            'charts' => $this->dashboardRepository->charts(),
        ];
    }
}
