<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue'
import { cn } from '@/lib/utils'
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
  class?: HTMLAttributes['class']
  isActive?: boolean
}>()

const page = usePage<SharedData>();

const lang = computed(() => page.props.lang)
</script>

<template>
  <div
    data-slot="sidebar-menu-badge"
    data-sidebar="menu-badge"
    :class="cn(
      'pointer-events-none absolute flex h-5 min-w-5 items-center justify-center rounded-md px-1 text-xs font-medium tabular-nums select-none',
      'peer-data-[size=sm]/menu-button:top-1',
      'peer-data-[size=default]/menu-button:top-1.5',
      'peer-data-[size=lg]/menu-button:top-2.5',
      'group-data-[collapsible=icon]:hidden',
      lang == 'ar' ? 'left-1' : 'right-1',
      props.isActive ? 'text-active-link font-semibold' : 'text-body',
      props.class,
    )"
  >
    <slot />
  </div>
</template>
