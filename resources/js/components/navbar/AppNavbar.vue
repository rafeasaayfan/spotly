<script setup lang="ts">
import AppearenceBtn from '@/components/appearance/AppearanceBtn.vue';
import AuthAvatar from '@/components/AuthAvatar.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import LanguagesMenu from '@/components/languages/Languages.vue';
import AppLogo from '@/components/logo/AppLogo.vue';
import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import type { BreadcrumbItem } from '@/types';
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { Button } from '../ui/button';
import DesktopNav from './content/DesktopNav.vue';
import MobileNav from './content/MobileNav.vue';

const page = usePage<SharedData>();
const auth = computed(() => page.props.auth);

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const scrolled = ref(false);

const handleScroll = () => {
    scrolled.value = window.scrollY > 2;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <div :class="scrolled ? 'mt-0 bg-black/3 pt-0 backdrop-blur-lg dark:bg-white/3' : 'mt-2'" class="fixed z-20 w-full transition-all duration-300">
        <div
            :class="scrolled ? 'px-2 md:px-4' : 'px-2 md:px-10'"
            class="mx-auto flex h-16 items-center justify-between transition-all duration-300 md:max-w-7xl"
        >
            <!-- Mobile Menu -->
            <MobileNav />

            <Link :href="route('landing')" class="hidden items-center gap-2 md:flex">
                <AppLogo />
            </Link>

            <Link :href="route('landing')" class="flex items-center gap-2 md:hidden">
                <AppLogoIcon />
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

                <AuthAvatar v-if="auth.user" />
                <Link v-else :href="route('register')">
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
