<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/components/garage/PageHeader.vue';
import StatusPill from '@/components/garage/StatusPill.vue';
import Button from '@/components/ui/button/Button.vue';

defineProps<{ invoice: any }>();
</script>

<template>
    <Head :title="invoice.invoice_number" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader :title="invoice.invoice_number" description="Billing summary with line items and payment status.">
            <Button as-child variant="outline"><Link href="/invoices">Back</Link></Button>
            <Button as-child><a :href="`/invoices/${invoice.id}/pdf`">Download PDF</a></Button>
        </PageHeader>
        <div class="grid gap-4 lg:grid-cols-[1.4fr_1fr]">
            <div class="rounded-2xl border bg-card p-5">
                <h3 class="font-semibold">Line Items</h3>
                <div class="mt-4 space-y-3">
                    <div v-for="item in invoice.items" :key="item.id" class="flex items-center justify-between rounded-xl border p-4">
                        <div>
                            <p class="font-medium">{{ item.description }}</p>
                            <p class="text-sm text-muted-foreground">{{ item.type }} • Qty {{ item.quantity }}</p>
                        </div>
                        <p>INR {{ item.total }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border bg-card p-5">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold">Summary</h3>
                    <StatusPill :value="invoice.payment_status" />
                </div>
                <div class="mt-4 space-y-2 text-sm">
                    <p>Customer: {{ invoice.job_card?.customer?.name }}</p>
                    <p>Vehicle: {{ invoice.job_card?.vehicle?.registration_number }}</p>
                    <p>Labour: INR {{ invoice.labour_total }}</p>
                    <p>Parts: INR {{ invoice.parts_total }}</p>
                    <p>Discount: INR {{ invoice.discount_total }}</p>
                    <p>Tax: INR {{ invoice.tax_total }}</p>
                    <p class="text-lg font-semibold">Grand Total: INR {{ invoice.grand_total }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
