<script setup lang="ts">
import AppearenceBtn from '../appearance/AppearanceBtn.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import LanguagesMenu from '../languages/Languages.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import AuthAvatar from '../user/AuthAvatar.vue';
import { Button } from '@/components/ui/button';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import DesktopNav from './content/DesktopNav.vue';
import MobileNav from './content/MobileNav.vue';
import { ShoppingCart } from 'lucide-vue-next';

const page = usePage<SharedData>();
const auth = computed(() => page.props.auth);

interface Props {
    breadcrumbs?: BreadcrumbItem[];
    websiteNameAndLogo: Record<string, string>;
    cartItemsCount: number;
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

const isCurrentRoute = computed(() => (url: string) => page.url === url);
</script>

<template>
    <div
        :class="scrolled ? 'web-bg-nav mt-0 md:mt-0 pt-0 backdrop-blur-lg' : 'mt-0 md:mt-2'"
        class="fixed z-20 w-full transition-all duration-300"
        :dir="page.props.lang === 'ar' ? 'rtl' : 'ltr'"
    >
        <div
            :class="scrolled ? 'px-2 md:px-4' : 'px-2 md:px-10'"
            class="mx-auto flex h-16 items-center justify-between transition-all duration-300 md:max-w-7xl"
        >
            <!-- Mobile Menu -->
            <MobileNav :websiteNameAndLogo="props.websiteNameAndLogo" />

            <Link :href="route('website.e-commerce.home')" class="hidden cursor-pointer items-center gap-3 md:flex">
                <img
                    v-if="props.websiteNameAndLogo.light_logo"
                    :src="props.websiteNameAndLogo.light_logo"
                    class="block size-12 rounded-full dark:hidden"
                />
                <img
                    v-if="props.websiteNameAndLogo.dark_logo"
                    :src="props.websiteNameAndLogo.dark_logo"
                    class="hidden size-12 rounded-full dark:block"
                />
                <span class="web-text-active leading-non truncate text-lg font-bold">{{ props.websiteNameAndLogo.name }}</span>
            </Link>

            <!-- Desktop Menu -->
            <DesktopNav />

            <div class="flex items-center space-x-2">
                <Link href="/cart" class="relative cursor-pointer rounded-full 
                    p-2 hover:-translate-y-0.5 transition-all duration-300"
                    :class="isCurrentRoute('/cart') ? 'web-text-active web-bg-content-active' : 'web-text-body-muted web-bg-content'"
                >
                    <div v-if="props.cartItemsCount > 0" class="absolute -top-1.5 -right-0.5 text-[10px] web-text-for-danger 
                        web-bg-danger rounded-full size-4.5 flex items-center justify-center"
                    >
                        {{ props.cartItemsCount }}
                    </div>
                    <ShoppingCart class="size-5" />
                </Link>

                <div class="hidden md:block">
                    <AppearenceBtn />
                </div>

                <div class="hidden md:block">
                    <LanguagesMenu />
                </div>

                <AuthAvatar v-if="auth.website_user" />
                <Link v-else :href="route('website.register')">
                    <Button variant="outline" size="sm" class="web-bg-content web-border-color web-text-body">
                        {{ $t('navbar.login.register') }}
                    </Button>
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
