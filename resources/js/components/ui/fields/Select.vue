<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
  active?: boolean,
  class?: HTMLAttributes['class']
  name?: string,
  id?: string,
  option?: string,
}>()
</script>

<template>
    <select :name="props.name" :id="props.id"
    :class="
      cn(
        'bg-slate-300 hover:bg-slate-200 dark:bg-slate-900 dark:hover:bg-slate-800',
        'placeholder:text-slate-800 dark:placeholder:text-slate-200 text-slate-900 dark:text-slate-100 text-sm',
        'min-w-18 w-full p-2 border-none rounded-md cursor-pointer duration-300 focus:border-blue-900 dark:focus:border-blue-600',
        props.class,
      )
    ">
        <option disabled selected>{{ props.option ?? 'Seletc an option' }}</option>
        <slot />
    </select>
</template>
