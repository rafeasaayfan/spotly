<script setup lang="ts">
import AppearenceBtn from '@/components/appearance/AppearanceBtn.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import LanguagesMenu from '@/components/languages/Languages.vue';
// import AppLogo from '@/components/logo/AppLogo.vue';
// import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
// import { Link } from '@inertiajs/vue3';
import DesktopNav from './content/DesktopNav.vue';
import MobileNav from './content/MobileNav.vue';
import AuthAvatar from '@/components/AuthAvatar.vue';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage<SharedData>();
const auth = computed(() => page.props.auth);
</script>

<template>
    <div
        class="fixed z-20 w-full transition-all duration-300
        backdrop-blur-md bg-black/3 dark:bg-white/3"
        :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
    >
        <div
            class="mx-auto flex h-15 items-center justify-between transition-all duration-300 max-w-7xl px-4 md:px-10 lg:px-4"
        >
            <!-- Mobile Menu -->
            <MobileNav />

            <Link :href="route('landing')" class="hidden items-center gap-2 md:flex cursor-pointer">
                <!-- <AppLogo /> -->
                <span class="gradient-text text-xl font-bold">E-commerce</span>
            </Link>

            <!-- <Link :href="route('landing')" class="flex items-center gap-2 md:hidden">
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

                <AuthAvatar v-if="auth.website_user" />
                <Link v-else href="/register">
                    <Button variant="outline" size="sm"> Login / Register </Button>
                </Link>
            </div>
        </div>
    </div>

    <div v-if="props.breadcrumbs.length > 1" class="border-muted flex w-full border-b">
        <div class="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 md:max-w-7xl">
            <Breadcrumbs :breadcrumbs="breadcrumbs" />
        </div>
    </div>
</template>
