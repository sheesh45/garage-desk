<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import PageHeader from '@/components/garage/PageHeader.vue';
import PaginationNav from '@/components/garage/PaginationNav.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps<{ vehicles: { data: Array<any>; links: Array<any> }; filters: { search?: string } }>();
const filters = reactive({ search: props.filters.search ?? '' });
const submit = () => router.get('/vehicles', filters, { preserveState: true, preserveScroll: true });
</script>

<template>
    <Head title="Vehicles" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader title="Vehicles" description="Track ownership, timelines, and compliance reminders.">
            <Button as-child><Link href="/vehicles/create">Add vehicle</Link></Button>
        </PageHeader>
        <div class="flex gap-3 rounded-2xl border bg-card p-4">
            <Input v-model="filters.search" placeholder="Search by registration, brand, or model" />
            <Button @click="submit">Search</Button>
        </div>
        <div class="overflow-hidden rounded-2xl border bg-card">
            <table class="min-w-full text-sm">
                <thead class="bg-muted/40 text-left text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3">Registration</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Vehicle</th>
                        <th class="px-4 py-3">Next Service</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="vehicle in vehicles.data" :key="vehicle.id" class="border-t">
                        <td class="px-4 py-3 font-medium">{{ vehicle.registration_number }}</td>
                        <td class="px-4 py-3">{{ vehicle.customer?.name }}</td>
                        <td class="px-4 py-3">{{ vehicle.brand }} {{ vehicle.model }}</td>
                        <td class="px-4 py-3">{{ vehicle.next_service_due_at ?? 'N/A' }}</td>
                        <td class="px-4 py-3"><Button as-child variant="outline" size="sm"><Link :href="`/vehicles/${vehicle.id}/edit`">Edit</Link></Button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <PaginationNav :links="vehicles.links" />
    </div>
</template>
