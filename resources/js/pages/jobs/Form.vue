<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import PageHeader from '@/components/garage/PageHeader.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps<{ job: any | null; customers: Array<any>; vehicles: Array<any>; mechanics: Array<any>; services: Array<any>; parts: Array<any>; statuses: Array<string>; priorities: Array<string> }>();

const form = useForm({
    customer_id: props.job?.customer_id ?? props.customers[0]?.id ?? '',
    vehicle_id: props.job?.vehicle_id ?? props.vehicles[0]?.id ?? '',
    mechanic_id: props.job?.mechanic_id ?? '',
    complaint: props.job?.complaint ?? '',
    estimated_delivery_at: props.job?.estimated_delivery_at?.slice(0, 16) ?? '',
    priority: props.job?.priority ?? props.priorities[1] ?? 'normal',
    status: props.job?.status ?? props.statuses[0] ?? 'pending',
    diagnosis: props.job?.diagnosis ?? '',
    repair_notes: props.job?.repair_notes ?? '',
    time_taken_minutes: props.job?.time_taken_minutes ?? 0,
    inspection_notes: props.job?.inspection_notes ?? '',
    delivery_notes: props.job?.delivery_notes ?? '',
    services: props.job?.services?.map((service: any) => service.id) ?? [],
    parts: props.job?.parts?.map((part: any) => ({ id: part.id, quantity: part.pivot?.quantity ?? 1 })) ?? [],
});

const visibleVehicles = () => props.vehicles.filter((vehicle) => String(vehicle.customer_id) === String(form.customer_id));
const toggleService = (serviceId: number) => {
    form.services = form.services.includes(serviceId)
        ? form.services.filter((id: number) => id !== serviceId)
        : [...form.services, serviceId];
};
const updatePart = (partId: number, selected: boolean) => {
    form.parts = selected
        ? [...form.parts.filter((part: any) => part.id !== partId), { id: partId, quantity: 1 }]
        : form.parts.filter((part: any) => part.id !== partId);
};
const partQuantity = (partId: number) => form.parts.find((part: any) => part.id === partId)?.quantity ?? 1;
const submit = () => props.job ? form.put(`/jobs/${props.job.id}`) : form.post('/jobs');
</script>

<template>
    <Head :title="job ? 'Edit Job Card' : 'Create Job Card'" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader :title="job ? 'Edit Job Card' : 'Create Job Card'" description="Capture intake, assign a mechanic, and record work.">
            <Button as-child variant="outline"><Link href="/jobs">Back</Link></Button>
        </PageHeader>
        <form class="grid gap-4 rounded-2xl border bg-card p-5 lg:grid-cols-2" @submit.prevent="submit">
            <label class="space-y-2"><span class="text-sm">Customer</span><select v-model="form.customer_id" class="h-9 w-full rounded-md border bg-transparent px-3"><option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option></select></label>
            <label class="space-y-2"><span class="text-sm">Vehicle</span><select v-model="form.vehicle_id" class="h-9 w-full rounded-md border bg-transparent px-3"><option v-for="vehicle in visibleVehicles()" :key="vehicle.id" :value="vehicle.id">{{ vehicle.registration_number }}</option></select></label>
            <label class="space-y-2"><span class="text-sm">Mechanic</span><select v-model="form.mechanic_id" class="h-9 w-full rounded-md border bg-transparent px-3"><option value="">Unassigned</option><option v-for="mechanic in mechanics" :key="mechanic.id" :value="mechanic.id">{{ mechanic.name }}</option></select></label>
            <label class="space-y-2"><span class="text-sm">Estimated Delivery</span><Input v-model="form.estimated_delivery_at" type="datetime-local" /></label>
            <label class="space-y-2"><span class="text-sm">Priority</span><select v-model="form.priority" class="h-9 w-full rounded-md border bg-transparent px-3"><option v-for="priority in priorities" :key="priority" :value="priority">{{ priority }}</option></select></label>
            <label class="space-y-2"><span class="text-sm">Status</span><select v-model="form.status" class="h-9 w-full rounded-md border bg-transparent px-3"><option v-for="status in statuses" :key="status" :value="status">{{ status }}</option></select></label>
            <label class="space-y-2 lg:col-span-2"><span class="text-sm">Complaint</span><textarea v-model="form.complaint" class="min-h-24 w-full rounded-md border bg-transparent px-3 py-2" /></label>
            <label class="space-y-2"><span class="text-sm">Diagnosis</span><textarea v-model="form.diagnosis" class="min-h-24 w-full rounded-md border bg-transparent px-3 py-2" /></label>
            <label class="space-y-2"><span class="text-sm">Repair Notes</span><textarea v-model="form.repair_notes" class="min-h-24 w-full rounded-md border bg-transparent px-3 py-2" /></label>
            <label class="space-y-2"><span class="text-sm">Inspection Notes</span><textarea v-model="form.inspection_notes" class="min-h-24 w-full rounded-md border bg-transparent px-3 py-2" /></label>
            <label class="space-y-2"><span class="text-sm">Delivery Notes</span><textarea v-model="form.delivery_notes" class="min-h-24 w-full rounded-md border bg-transparent px-3 py-2" /></label>
            <label class="space-y-2"><span class="text-sm">Time Taken (mins)</span><Input v-model="form.time_taken_minutes" type="number" /></label>
            <div class="space-y-3">
                <span class="text-sm">Services</span>
                <label v-for="service in services" :key="service.id" class="flex items-center gap-2 rounded-xl border p-3">
                    <input :checked="form.services.includes(service.id)" type="checkbox" class="h-4 w-4" @change="toggleService(service.id)" />
                    <span>{{ service.name }} • INR {{ service.base_cost }}</span>
                </label>
            </div>
            <div class="space-y-3">
                <span class="text-sm">Parts</span>
                <div v-for="part in parts" :key="part.id" class="rounded-xl border p-3">
                    <label class="flex items-center gap-2">
                        <input :checked="form.parts.some((entry: any) => entry.id === part.id)" type="checkbox" class="h-4 w-4" @change="updatePart(part.id, ($event.target as HTMLInputElement).checked)" />
                        <span>{{ part.name }} • INR {{ part.selling_price }} • {{ part.quantity }} in stock</span>
                    </label>
                    <Input
                        v-if="form.parts.some((entry: any) => entry.id === part.id)"
                        :model-value="partQuantity(part.id)"
                        class="mt-3"
                        type="number"
                        @update:model-value="(value) => form.parts = form.parts.map((entry: any) => entry.id === part.id ? { ...entry, quantity: Number(value) } : entry)"
                    />
                </div>
            </div>
            <div class="lg:col-span-2"><Button type="submit">Save job card</Button></div>
        </form>
    </div>
</template>
