<script setup lang="ts">
import AppearenceBtn from '@/components/appearance/AppearanceBtn.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import LanguagesMenu from '@/components/languages/Languages.vue';
// import AppLogo from '@/components/logo/AppLogo.vue';
// import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import type { BreadcrumbItem } from '@/types';
// import { Link } from '@inertiajs/vue3';
import DesktopNav from './content/DesktopNav.vue';
import MobileNav from './content/MobileNav.vue';
// import AuthAvatar from '@/components/AuthAvatar.vue';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="fixed z-20 w-full transition-all duration-300 bg-white/80 dark:bg-black/80 backdrop-blur-sm border-b border-muted shadow">
        <div
            class="mx-auto flex h-15 items-center justify-between transition-all duration-300 max-w-7xl px-4 md:px-0"
        >
            <!-- Mobile Menu -->
            <MobileNav />

            <!-- <Link :href="route('landing')" class="hidden items-center gap-2 md:flex">
                <AppLogo />
            </Link>

            <Link :href="route('landing')" class="flex items-center gap-2 md:hidden">
                <AppLogoIcon class="size-16" />
            </Link> -->

            <!-- Desktop Menu -->
            <DesktopNav />

            <div class="flex items-center space-x-2">
                <div class="hidden md:block">
                    <AppearenceBtn />
                </div>

                <div class="hidden md:block">
                    <LanguagesMenu />
                </div>

                <!-- <AuthAvatar /> -->
            </div>
        </div>
    </div>

    <div v-if="props.breadcrumbs.length > 1" class="border-muted flex w-full border-b">
        <div class="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 md:max-w-7xl">
            <Breadcrumbs :breadcrumbs="breadcrumbs" />
        </div>
    </div>
</template>
