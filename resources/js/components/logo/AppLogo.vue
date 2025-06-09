<script setup lang="ts">
import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import { useSidebar } from '@/components/ui/sidebar/utils'

const { state } = useSidebar()
</script>

<template>
    <!-- <div>
        <AppLogoIcon :class="state == 'collapsed' ? 'size-9' : 'size-18'" />
    </div> -->
    <div class="grid flex-1 text-start text-sm" :class="state == 'collapsed' ? 'hidden' : 'block'">
        <span class="text-lg font-semibold text-active font-bold leading-none">Spotly</span>
    </div>
</template>
