<script setup lang="ts">
import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import { useSidebar } from '@/components/ui/sidebar/utils'

const { state } = useSidebar()
</script>

<template>
    <div>
        <AppLogoIcon :class="state == 'collapsed' ? 'w-9 h-9' : ''" />
    </div>
    <div class="grid flex-1 text-start text-lg" :class="state == 'collapsed' ? 'hidden' : 'block'">
        <span class="text-active truncate font-bold leading-non">Spotly</span>
    </div>
</template>
