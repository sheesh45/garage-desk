<script setup lang="ts">
defineProps<{
    title: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-semibold tracking-tight">{{ title }}</h1>
            <p v-if="description" class="text-sm text-muted-foreground">
                {{ description }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <slot />
        </div>
    </div>
</template>
