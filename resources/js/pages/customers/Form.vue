<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import PageHeader from '@/components/garage/PageHeader.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps<{ customer: any | null }>();

const form = useForm({
    name: props.customer?.name ?? '',
    mobile: props.customer?.mobile ?? '',
    email: props.customer?.email ?? '',
    address: props.customer?.address ?? '',
    gst_number: props.customer?.gst_number ?? '',
    notes: props.customer?.notes ?? '',
});

const submit = () =>
    props.customer
        ? form.put(`/customers/${props.customer.id}`)
        : form.post('/customers');
</script>

<template>
    <Head :title="customer ? 'Edit Customer' : 'Add Customer'" />

    <div class="space-y-6 p-4 md:p-6">
        <PageHeader :title="customer ? 'Edit Customer' : 'Add Customer'" description="Capture contact details and workshop notes.">
            <Button as-child variant="outline"><Link href="/customers">Back</Link></Button>
        </PageHeader>

        <form class="grid gap-4 rounded-2xl border bg-card p-5 md:grid-cols-2" @submit.prevent="submit">
            <label class="space-y-2">
                <span class="text-sm">Name</span>
                <Input v-model="form.name" />
            </label>
            <label class="space-y-2">
                <span class="text-sm">Mobile</span>
                <Input v-model="form.mobile" />
            </label>
            <label class="space-y-2">
                <span class="text-sm">Email</span>
                <Input v-model="form.email" type="email" />
            </label>
            <label class="space-y-2">
                <span class="text-sm">GST Number</span>
                <Input v-model="form.gst_number" />
            </label>
            <label class="space-y-2 md:col-span-2">
                <span class="text-sm">Address</span>
                <textarea v-model="form.address" class="min-h-24 w-full rounded-md border bg-transparent px-3 py-2" />
            </label>
            <label class="space-y-2 md:col-span-2">
                <span class="text-sm">Notes</span>
                <textarea v-model="form.notes" class="min-h-28 w-full rounded-md border bg-transparent px-3 py-2" />
            </label>
            <div class="md:col-span-2">
                <Button type="submit" :disabled="form.processing">Save customer</Button>
            </div>
        </form>
    </div>
</template>
