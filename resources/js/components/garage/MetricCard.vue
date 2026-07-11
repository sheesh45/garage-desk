<script setup lang="ts">
defineProps<{
    label: string;
    value: string | number;
    tone?: 'default' | 'success' | 'warning';
}>();
</script>

<template>
    <div
        class="rounded-2xl border border-border/70 bg-card/90 p-5 shadow-sm"
        :class="{
            'bg-emerald-50/60 dark:bg-emerald-950/20': tone === 'success',
            'bg-amber-50/70 dark:bg-amber-950/20': tone === 'warning',
        }"
    >
        <p class="text-sm text-muted-foreground">{{ label }}</p>
        <p class="mt-2 text-3xl font-semibold tracking-tight">{{ value }}</p>
    </div>
</template>
