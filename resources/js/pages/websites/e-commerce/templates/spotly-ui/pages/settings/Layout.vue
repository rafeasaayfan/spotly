<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { SharedData, type NavItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Lock, User } from 'lucide-vue-next';

const pageLang = usePage<SharedData>();

const sidebarNavItems: NavItem[] = [
    {
        title: pageLang.props.lang === 'ar' ? 'الملف الشخصي' : 'Profile',
        href: '/settings/profile',
        icon: User,
    },
    {
        title: pageLang.props.lang === 'ar' ? 'كلمة المرور' : 'Password',
        href: '/settings/password',
        icon: Lock,
    },
    // {
    //     title: 'Preferences',
    //     href: '/settings/preferences',
    //     icon: Settings,
    // },
];

interface ZiggyProps {
    location: string;
}

const page = usePage<{ ziggy?: ZiggyProps }>();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <Head :title="$t('settings')" />

    <div class="flex flex-col gap-3 m-4 border web-border-color rounded-md lg:h-screen">
        <div class="grid h-full grid-cols-7 gap-5 lg:grid-cols-5">
            <aside class="border-b md:border-b-0 md:border-e web-border-color 
            col-span-7 h-full w-full p-4 md:col-span-2 lg:col-span-1">
                <nav class="grid grid-cols-1 gap-4">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="tab"
                        :class="[
                            'duration-100 h-9 text-sm bg-transparent justify-start hover:translate-y-0 hover:scale-100 hover:border',
                            'border-[var(--border_color_light)] dark:border-[var(--border_color_dark)]',
                            { 'web-text-active bg-[var(--bg_content_active_light)] dark:bg-[var(--bg_content_active_dark)]'
                            : currentPath === item.href },
                        ]"
                        as-child
                    >
                        <Link :href="item.href ?? ''" class="flex items-center gap-1.5 text-base">
                            <component :is="item.icon" class="size-4" />
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <div class="col-span-7 flex w-full rounded-md md:col-span-5 md:justify-center p-4 lg:col-span-4">
                <section class="w-full space-y-6">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
