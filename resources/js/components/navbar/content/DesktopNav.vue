<script setup lang="ts">
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';

import { useNavigation } from '@/composables/navigation/useNavigation';
import { navbarItems } from '@/config/navigations';
import type { SharedData } from '@/types';

import { Link, usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();

const {
  setupScrollTracking,
  scrollToSection,
  activeNavStyle,
  isCurrentRoute
} = useNavigation(navbarItems);

setupScrollTracking();
</script>

<template>
    <div class="hidden h-full lg:flex lg:flex-1">
        <NavigationMenu class="mx-auto h-full">
            <NavigationMenuList class="h-full" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
                <NavigationMenuItem v-for="(item, index) in navbarItems" :key="index" class="flex h-16 items-center">
                    <button v-if="item.href.startsWith('#')" type="button" @click="scrollToSection(item.href)" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
                        <NavigationMenuLink
                            :class="[navigationMenuTriggerStyle(), activeNavStyle(item.href), 'cursor-pointer flex-row items-center gap-2 px-3']"
                        >
                            <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
                            <span>{{ item.title }}</span>
                        </NavigationMenuLink>
                    </button>

                    <Link v-else :href="item.href" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
                        <NavigationMenuLink
                            :class="[navigationMenuTriggerStyle(), activeNavStyle(item.href), 'cursor-pointer flex-row items-center gap-2 px-3']"
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
