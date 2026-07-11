<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/components/garage/PageHeader.vue';
import PaginationNav from '@/components/garage/PaginationNav.vue';
import StatusPill from '@/components/garage/StatusPill.vue';
import Button from '@/components/ui/button/Button.vue';

defineProps<{ invoices: { data: Array<any>; links: Array<any> } }>();
</script>

<template>
    <Head title="Invoices" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader title="Invoices" description="Convert completed workshop work into customer billing.">
            <Button as-child><Link href="/invoices/create">Create invoice</Link></Button>
            <Button as-child variant="outline"><a href="/reports/revenue">Export revenue</a></Button>
        </PageHeader>
        <div class="overflow-hidden rounded-2xl border bg-card">
            <table class="min-w-full text-sm">
                <thead class="bg-muted/40 text-left text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3">Invoice</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Vehicle</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="invoice in invoices.data" :key="invoice.id" class="border-t">
                        <td class="px-4 py-3 font-medium">{{ invoice.invoice_number }}</td>
                        <td class="px-4 py-3">{{ invoice.job_card?.customer?.name }}</td>
                        <td class="px-4 py-3">{{ invoice.job_card?.vehicle?.registration_number }}</td>
                        <td class="px-4 py-3">INR {{ invoice.grand_total }}</td>
                        <td class="px-4 py-3"><StatusPill :value="invoice.payment_status" /></td>
                        <td class="px-4 py-3"><div class="flex gap-2"><Button as-child variant="outline" size="sm"><Link :href="`/invoices/${invoice.id}`">View</Link></Button><Button as-child variant="outline" size="sm"><a :href="`/invoices/${invoice.id}/pdf`">PDF</a></Button></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <PaginationNav :links="invoices.links" />
    </div>
</template>
