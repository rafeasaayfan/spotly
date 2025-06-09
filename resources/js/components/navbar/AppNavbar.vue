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
import { onMounted, onUnmounted, ref } from 'vue';
// import AuthAvatar from '@/components/AuthAvatar.vue';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const scrolled = ref(false);

const handleScroll = () => {
  scrolled.value = window.scrollY > 16;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <div :class="scrolled ? 'fixed w-full pt-0 backdrop-blur-lg mt-0 bg-black/5 dark:bg-white/5' : ' mt-2'"
    class="transition-all duration-300 z-20">
        <div :class="scrolled ? 'px-4' : 'px-4 md:px-10'"
            class="mx-auto flex h-16 items-center md:max-w-7xl justify-between">
            <!-- Mobile Menu -->
            <MobileNav />

            <Link :href="route('landing')" class="hidden md:flex items-center gap-2">
                <AppLogo  />
            </Link>

            <Link :href="route('landing')" class="flex md:hidden items-center gap-2">
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
