<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/components/garage/PageHeader.vue';
import PaginationNav from '@/components/garage/PaginationNav.vue';
import Button from '@/components/ui/button/Button.vue';

defineProps<{ mechanics: { data: Array<any>; links: Array<any> } }>();
</script>

<template>
    <Head title="Mechanics" />
    <div class="space-y-6 p-4 md:p-6">
        <PageHeader title="Mechanics" description="Track skills, assignment load, and output quality.">
            <Button as-child><Link href="/mechanics/create">Add mechanic</Link></Button>
        </PageHeader>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <div v-for="mechanic in mechanics.data" :key="mechanic.id" class="rounded-2xl border bg-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold">{{ mechanic.name }}</h3>
                        <p class="text-sm text-muted-foreground">{{ mechanic.phone }}</p>
                    </div>
                    <Button as-child variant="outline" size="sm"><Link :href="`/mechanics/${mechanic.id}/edit`">Edit</Link></Button>
                </div>
                <p class="mt-4 text-sm text-muted-foreground">{{ (mechanic.skills ?? []).join(', ') }}</p>
                <div class="mt-4 flex gap-3 text-sm">
                    <span>{{ mechanic.job_cards_count }} assigned</span>
                    <span>{{ mechanic.completed_jobs_count }} completed</span>
                </div>
            </div>
        </div>
        <PaginationNav :links="mechanics.links" />
    </div>
</template>
