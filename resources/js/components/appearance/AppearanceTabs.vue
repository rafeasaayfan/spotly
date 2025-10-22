<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Monitor, Moon, Sun } from 'lucide-vue-next';

const page = usePage<SharedData>();

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'appearance.light' },
    { value: 'dark', Icon: Moon, label: 'appearance.dark' },
    { value: 'system', Icon: Monitor, label: 'appearance.system' },
] as const;
</script>

<template>
    <div
        class="border-muted bg-body flex max-w-full items-center justify-between gap-2 overflow-x-auto rounded border-4 border-double"
        :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
    >
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            @click="updateAppearance(value)"
            :class="[
                'text-body-muted flex cursor-pointer items-center px-2 md:px-3.5 py-1.5 transition-colors',
                appearance === value ? 'bg-primary text-for-bg-primary font-semibold shadow-xs' : 'bg-content-3',
            ]"
            :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
        >
            <div class="flex items-center gap-1 md:gap-2" :class="appearance === value ? 'text-white' : ''">
                <component :is="Icon" class="w-3 h-3 md:h-4 md:w-4" />
                <span class="text-xs md:text-sm">{{ $t(label) }}</span>
            </div>
        </button>
    </div>
</template>
