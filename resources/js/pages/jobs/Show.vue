<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/components/garage/PageHeader.vue';
import StatusPill from '@/components/garage/StatusPill.vue';
import Button from '@/components/ui/button/Button.vue';

defineProps<{ job: any }>();
</script>

<template>
    <Head :title="job.job_number" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader :title="job.job_number" description="Detailed repair order, diagnosis, parts, and billing state.">
            <Button as-child variant="outline"><Link href="/jobs">Back</Link></Button>
        </PageHeader>
        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border bg-card p-5 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold">Complaint</h3>
                    <StatusPill :value="job.status" />
                </div>
                <p class="mt-3">{{ job.complaint }}</p>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div><p class="text-sm text-muted-foreground">Diagnosis</p><p>{{ job.diagnosis || 'N/A' }}</p></div>
                    <div><p class="text-sm text-muted-foreground">Repair Notes</p><p>{{ job.repair_notes || 'N/A' }}</p></div>
                </div>
            </div>
            <div class="rounded-2xl border bg-card p-5">
                <p><span class="text-muted-foreground">Customer:</span> {{ job.customer?.name }}</p>
                <p><span class="text-muted-foreground">Vehicle:</span> {{ job.vehicle?.registration_number }}</p>
                <p><span class="text-muted-foreground">Mechanic:</span> {{ job.mechanic?.name ?? 'Unassigned' }}</p>
                <p><span class="text-muted-foreground">Priority:</span> {{ job.priority }}</p>
            </div>
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl border bg-card p-5">
                <h3 class="font-semibold">Services</h3>
                <div class="mt-4 space-y-3"><div v-for="service in job.services" :key="service.id" class="rounded-xl border p-4">{{ service.name }} • INR {{ service.pivot?.total }}</div></div>
            </div>
            <div class="rounded-2xl border bg-card p-5">
                <h3 class="font-semibold">Parts</h3>
                <div class="mt-4 space-y-3"><div v-for="part in job.parts" :key="part.id" class="rounded-xl border p-4">{{ part.name }} • Qty {{ part.pivot?.quantity }} • INR {{ part.pivot?.total }}</div></div>
            </div>
        </div>
    </div>
</template>
