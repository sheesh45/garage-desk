<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import PageHeader from '@/components/garage/PageHeader.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps<{ vehicle: any | null; customers: Array<any> }>();

const form = useForm({
    customer_id: props.vehicle?.customer_id ?? props.customers[0]?.id ?? '',
    registration_number: props.vehicle?.registration_number ?? '',
    brand: props.vehicle?.brand ?? '',
    model: props.vehicle?.model ?? '',
    year: props.vehicle?.year ?? '',
    vehicle_type: props.vehicle?.vehicle_type ?? 'car',
    fuel_type: props.vehicle?.fuel_type ?? 'petrol',
    engine_number: props.vehicle?.engine_number ?? '',
    chassis_number: props.vehicle?.chassis_number ?? '',
    odometer: props.vehicle?.odometer ?? 0,
    insurance_expiry: props.vehicle?.insurance_expiry ?? '',
    puc_expiry: props.vehicle?.puc_expiry ?? '',
    last_service_at: props.vehicle?.last_service_at ?? '',
    next_service_due_at: props.vehicle?.next_service_due_at ?? '',
});

const submit = () => props.vehicle ? form.put(`/vehicles/${props.vehicle.id}`) : form.post('/vehicles');
</script>

<template>
    <Head :title="vehicle ? 'Edit Vehicle' : 'Add Vehicle'" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader :title="vehicle ? 'Edit Vehicle' : 'Add Vehicle'" description="Register customer vehicles with compliance and service dates.">
            <Button as-child variant="outline"><Link href="/vehicles">Back</Link></Button>
        </PageHeader>
        <form class="grid gap-4 rounded-2xl border bg-card p-5 md:grid-cols-2 lg:grid-cols-3" @submit.prevent="submit">
            <label class="space-y-2"><span class="text-sm">Customer</span><select v-model="form.customer_id" class="h-9 w-full rounded-md border bg-transparent px-3"><option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option></select></label>
            <label class="space-y-2"><span class="text-sm">Registration</span><Input v-model="form.registration_number" /></label>
            <label class="space-y-2"><span class="text-sm">Brand</span><Input v-model="form.brand" /></label>
            <label class="space-y-2"><span class="text-sm">Model</span><Input v-model="form.model" /></label>
            <label class="space-y-2"><span class="text-sm">Year</span><Input v-model="form.year" type="number" /></label>
            <label class="space-y-2"><span class="text-sm">Vehicle Type</span><select v-model="form.vehicle_type" class="h-9 w-full rounded-md border bg-transparent px-3"><option>car</option><option>bike</option></select></label>
            <label class="space-y-2"><span class="text-sm">Fuel Type</span><select v-model="form.fuel_type" class="h-9 w-full rounded-md border bg-transparent px-3"><option>petrol</option><option>diesel</option><option>electric</option><option>cng</option></select></label>
            <label class="space-y-2"><span class="text-sm">Engine Number</span><Input v-model="form.engine_number" /></label>
            <label class="space-y-2"><span class="text-sm">Chassis Number</span><Input v-model="form.chassis_number" /></label>
            <label class="space-y-2"><span class="text-sm">Odometer</span><Input v-model="form.odometer" type="number" /></label>
            <label class="space-y-2"><span class="text-sm">Insurance Expiry</span><Input v-model="form.insurance_expiry" type="date" /></label>
            <label class="space-y-2"><span class="text-sm">PUC Expiry</span><Input v-model="form.puc_expiry" type="date" /></label>
            <label class="space-y-2"><span class="text-sm">Last Service</span><Input v-model="form.last_service_at" type="date" /></label>
            <label class="space-y-2"><span class="text-sm">Next Service Due</span><Input v-model="form.next_service_due_at" type="date" /></label>
            <div class="md:col-span-2 lg:col-span-3"><Button type="submit" :disabled="form.processing">Save vehicle</Button></div>
        </form>
    </div>
</template>
