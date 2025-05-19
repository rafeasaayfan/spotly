<script setup lang="ts">
// import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import LanguagesMenu from '@/components/languages/Languages.vue';
import AppearanceTabs from '@/components/appearance/AppearanceTabs.vue';
import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { navbarItems } from '@/config/navigations';
import type { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu } from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage<SharedData>();

const isCurrentRoute = computed(() => (url: string) => page.url === url);

const activeItemStyles = computed(
    () => (url: string) => (isCurrentRoute.value(url) ? 'text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100' : ''),
);
</script>

<template>
    <div class="lg:hidden">
        <Sheet>
            <SheetTrigger :as-child="true">
                <Button variant="ghost" size="icon" class="bg-gray-500/30">
                    <Menu class="!h-5 !w-5" />
                </Button>
            </SheetTrigger>
            <SheetContent :side="page.props.lang == 'ar' ? 'right' : 'left'" class="w-[260px]">
                <SheetTitle class="sr-only">Navigation Menu</SheetTitle>
                <SheetHeader class="flex border-b border-gray-500/10 pb-10">
                    <!-- <AppLogoIcon class="size-12 bg-red-500" /> -->
                </SheetHeader>
                <div class="flex h-full flex-1 flex-col justify-between space-y-4 py-3 px-4">
                    <nav class="space-y-2">
                        <Link
                            v-for="item in navbarItems"
                            :key="item.title"
                            :href="item.href"
                            class="hover:bg-accent flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium"
                            :class="activeItemStyles(item.href)"
                            :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
                        >
                            <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                            {{ item.title }}
                        </Link>
                    </nav>

                    <div class="flex flex-col gap-3">
                        <div class="px-8">
                            <LanguagesMenu />
                        </div>
                        <AppearanceTabs />
                    </div>
                </div>
            </SheetContent>
        </Sheet>
    </div>
</template>
