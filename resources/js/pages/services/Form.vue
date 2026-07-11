<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PageHeader from '@/components/garage/PageHeader.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps<{ service: any | null }>();
const partsInput = ref((props.service?.required_parts ?? []).join(', '));
const form = useForm({
    name: props.service?.name ?? '',
    description: props.service?.description ?? '',
    estimated_minutes: props.service?.estimated_minutes ?? 60,
    base_cost: props.service?.base_cost ?? 0,
    required_parts: props.service?.required_parts ?? [],
    is_active: props.service?.is_active ?? true,
});
const submit = () => {
    form.required_parts = partsInput.value.split(',').map((value: string) => value.trim()).filter(Boolean);
    props.service ? form.put(`/services/${props.service.id}`) : form.post('/services');
};
</script>

<template>
    <Head :title="service ? 'Edit Service' : 'Add Service'" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader :title="service ? 'Edit Service' : 'Add Service'" description="Tune labour pricing, time estimates, and standard parts.">
            <Button as-child variant="outline"><Link href="/services">Back</Link></Button>
        </PageHeader>
        <form class="grid gap-4 rounded-2xl border bg-card p-5 md:grid-cols-2" @submit.prevent="submit">
            <label class="space-y-2"><span class="text-sm">Name</span><Input v-model="form.name" /></label>
            <label class="space-y-2"><span class="text-sm">Estimated Minutes</span><Input v-model="form.estimated_minutes" type="number" /></label>
            <label class="space-y-2"><span class="text-sm">Base Cost</span><Input v-model="form.base_cost" type="number" /></label>
            <label class="flex items-center gap-2 self-end"><input v-model="form.is_active" type="checkbox" class="h-4 w-4" /> Active service</label>
            <label class="space-y-2 md:col-span-2"><span class="text-sm">Description</span><textarea v-model="form.description" class="min-h-28 w-full rounded-md border bg-transparent px-3 py-2" /></label>
            <label class="space-y-2 md:col-span-2"><span class="text-sm">Required Parts</span><Input v-model="partsInput" placeholder="Engine Oil, Brake Pad Set" /></label>
            <div class="md:col-span-2"><Button type="submit">Save service</Button></div>
        </form>
    </div>
</template>
