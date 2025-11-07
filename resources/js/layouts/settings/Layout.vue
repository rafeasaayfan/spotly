<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Lock, Trash, User } from 'lucide-vue-next';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: '/settings/profile',
        icon: User,
    },
    {
        title: 'Password',
        href: '/settings/password',
        icon: Lock,
    },
    {
        title: 'Delete Account',
        href: '/settings/delete-account',
        icon: Trash,
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
    <div class="flex flex-col gap-3 my-4 mx-2 md:mx-4 border border-muted rounded-md lg:h-screen">
        <div class="grid h-full grid-cols-7 gap-5 lg:grid-cols-5">
            <aside class="border-b md:border-b-0 md:border-e border-muted 
            col-span-7 h-full w-full p-4 md:col-span-2 lg:col-span-1">
                <nav class="grid grid-cols-1 gap-4">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="tab"
                        :class="[
                            'h-9 text-sm bg-transparent justify-start hover:translate-y-0 hover:scale-100 hover:border border-muted',
                            { 'border border-[var(--primary)]/90 text-active  ': currentPath === item.href },
                        ]"
                        as-child
                    >
                        <Link :href="item.href ?? ''" class="flex items-center gap-1.5 text-base">
                            <component :is="item.icon" class="size-3.5" />
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <div class="col-span-7 flex w-full rounded-md md:col-span-5 md:justify-center p-4 lg:col-span-2">
                <section class="w-full space-y-6">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
