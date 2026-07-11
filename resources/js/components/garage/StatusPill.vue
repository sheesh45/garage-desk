<script setup lang="ts">
const props = defineProps<{
    value: string;
}>();

const palette = {
    pending: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
    inspection: 'bg-sky-100 text-sky-700 dark:bg-sky-950 dark:text-sky-200',
    waiting_parts: 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-200',
    in_progress: 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-200',
    completed: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-200',
    delivered: 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900',
    paid: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-200',
    partial: 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-200',
    refunded: 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-200',
};

const label = props.value.replaceAll('_', ' ').replace(/\b\w/g, (char) =>
    char.toUpperCase(),
);
</script>

<template>
    <span
        class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
        :class="palette[value as keyof typeof palette] ?? 'bg-muted text-foreground'"
    >
        {{ label }}
    </span>
</template>
