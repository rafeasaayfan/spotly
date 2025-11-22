<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import { computed } from 'vue';

const props = defineProps<{
    totalCount: number;
    currency?: string;
    title: string;
    withBorder?: boolean;
    icon?: string;
    active?: { count: number; title: string };
    inactive?: { count: number; title: string };
    banned?: { count: number; title: string };
}>();

const gridSize = computed(() => {
    return (['active', 'inactive', 'banned'] as const).filter((key) => (props as Record<string, any>)[key]).length;
});
</script>

<template>
    <div class="flex flex-col justify-between gap-4 px-4 py-4" :class="props.withBorder ? 'border-muted border-s border-t lg:border-t-0' : ''">
        <div class="flex items-center justify-between gap-3">
            <div class="flex flex-col gap-1">
                <span class="text-active text-2xl font-bold">{{ props.totalCount }} {{ props.currency }}</span>
                <span class="text-body-muted text-sm">{{ props.title }}</span>
            </div>

            <div v-if="props.icon" class="flex size-14 items-center justify-center rounded-full bg-black/5 dark:bg-white/5">
                <Icon :name="props.icon" class="size-6" />
            </div>
        </div>

        <div v-if="gridSize > 0" :class="['grid gap-2', gridSize === 1 ? 'grid-cols-1' : gridSize === 2 ? 'grid-cols-2' : 'grid-cols-3']">
            <div v-if="props.active" class="border-muted rounded border p-1">
                <div class="flex flex-col items-center rounded bg-green-500/10 py-2">
                    <span class="text-xs text-body font-medium">{{ props.active?.count }}</span>
                    <!-- <div class="h-2 w-full bg-green-500/80 rounded-full"></div> -->
                    <span class="text-green-500/80 text-[11px]">{{ props.active?.title }}</span>
                </div>
            </div>

            <div v-if="props.inactive" class="border-muted rounded border p-1">
                <div class="flex flex-col items-center rounded bg-yellow-500/10 py-2">
                    <span class="text-xs text-body font-medium">{{ props.inactive?.count }}</span>
                    <!-- <div class="h-2 w-full bg-yellow-500/80 rounded-full"></div> -->
                    <span class="text-yellow-500/80 text-[11px]">{{ props.inactive?.title }}</span>
                </div>
            </div>

            <div v-if="props.banned" class="border-muted rounded border p-1">
                <div class="flex flex-col items-center rounded bg-red-500/10 py-2">
                    <span class="text-xs text-body font-medium">{{ props.banned?.count }}</span>
                    <!-- <div class="h-2 w-full bg-red-500/80 rounded-full"></div> -->
                    <span class="text-red-500/80 text-[11px]">{{ props.banned?.title }}</span>
                </div>
            </div>
        </div>

        <slot />
    </div>
</template>
