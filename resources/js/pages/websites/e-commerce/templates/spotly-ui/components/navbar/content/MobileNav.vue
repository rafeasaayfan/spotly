<script setup lang="ts">
import AppearanceTabs from '../../appearance/AppearanceTabs.vue';
import LanguagesTabs from '../../languages/LanguagesTabs.vue';

import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';

import { useNavigation } from '@/composables/navigation/useNavigation';
import { navbarItems } from '../../../../../config/navigations/navbar';
import type { SharedData } from '@/types';

import { Link, usePage } from '@inertiajs/vue3';
import { Menu } from 'lucide-vue-next';

const page = usePage<SharedData>();

const { setupScrollTracking, scrollToSection, activeNavStyle } = useNavigation(navbarItems);

setupScrollTracking();

defineProps<{
    websiteNameAndLogo: Record<string, string>;
}>();
</script>

<template>
    <div class="lg:hidden">
        <Sheet>
            <SheetTrigger :as-child="true">
                <Button variant="ghost" size="icon" class="web-bg-content web-text-body-muted">
                    <Menu class="!h-5 !w-5" />
                </Button>
            </SheetTrigger>

            <SheetContent
                :side="page.props.lang == 'ar' ? 'right' : 'left'"
                class="web-bg-dropdown web-border-color w-[260px] backdrop-blur-xl md:w-[320px]"
            >
                <SheetTitle class="sr-only">Navigation Menu</SheetTitle>

                <SheetHeader class="web-border-color flex border-b pb-3">
                    <Link :href="route('website.e-commerce.home')" class="cursor-pointer items-center gap-3">
                        <img
                            v-if="websiteNameAndLogo.light_logo"
                            :src="websiteNameAndLogo.light_logo"
                            class="block size-12 rounded-full dark:hidden"
                        />
                        <img v-if="websiteNameAndLogo.dark_logo" :src="websiteNameAndLogo.dark_logo" class="hidden size-12 rounded-full dark:block" />
                        <span v-if="!websiteNameAndLogo.dark_logo || !websiteNameAndLogo.light_logo" class="web-text-active leading-non truncate text-base font-bold">
                            {{ websiteNameAndLogo.name }}
                        </span>
                    </Link>
                </SheetHeader>

                <div class="flex h-full flex-1 flex-col justify-between space-y-4 px-4 py-3">
                    <nav class="space-y-2">
                        <template v-for="item in navbarItems" :key="item.title">
                            <button
                                v-if="item.href?.startsWith('#')"
                                @click="scrollToSection(item.href)"
                                class="bg-transparent hover:bg-[var(--bg_content_light)] dark:hover:bg-[var(--bg_content_dark)] web-text-body flex w-full items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium"
                                :class="activeNavStyle(item.href, 'web-bg-content-active web-text-active')"
                                :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
                            >
                                <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                {{ $t(item.title) }}
                            </button>

                            <Link
                                v-else
                                :href="item.href ?? ''"
                                class="bg-transparent hover:bg-[var(--bg_content_light)] dark:hover:bg-[var(--bg_content_dark)] web-text-body flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium"
                                :class="activeNavStyle(item.href ?? '', 'web-bg-content-active web-text-active')"
                                :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
                            >
                                <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                {{ $t(item.title) }}
                            </Link>
                        </template>
                    </nav>

                    <div class="flex flex-col gap-3">
                        <LanguagesTabs />
                        <AppearanceTabs />
                    </div>
                </div>
            </SheetContent>
        </Sheet>
    </div>
</template>
