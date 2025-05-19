<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import AppLogo from '@/components/logo/AppLogo.vue';
import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import type { BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import AppearenceBtn from '@/components/appearance/AppearanceBtn.vue';
import LanguagesMenu from '@/components/languages/Languages.vue';
import MobileNav from './content/MobileNav.vue';
import DesktopNav from './content/DesktopNav.vue';
import AuthAvatar from '@/components/AuthAvatar.vue';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="border-sidebar-border/80 dark:border-sidebar-border/30 border-b">
        <div class="mx-auto flex h-16 items-center px-4 md:max-w-7xl justify-between">
            <!-- Mobile Menu -->
            <MobileNav />

            <Link :href="route('dashboard.index')" class="hidden md:flex items-center gap-2">
                <AppLogo />
            </Link>

            <Link :href="route('dashboard.index')" class="flex md:hidden items-center gap-2">
                <AppLogoIcon class="size-16" />
            </Link>

            <!-- Desktop Menu -->
            <DesktopNav />

            <div class="flex items-center space-x-2">
                <div class="hidden md:block">
                    <AppearenceBtn />
                </div>

                <div class="hidden md:block">
                    <LanguagesMenu />
                </div>

                <AuthAvatar />
            </div>
        </div>
    </div>

    <div v-if="props.breadcrumbs.length > 1" class="border-sidebar-border/70 flex w-full border-b">
        <div class="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 md:max-w-7xl">
            <Breadcrumbs :breadcrumbs="breadcrumbs" />
        </div>
    </div>
</template>
