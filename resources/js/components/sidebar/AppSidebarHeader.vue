<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import AppearenceBtn from '@/components/appearance/AppearanceBtn.vue';
import LanguagesMenu from '@/components/languages/Languages.vue';
import AuthAvatar from '@/components/AuthAvatar.vue';

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
        class="bg-navs border-muted fixed top-0 w-full flex h-16 shrink-0 items-center justify-between gap-2 border-b px-6
        transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-14 md:px-4 z-10
        max-w-full xl:max-w-[calc(100%-16rem)] xl:group-has-data-[collapsible=icon]/sidebar-wrapper:max-w-[calc(100%-3rem)]
        xl:group-has-data-[collapsible=offcanvas]/sidebar-wrapper:max-w-full"
    >

        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <div class="flex items-center space-x-2">
            <div class="hidden md:block">
                <AppearenceBtn />
            </div>

            <div class="hidden md:block">
                <LanguagesMenu />
            </div>

            <AuthAvatar />
        </div>

    </header>
</template>
