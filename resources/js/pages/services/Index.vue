<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/components/garage/PageHeader.vue';
import PaginationNav from '@/components/garage/PaginationNav.vue';
import Button from '@/components/ui/button/Button.vue';

defineProps<{ services: { data: Array<any>; links: Array<any> } }>();
</script>

<template>
    <Head title="Services" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader title="Service Catalogue" description="Define labour packages, timing, and default pricing.">
            <Button as-child><Link href="/services/create">Add service</Link></Button>
        </PageHeader>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <div v-for="service in services.data" :key="service.id" class="rounded-2xl border bg-card p-5">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold">{{ service.name }}</h3>
                    <Button as-child variant="outline" size="sm"><Link :href="`/services/${service.id}/edit`">Edit</Link></Button>
                </div>
                <p class="mt-2 text-sm text-muted-foreground">{{ service.description }}</p>
                <div class="mt-4 flex gap-3 text-sm">
                    <span>{{ service.estimated_minutes }} mins</span>
                    <span>INR {{ service.base_cost }}</span>
                </div>
            </div>
        </div>
        <PaginationNav :links="services.links" />
    </div>
</template>
