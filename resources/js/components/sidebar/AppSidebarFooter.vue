<script setup lang="ts">
import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer
        class="bg-navs border-muted bottom-0 flex h-10 shrink-0 items-center justify-between gap-2 border-t px-6
        transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-9 md:px-4"
    >
        <div class="flex items-center space-x-2">
            <Link :href="route('dashboard.index')">
                <AppLogoIcon class="size-10" />
            </Link>

            <span class="text-xs text-body-muted">@ All rights reserved</span>
        </div>

        <div>
            <span class="text-xs text-body-muted">VUE.RS / 2025</span>
        </div>
    </footer>
</template>
