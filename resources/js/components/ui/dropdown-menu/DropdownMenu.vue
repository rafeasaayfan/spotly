<script setup lang="ts">
import { DropdownMenuRoot, type DropdownMenuRootEmits, type DropdownMenuRootProps, useForwardPropsEmits } from 'reka-ui'
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<DropdownMenuRootProps>()
const emits = defineEmits<DropdownMenuRootEmits>()

const forwarded = useForwardPropsEmits(props, emits)

const page = usePage<SharedData>();
</script>

<template>
  <DropdownMenuRoot
    data-slot="dropdown-menu"
    v-bind="forwarded"
    :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
  >
    <slot />
  </DropdownMenuRoot>
</template>
