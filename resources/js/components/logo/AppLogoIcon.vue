<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';
import { computed } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();

// unique suffix so multiple instances won't clash on the same page
const uid = `logo_${Math.random().toString(36).slice(2, 8)}`;

const svgClass = computed(() =>
    cn(
        'block h-12 w-12',
        props.class,
    ),
);
</script>

<template>
    <svg viewBox="0 0 80 80" width="80" height="80" preserveAspectRatio="xMidYMid meet" overflow="visible" :class="svgClass" v-bind="$attrs">
        <defs>
            <linearGradient :id="`ringGradient-${uid}`" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="40%" :stop-color="'var(--primary)'" />
                <stop offset="50%" :stop-color="'var(--primary-hover)'" />
                <stop offset="60%" :stop-color="'var(--primary-active)'" />
                <stop offset="80%" :stop-color="'var(--primary-active)'" />
                <stop offset="100%" :stop-color="'var(--background)'" />
            </linearGradient>

            <filter
                :id="`ringShadow-${uid}`"
                x="-50%"
                y="-50%"
                width="200%"
                height="200%"
                filterUnits="userSpaceOnUse"
                primitiveUnits="userSpaceOnUse"
            >
                <feDropShadow dx="0" dy="2" stdDeviation="4" :flood-color="'var(--primary)'" flood-opacity="0.2" />
            </filter>
        </defs>

        <!-- Gradient ring -->
        <circle cx="40" cy="40" r="30" :stroke="`url(#ringGradient-${uid})`" stroke-width="10" fill="none" :filter="`url(#ringShadow-${uid})`" />

        <!-- Centered S -->
        <text x="40" y="50" text-anchor="middle" font-family="Arial, sans-serif" font-size="32" font-weight="bold" fill="var(--foreground-active)">
            {{ $t('s') }}
        </text>
    </svg>
</template>