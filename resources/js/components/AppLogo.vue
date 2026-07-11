<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div
        class="flex size-10 items-center justify-center rounded-2xl bg-[linear-gradient(145deg,var(--color-primary),color-mix(in_oklab,var(--color-primary)_72%,white))] text-sidebar-primary-foreground shadow-[0_10px_24px_rgba(249,115,22,0.22)]"
    >
        <AppLogoIcon class="size-6 fill-current text-white dark:text-slate-950" />
    </div>
    <div class="grid flex-1 text-left text-sm">
        <span class="truncate text-base leading-tight font-semibold">Garage360</span>
    </div>
</template>
