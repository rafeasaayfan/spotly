<script setup lang="ts">
import { useSidebar } from '@/components/ui/sidebar/utils'
import CreatorLogoIcon from './CreatorLogoIcon.vue';

const { state } = useSidebar()
</script>

<template>
    <div>
        <CreatorLogoIcon :class="state == 'collapsed' ? 'size-9' : 'size-18'" />
    </div>
    <div class="grid flex-1 text-start text-sm" :class="state == 'collapsed' ? 'hidden' : 'block'">
        <span class="text-active truncate font-bold leading-none">VUE.RS</span>
    </div>
</template>
