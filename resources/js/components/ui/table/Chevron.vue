<script setup lang="ts">
import { HTMLAttributes, computed } from 'vue'
import { cn } from '@/lib/utils'
import { ChevronUpIcon, ChevronDownIcon } from 'lucide-vue-next'

const props = defineProps<{
  class?: HTMLAttributes['class']
  sortKey: string
  currentSort: { key: string, direction: 'asc' | 'desc' }
}>()

const isActive = computed(() => props.currentSort.key === props.sortKey)
</script>

<template>
  <span :class="cn('flex flex-col items-center gap-[0.1px]', props.class)">
    <ChevronUpIcon
      class="size-2.5"
      :class="[
        isActive && props.currentSort.direction === 'asc' ? 'text-black dark:text-blue-600' : 'opacity-30'
      ]"
    />
    <ChevronDownIcon
      class="size-2.5 -mt-1"
      :class="[
        isActive && props.currentSort.direction === 'desc' ? 'text-black dark:text-blue-600' : 'opacity-30'
      ]"
    />
  </span>
</template>
