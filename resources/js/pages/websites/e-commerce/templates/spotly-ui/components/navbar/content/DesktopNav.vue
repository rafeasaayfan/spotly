<script setup lang="ts">
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';

import { useNavigation } from '@/composables/navigation/useNavigation';
import { navbarItems } from '@/pages/websites/e-commerce/config/navigations/navbar';
import type { SharedData } from '@/types';

import { Link, usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();

const { setupScrollTracking, scrollToSection, activeNavStyle, isCurrentRoute } = useNavigation(navbarItems);

setupScrollTracking();
</script>

<template>
    <div class="hidden h-full lg:flex lg:flex-1">
        <NavigationMenu class="mx-auto h-full">
            <NavigationMenuList class="h-full" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
                <NavigationMenuItem v-for="(item, index) in navbarItems" :key="index" class="flex h-16 items-center">
                    <button
                        v-if="item.href?.startsWith('#')"
                        type="button"
                        @click="scrollToSection(item.href)"
                        :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
                    >
                        <NavigationMenuLink
                            :class="[
                                navigationMenuTriggerStyle(),
                                'cursor-pointer flex-row items-center gap-2 bg-transparent px-3 hover:bg-[var(--bg_content_light)] dark:hover:bg-[var(--bg_content_dark)]',
                                activeNavStyle(item.href, 'web-bg-content-active web-text-active'),
                            ]"
                        >
                            <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
                            <span>{{ $t(item.title) }}</span>
                        </NavigationMenuLink>
                    </button>

                    <Link v-else :href="item.href ?? ''" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
                        <NavigationMenuLink
                            :class="[
                                navigationMenuTriggerStyle(),
                                'cursor-pointer flex-row items-center gap-2 bg-transparent px-3 hover:bg-[var(--bg_content_light)] dark:hover:bg-[var(--bg_content_dark)]',
                                activeNavStyle(item.href ?? '', 'web-bg-content-active web-text-active'),
                            ]"
                        >
                            <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
                            <span>{{ $t(item.title) }}</span>
                        </NavigationMenuLink>
                    </Link>

                    <div v-if="isCurrentRoute(item.href ?? '')" class="web-bg-primary absolute bottom-0 left-0 h-0.5 w-full"></div>
                </NavigationMenuItem>
            </NavigationMenuList>
        </NavigationMenu>
    </div>
</template>
