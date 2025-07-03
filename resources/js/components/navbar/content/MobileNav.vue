<script setup lang="ts">
// import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import AppearanceTabs from '@/components/appearance/AppearanceTabs.vue';
import LanguagesTabs from '@/components/languages/LanguagesTabs.vue';

import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';

import { useNavigation } from '@/composables/navigation/useNavigation';
import { navbarItems } from '@/config/navigations';
import type { SharedData } from '@/types';

import { Link, usePage } from '@inertiajs/vue3';
import { Menu } from 'lucide-vue-next';

const page = usePage<SharedData>();

const { setupScrollTracking, scrollToSection, activeNavStyle } = useNavigation(navbarItems);

setupScrollTracking();
</script>

<template>
    <div class="lg:hidden">
        <Sheet>
            <SheetTrigger :as-child="true">
                <Button variant="ghost" size="icon">
                    <Menu class="!h-5 !w-5" />
                </Button>
            </SheetTrigger>

            <SheetContent :side="page.props.lang == 'ar' ? 'right' : 'left'" class="w-[260px] md:w-[320px]">
                <SheetTitle class="sr-only">Navigation Menu</SheetTitle>

                <SheetHeader class="border-muted flex border-b pb-10">
                    <!-- <AppLogoIcon class="size-12 bg-red-500" /> -->
                </SheetHeader>

                <div class="flex h-full flex-1 flex-col justify-between space-y-4 px-4 py-3">
                    <nav class="space-y-2">
                        <template v-for="item in navbarItems" :key="item.title">
                            <button
                                v-if="item.href?.startsWith('#')"
                                @click="scrollToSection(item.href)"
                                class="w-full bg-content-3 flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium"
                                :class="activeNavStyle(item.href)"
                                :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
                            >
                                <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                {{ item.title }}
                            </button>

                            <Link
                                v-else
                                :href="item.href ?? ''"
                                class="bg-content-3 flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium"
                                :class="activeNavStyle(item.href ?? '')"
                                :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
                            >
                                <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                {{ item.title }}
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
