<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/components/garage/PageHeader.vue';
import StatusPill from '@/components/garage/StatusPill.vue';
import Button from '@/components/ui/button/Button.vue';

defineProps<{ customer: any }>();
</script>

<template>
    <Head :title="customer.name" />

    <div class="space-y-6 p-4 md:p-6">
        <PageHeader :title="customer.name" description="Customer profile with linked vehicles and workshop history.">
            <Button as-child variant="outline"><Link href="/customers">Back</Link></Button>
        </PageHeader>

        <div class="grid gap-4 lg:grid-cols-[1fr_1.2fr]">
            <div class="rounded-2xl border bg-card p-5">
                <p><span class="text-muted-foreground">Mobile:</span> {{ customer.mobile }}</p>
                <p><span class="text-muted-foreground">Email:</span> {{ customer.email || 'N/A' }}</p>
                <p><span class="text-muted-foreground">GST:</span> {{ customer.gst_number || 'N/A' }}</p>
                <p class="mt-4 text-sm text-muted-foreground">{{ customer.address }}</p>
            </div>

            <div class="rounded-2xl border bg-card p-5">
                <h3 class="font-semibold">Vehicles</h3>
                <div class="mt-4 space-y-3">
                    <div v-for="vehicle in customer.vehicles" :key="vehicle.id" class="rounded-xl border p-4">
                        <p class="font-medium">{{ vehicle.registration_number }}</p>
                        <p class="text-sm text-muted-foreground">{{ vehicle.brand }} {{ vehicle.model }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border bg-card p-5">
            <h3 class="font-semibold">Service History</h3>
            <div class="mt-4 space-y-3">
                <div v-for="job in customer.job_cards" :key="job.id" class="flex items-center justify-between rounded-xl border p-4">
                    <div>
                        <p class="font-medium">{{ job.job_number }}</p>
                        <p class="text-sm text-muted-foreground">{{ job.vehicle?.registration_number }} • {{ job.complaint }}</p>
                    </div>
                    <StatusPill :value="job.status" />
                </div>
            </div>
        </div>
    </div>
</template>
