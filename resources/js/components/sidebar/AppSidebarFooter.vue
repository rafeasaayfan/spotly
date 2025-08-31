<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import CreatorLogoIcon from '../logo/CreatorLogoIcon.vue';
</script>

<template>
    <footer
        class="bg-nav border-muted absolute bottom-0 flex h-10 shrink-0 items-center justify-between gap-2 border-t px-2 md:px-4
        transition-[width,height] ease-linear z-10
        group-has-data-[collapsible=icon]/sidebar-wrapper:h-9
        w-full
        xl:group-has-data-[collapsible=offcanvas]/sidebar-wrapper:w-full"
    >
        <div class="flex items-center space-x-2">
            <Link :href="route('dashboard.index')">
                <CreatorLogoIcon class="size-12" />
            </Link>

            <span class="text-xs text-body-muted">@ All rights reserved</span>
        </div>

        <div>
            <span class="text-xs text-body-muted">Spotly / 2025</span>
        </div>
    </footer>
</template>
