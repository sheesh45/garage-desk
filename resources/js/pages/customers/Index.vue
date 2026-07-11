<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import PageHeader from '@/components/garage/PageHeader.vue';
import PaginationNav from '@/components/garage/PaginationNav.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

defineOptions({ layout: { breadcrumbs: [{ title: 'Customers', href: '/customers' }] } });

const props = defineProps<{
    customers: { data: Array<any>; links: Array<any> };
    filters: { search?: string };
}>();

const filters = reactive({ search: props.filters.search ?? '' });

const submit = () =>
    router.get('/customers', filters, {
        preserveState: true,
        preserveScroll: true,
    });
</script>

<template>
    <Head title="Customers" />

    <div class="space-y-6 p-4 md:p-6">
        <PageHeader title="Customers" description="Manage profiles, service history, and linked vehicles.">
            <Button as-child><Link href="/customers/create">Add customer</Link></Button>
        </PageHeader>

        <div class="flex gap-3 rounded-2xl border bg-card p-4">
            <Input v-model="filters.search" placeholder="Search by name, mobile, or email" />
            <Button @click="submit">Search</Button>
        </div>

        <div class="overflow-hidden rounded-2xl border bg-card">
            <table class="min-w-full text-sm">
                <thead class="bg-muted/40 text-left text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Mobile</th>
                        <th class="px-4 py-3">Vehicles</th>
                        <th class="px-4 py-3">Jobs</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="customer in customers.data" :key="customer.id" class="border-t">
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ customer.name }}</div>
                            <div class="text-muted-foreground">{{ customer.email }}</div>
                        </td>
                        <td class="px-4 py-3">{{ customer.mobile }}</td>
                        <td class="px-4 py-3">{{ customer.vehicles_count }}</td>
                        <td class="px-4 py-3">{{ customer.job_cards_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <Button as-child variant="outline" size="sm"><Link :href="`/customers/${customer.id}`">View</Link></Button>
                                <Button as-child variant="outline" size="sm"><Link :href="`/customers/${customer.id}/edit`">Edit</Link></Button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <PaginationNav :links="customers.links" />
    </div>
</template>
