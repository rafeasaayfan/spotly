<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const page = usePage<SharedData>();

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <td
    data-slot="td"
    :class="
      cn(
        'py-3 border-slate-500/10 dark:border-slate-600/10',
        page.props.lang == 'ar' ? 'text-right border-l px-3' : 'text-left border-r px-3',
        props.class,
      )
    "
  >
    <slot />
  </td>
</template>
