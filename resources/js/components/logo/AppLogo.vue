<script setup lang="ts">
import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import { useSidebar } from '@/components/ui/sidebar/utils'

const { state } = useSidebar()
</script>

<template>
    <div>
        <AppLogoIcon :class="state == 'collapsed' ? 'size-9' : 'size-18'" />
    </div>
    <div class="grid flex-1 text-start text-md" :class="state == 'collapsed' ? 'hidden' : 'block'">
        <span class="text-active truncate font-bold leading-none">VUE.RS</span>
    </div>
</template>
