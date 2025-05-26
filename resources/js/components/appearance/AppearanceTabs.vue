<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Monitor, Moon, Sun } from 'lucide-vue-next';

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'Light' },
    { value: 'dark', Icon: Moon, label: 'Dark' },
    { value: 'system', Icon: Monitor, label: 'System' },
] as const;
</script>

<template>
    <div class="inline-flex gap-2 rounded-md p-1 bg-card">
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            @click="updateAppearance(value)"
            :class="[
                'flex items-center rounded-md px-2 md:px-3.5 py-1.5 transition-colors cursor-pointer text-body-muted',
                appearance === value
                    ? 'bg-primary shadow-xs text-for-bg-primary font-semibold'
                    : 'bg-content-3',
            ]"
        >
            <div class="flex items-center gap-1 md:gap-2">
                <component :is="Icon" class="w-3 h-3 md:h-4 md:w-4" />
                <span class="text-xs md:text-sm">{{ label }}</span>
            </div>
        </button>
    </div>
</template>
