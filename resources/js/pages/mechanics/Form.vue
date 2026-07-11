<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PageHeader from '@/components/garage/PageHeader.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps<{ mechanic: any | null }>();
const form = useForm({
    name: props.mechanic?.name ?? '',
    phone: props.mechanic?.phone ?? '',
    skills: props.mechanic?.skills ?? [],
    experience_years: props.mechanic?.experience_years ?? 0,
    is_active: props.mechanic?.is_active ?? true,
});
const skillsInput = ref((form.skills ?? []).join(', '));
const submit = () => {
    form.skills = String(skillsInput.value)
        .split(',')
        .map((value) => value.trim())
        .filter(Boolean);
    props.mechanic ? form.put(`/mechanics/${props.mechanic.id}`) : form.post('/mechanics');
};
</script>

<template>
    <Head :title="mechanic ? 'Edit Mechanic' : 'Add Mechanic'" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader :title="mechanic ? 'Edit Mechanic' : 'Add Mechanic'" description="Manage workshop talent and assignment readiness.">
            <Button as-child variant="outline"><Link href="/mechanics">Back</Link></Button>
        </PageHeader>
        <form class="grid gap-4 rounded-2xl border bg-card p-5 md:grid-cols-2" @submit.prevent="submit">
            <label class="space-y-2"><span class="text-sm">Name</span><Input v-model="form.name" /></label>
            <label class="space-y-2"><span class="text-sm">Phone</span><Input v-model="form.phone" /></label>
            <label class="space-y-2 md:col-span-2"><span class="text-sm">Skills</span><Input v-model="skillsInput" placeholder="Engine Repair, Electrical, Alignment" /></label>
            <label class="space-y-2"><span class="text-sm">Experience Years</span><Input v-model="form.experience_years" type="number" /></label>
            <label class="flex items-center gap-2 self-end"><input v-model="form.is_active" type="checkbox" class="h-4 w-4" /> Active mechanic</label>
            <div class="md:col-span-2"><Button type="submit">Save mechanic</Button></div>
        </form>
    </div>
</template>
