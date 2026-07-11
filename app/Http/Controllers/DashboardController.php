<?php

namespace App\Http\Controllers;

use App\Models\JobCard;
use App\Models\ServiceReminder;
use App\Services\DashboardMetricsService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(DashboardMetricsService $dashboardMetricsService): Response
    {
        $summary = $dashboardMetricsService->summary();

        return Inertia::render('Dashboard', [
            ...$summary,
            'recentJobs' => JobCard::query()
                ->with(['customer', 'vehicle', 'mechanic'])
                ->latest()
                ->limit(6)
                ->get()
                ->map(fn (JobCard $jobCard): array => [
                    'id' => $jobCard->id,
                    'job_number' => $jobCard->job_number,
                    'customer' => $jobCard->customer?->name,
                    'vehicle' => $jobCard->vehicle?->registration_number,
                    'mechanic' => $jobCard->mechanic?->name,
                    'status' => $jobCard->status?->value,
                    'priority' => $jobCard->priority?->value,
                    'estimated_delivery_at' => $jobCard->estimated_delivery_at?->toDateTimeString(),
                ]),
            'upcomingReminders' => ServiceReminder::query()
                ->with(['customer', 'vehicle'])
                ->whereBetween('due_at', [now(), now()->copy()->addDays(14)])
                ->orderBy('due_at')
                ->limit(6)
                ->get()
                ->map(fn (ServiceReminder $reminder): array => [
                    'id' => $reminder->id,
                    'type' => $reminder->type?->value,
                    'customer' => $reminder->customer?->name,
                    'vehicle' => $reminder->vehicle?->registration_number,
                    'due_at' => $reminder->due_at?->toDateString(),
                    'status' => $reminder->status,
                ]),
        ]);
    }
}
