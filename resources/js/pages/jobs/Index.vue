<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import PageHeader from '@/components/garage/PageHeader.vue';
import PaginationNav from '@/components/garage/PaginationNav.vue';
import StatusPill from '@/components/garage/StatusPill.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps<{ jobs: { data: Array<any>; links: Array<any> }; filters: Record<string, any>; statuses: Array<string>; mechanics: Array<any> }>();
const filters = reactive({
    search: props.filters.search ?? '',
    status: props.filters.status ?? '',
    mechanic_id: props.filters.mechanic_id ?? '',
});
const submit = () => router.get('/jobs', filters, { preserveState: true, preserveScroll: true });
</script>

<template>
    <Head title="Job Cards" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader title="Job Cards" description="Create, assign, and track workshop execution from intake to delivery.">
            <Button as-child><Link href="/jobs/create">Create job card</Link></Button>
        </PageHeader>
        <div class="grid gap-3 rounded-2xl border bg-card p-4 md:grid-cols-4">
            <Input v-model="filters.search" placeholder="Search jobs, vehicle, customer" />
            <select v-model="filters.status" class="h-9 rounded-md border bg-transparent px-3">
                <option value="">All statuses</option>
                <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
            </select>
            <select v-model="filters.mechanic_id" class="h-9 rounded-md border bg-transparent px-3">
                <option value="">All mechanics</option>
                <option v-for="mechanic in mechanics" :key="mechanic.id" :value="mechanic.id">{{ mechanic.name }}</option>
            </select>
            <Button @click="submit">Apply filters</Button>
        </div>
        <div class="overflow-hidden rounded-2xl border bg-card">
            <table class="min-w-full text-sm">
                <thead class="bg-muted/40 text-left text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3">Job</th>
                        <th class="px-4 py-3">Vehicle</th>
                        <th class="px-4 py-3">Mechanic</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="job in jobs.data" :key="job.id" class="border-t">
                        <td class="px-4 py-3"><div class="font-medium">{{ job.job_number }}</div><div class="text-muted-foreground">{{ job.customer?.name }}</div></td>
                        <td class="px-4 py-3">{{ job.vehicle?.registration_number }}</td>
                        <td class="px-4 py-3">{{ job.mechanic?.name ?? 'Unassigned' }}</td>
                        <td class="px-4 py-3"><StatusPill :value="job.status" /></td>
                        <td class="px-4 py-3"><div class="flex gap-2"><Button as-child variant="outline" size="sm"><Link :href="`/jobs/${job.id}`">View</Link></Button><Button as-child variant="outline" size="sm"><Link :href="`/jobs/${job.id}/edit`">Edit</Link></Button></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <PaginationNav :links="jobs.links" />
    </div>
</template>
