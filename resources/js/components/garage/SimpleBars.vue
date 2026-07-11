<script setup lang="ts">
defineProps<{
    title: string;
    items: Array<{ label: string; value: number }>;
}>();
</script>

<template>
    <div class="rounded-2xl border bg-card p-5 shadow-sm">
        <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-muted-foreground">
            {{ title }}
        </h3>

        <div class="mt-5 space-y-4">
            <div v-for="item in items" :key="item.label" class="space-y-1.5">
                <div class="flex items-center justify-between text-sm">
                    <span>{{ item.label }}</span>
                    <span class="text-muted-foreground">{{ item.value }}</span>
                </div>
                <div class="h-2 rounded-full bg-muted">
                    <div
                        class="h-2 rounded-full bg-primary transition-all"
                        :style="{
                            width: `${Math.min(
                                100,
                                items.length ? (item.value / Math.max(...items.map((entry) => entry.value), 1)) * 100 : 0,
                            )}%`,
                        }"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
