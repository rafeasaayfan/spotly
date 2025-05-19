<script setup lang="ts">
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import { navbarItems } from '@/config/navigations';
import type { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage<SharedData>();

const isCurrentRoute = computed(() => (url: string) => page.url === url);

const activeItemStyles = computed(
    () => (url: string) => (isCurrentRoute.value(url) ? 'text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100' : ''),
);
</script>

<template>
    <div class="hidden h-full lg:flex lg:flex-1">
        <NavigationMenu class="mx-auto h-full">
            <NavigationMenuList class="h-full" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
                <NavigationMenuItem v-for="(item, index) in navbarItems" :key="index" class="flex h-16 items-center">
                    <Link :href="item.href" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
                        <NavigationMenuLink
                            :class="[navigationMenuTriggerStyle(), activeItemStyles(item.href), 'cursor-pointer flex-row items-center gap-2 px-3']"
                        >
                            <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
                            <span>{{ item.title }}</span>
                        </NavigationMenuLink>
                    </Link>
                    <div v-if="isCurrentRoute(item.href)" class="absolute bottom-0 left-0 h-0.5 w-full translate-y-px bg-black dark:bg-white"></div>
                </NavigationMenuItem>
            </NavigationMenuList>
        </NavigationMenu>
    </div>
</template>
