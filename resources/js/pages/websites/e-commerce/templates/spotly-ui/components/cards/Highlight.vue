<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
    text: string;
    type: string;
}>();
</script>

<template>
    <div
        :class="
            cn(
                'border-black/10 dark:border-white/10 w-fit rounded border bg-gradient-to-br px-2 py-1.5 text-xs font-bold',
                [
                    type === 'category'
                        ? 'from-black/8 via-transparent to-black/8 text-purple-800 dark:from-white/8 dark:to-white/8 dark:text-purple-400'
                        : 'from-transparent via-black/8 to-transparent text-emerald-800 dark:via-white/8 dark:text-emerald-400'
                ],
                props.class,
            )
        "
    >
        {{ props.text }}
    </div>
</template>
