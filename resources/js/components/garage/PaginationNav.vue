<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    links: Array<{ url: string | null; label: string; active: boolean }>;
}>();
</script>

<template>
    <div class="flex flex-wrap gap-2">
        <Link
            v-for="link in links"
            :key="link.label"
            :href="link.url ?? '#'"
            class="rounded-md border px-3 py-1.5 text-sm transition"
            :class="link.active ? 'border-primary bg-primary text-primary-foreground' : 'border-border text-muted-foreground'"
            :preserve-scroll="true"
        >
            <span v-html="link.label" />
        </Link>
    </div>
</template>
