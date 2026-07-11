<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 64 64"
        :class="className"
        v-bind="$attrs"
    >
        <defs>
            <linearGradient id="garageflow-mark" x1="12" y1="14" x2="52" y2="50" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="currentColor" stop-opacity="0.98" />
                <stop offset="1" stop-color="currentColor" stop-opacity="0.82" />
            </linearGradient>
        </defs>
        <path
            d="M18 24.5 24.5 16h15L46 24.5V42a4 4 0 0 1-4 4H22a4 4 0 0 1-4-4V24.5Z"
            fill="url(#garageflow-mark)"
        />
        <path
            d="M22 24h20"
            stroke="white"
            stroke-width="3"
            stroke-linecap="round"
            opacity="0.92"
        />
        <path
            d="M25 24 29.2 18h5.6L39 24"
            stroke="white"
            stroke-width="3"
            stroke-linecap="round"
            stroke-linejoin="round"
            opacity="0.95"
        />
        <circle cx="25.5" cy="38.5" r="4.5" fill="white" opacity="0.96" />
        <circle cx="38.5" cy="38.5" r="4.5" fill="white" opacity="0.96" />
        <path
            d="M32 30v11"
            stroke="white"
            stroke-width="3.2"
            stroke-linecap="round"
        />
        <path
            d="M26.5 35.5H37.5"
            stroke="white"
            stroke-width="3.2"
            stroke-linecap="round"
        />
    </svg>
</template>
