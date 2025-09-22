<script lang="ts" setup>
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { ChevronRight } from 'lucide-vue-next'
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()

const page = usePage<SharedData>();
</script>

<template>
  <li
    data-slot="breadcrumb-separator"
    role="presentation"
    aria-hidden="true"
    :class="cn('[&>svg]:size-3.5', props.class)"
  >
    <slot>
      <ChevronRight :class="page.props.lang === 'ar' ? 'rotate-180' : ''" />
    </slot>
  </li>
</template>
