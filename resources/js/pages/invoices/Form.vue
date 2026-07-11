<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import PageHeader from '@/components/garage/PageHeader.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps<{ jobs: Array<any> }>();
const selectedJob = () => props.jobs.find((job) => String(job.id) === String(form.job_card_id));
const form = useForm({
    job_card_id: props.jobs[0]?.id ?? '',
    discount_total: 0,
    tax_total: 0,
    payment_status: 'pending',
    notes: '',
});
const submit = () => form.post('/invoices');
</script>

<template>
    <Head title="Create Invoice" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader title="Create Invoice" description="Generate a professional bill from a completed or billable job.">
            <Button as-child variant="outline"><Link href="/invoices">Back</Link></Button>
        </PageHeader>
        <form class="grid gap-4 rounded-2xl border bg-card p-5 lg:grid-cols-2" @submit.prevent="submit">
            <label class="space-y-2 lg:col-span-2"><span class="text-sm">Job Card</span><select v-model="form.job_card_id" class="h-9 w-full rounded-md border bg-transparent px-3"><option v-for="job in jobs" :key="job.id" :value="job.id">{{ job.job_number }} • {{ job.customer?.name }} • {{ job.vehicle?.registration_number }}</option></select></label>
            <label class="space-y-2"><span class="text-sm">Discount</span><Input v-model="form.discount_total" type="number" /></label>
            <label class="space-y-2"><span class="text-sm">Tax</span><Input v-model="form.tax_total" type="number" /></label>
            <label class="space-y-2"><span class="text-sm">Payment Status</span><select v-model="form.payment_status" class="h-9 w-full rounded-md border bg-transparent px-3"><option value="pending">pending</option><option value="partial">partial</option><option value="paid">paid</option></select></label>
            <label class="space-y-2"><span class="text-sm">Notes</span><Input v-model="form.notes" /></label>
            <div class="rounded-2xl border border-dashed p-4 lg:col-span-2">
                <p class="font-medium">Selected job summary</p>
                <p class="mt-2 text-sm text-muted-foreground">{{ selectedJob()?.complaint }}</p>
                <p class="mt-2 text-sm">Services: {{ selectedJob()?.services?.length ?? 0 }} • Parts: {{ selectedJob()?.parts?.length ?? 0 }}</p>
            </div>
            <div class="lg:col-span-2"><Button type="submit">Generate invoice</Button></div>
        </form>
    </div>
</template>
