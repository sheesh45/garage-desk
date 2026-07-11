<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MetricCard from '@/components/garage/MetricCard.vue';
import PageHeader from '@/components/garage/PageHeader.vue';
import SimpleBars from '@/components/garage/SimpleBars.vue';
import StatusPill from '@/components/garage/StatusPill.vue';
import Button from '@/components/ui/button/Button.vue';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Dashboard', href: '/dashboard' }],
    },
});

defineProps<{
    metrics: Record<string, number>;
    charts: {
        monthlyRevenue: Array<{ label: string; value: number }>;
        jobStatus: Array<{ label: string; value: number }>;
        vehicleTypes: Array<{ label: string; value: number }>;
        inventoryUsage: Array<{ label: string; value: number }>;
    };
    recentJobs: Array<Record<string, string | number | null>>;
    upcomingReminders: Array<Record<string, string | number | null>>;
}>();

const money = (value: number) =>
    new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        maximumFractionDigits: 0,
    }).format(value);
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-1 flex-col gap-6 rounded-3xl bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.16),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(59,130,246,0.14),_transparent_30%)] p-4 md:p-6">
        <PageHeader
            title="Garage360"
            description="Track jobs, revenue, reminders, and workshop load from one place."
        >
            <Button as-child>
                <Link href="/jobs/create">Create job card</Link>
            </Button>
            <Button as-child variant="outline">
                <Link href="/invoices/create">Generate invoice</Link>
            </Button>
        </PageHeader>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <MetricCard label="Today's Jobs" :value="metrics.today_jobs" />
            <MetricCard label="Pending Jobs" :value="metrics.pending_jobs" tone="warning" />
            <MetricCard label="Completed Jobs" :value="metrics.completed_jobs" tone="success" />
            <MetricCard label="Revenue Today" :value="money(metrics.revenue_today)" />
            <MetricCard label="Revenue This Month" :value="money(metrics.revenue_month)" />
            <MetricCard label="Low Stock Items" :value="metrics.low_stock_items" tone="warning" />
            <MetricCard label="Active Mechanics" :value="metrics.active_mechanics" />
            <MetricCard label="Upcoming Reminders" :value="metrics.upcoming_reminders" />
        </div>

        <div class="grid gap-4 xl:grid-cols-2">
            <SimpleBars title="Monthly Revenue" :items="charts.monthlyRevenue" />
            <SimpleBars title="Job Status Mix" :items="charts.jobStatus" />
            <SimpleBars title="Vehicle Types" :items="charts.vehicleTypes" />
            <SimpleBars title="Inventory Usage" :items="charts.inventoryUsage" />
        </div>

        <div class="grid gap-4 xl:grid-cols-[1.4fr_1fr]">
            <div class="rounded-2xl border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Recent Jobs</h3>
                    <Link href="/jobs" class="text-sm text-muted-foreground">View all</Link>
                </div>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-muted-foreground">
                            <tr>
                                <th class="pb-3">Job</th>
                                <th class="pb-3">Customer</th>
                                <th class="pb-3">Vehicle</th>
                                <th class="pb-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="job in recentJobs" :key="String(job.id)" class="border-t">
                                <td class="py-3 font-medium">{{ job.job_number }}</td>
                                <td class="py-3">{{ job.customer }}</td>
                                <td class="py-3">{{ job.vehicle }}</td>
                                <td class="py-3">
                                    <StatusPill :value="String(job.status)" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Upcoming Reminders</h3>
                    <Link href="/vehicles" class="text-sm text-muted-foreground">Vehicles</Link>
                </div>
                <div class="mt-4 space-y-3">
                    <div
                        v-for="reminder in upcomingReminders"
                        :key="String(reminder.id)"
                        class="rounded-xl border border-border/60 p-4"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-medium">{{ reminder.customer }}</p>
                                <p class="text-sm text-muted-foreground">
                                    {{ reminder.vehicle }} • {{ reminder.type }}
                                </p>
                            </div>
                            <p class="text-sm text-muted-foreground">{{ reminder.due_at }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
