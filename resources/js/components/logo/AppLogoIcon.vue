<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
    <svg viewBox="0 0 80 80" fill="none" :class="cn('w-12 h-12', props.class)" v-bind="$attrs">
        <defs>
            <linearGradient id="ringGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="var(--primary)" />
                <stop offset="100%" stop-color="var(--destructive)" />
            </linearGradient>
            <filter id="ringShadow" x="-10" y="-10" width="100" height="100">
                <feDropShadow dx="0" dy="2" stdDeviation="4" flood-color="var(--primary)" flood-opacity="0.18" />
            </filter>
        </defs>
        <!-- Gradient ring -->
        <circle cx="40" cy="40" r="30" stroke="url(#ringGradient)" stroke-width="10" fill="none" filter="url(#ringShadow)" />
        <!-- Centered S -->
        <text x="40" y="50" text-anchor="middle" font-family="Arial, sans-serif" font-size="32" font-weight="bold" fill="var(--foreground-active)">S</text>
    </svg>
</template>
