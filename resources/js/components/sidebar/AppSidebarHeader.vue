<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import AppearenceBtn from '@/components/appearance/AppearanceBtn.vue';
import LanguagesMenu from '@/components/languages/Languages.vue';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <header
        class="bg-nav border-muted fixed top-0 w-full flex h-16 shrink-0 items-center justify-between gap-2 border-b px-2 md:px-4
        transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-14 z-20
        max-w-full xl:max-w-[calc(100%-16rem)] xl:group-has-data-[collapsible=icon]/sidebar-wrapper:max-w-[calc(100%-3rem)]
        xl:group-has-data-[collapsible=offcanvas]/sidebar-wrapper:max-w-full"
    >

        <div class="flex items-center gap-2">
            <SidebarTrigger />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <div class="flex items-center space-x-1 sm:space-x-2">
            <AppearenceBtn />

            <LanguagesMenu />
        </div>

    </header>
</template>
