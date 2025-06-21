<script setup lang="ts">
import Heading from '@/components/headers/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Lock, Settings, User } from 'lucide-vue-next';

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
        title: 'Preferences',
        href: '/settings/preferences',
        icon: Settings,
    },
];

interface ZiggyProps {
    location: string;
}

const page = usePage<{ ziggy?: ZiggyProps }>();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <div class="flex flex-col gap-3 px-4 pb-6 lg:h-screen">
        <header class="border-muted w-full border-b pb-2">
            <Heading title="Settings" description="Manage your profile and account settings" />
        </header>

        <div class="grid h-full grid-cols-7 gap-5 lg:grid-cols-5">
            <aside class="bg-nav col-span-7 h-full w-full rounded-md px-2 py-4 md:col-span-2 lg:col-span-1">
                <nav class="flex flex-col gap-4">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="tab"
                        :class="[
                            'w-full justify-start hover:translate-y-0 hover:scale-100',
                            { 'bg-content-2-active text-active': currentPath === item.href },
                        ]"
                        as-child
                    >
                        <Link :href="item.href" class="flex items-center gap-2">
                            <component :is="item.icon" class="size-4.5" />
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <Separator class="col-span-3 my-2 md:hidden" />

            <div class="col-span-7 flex w-full rounded-md md:col-span-5 md:justify-center md:p-4 lg:col-span-4">
                <section class="w-full space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
