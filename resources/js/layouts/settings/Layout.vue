<script setup lang="ts">
import Heading from '@/components/headers/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: '/settings/profile',
    },
    {
        title: 'Password',
        href: '/settings/password',
    },
    {
        title: 'App Settings',
        href: '/settings/appSettings',
    },
];

interface ZiggyProps {
  location: string
}

const page = usePage<{ ziggy?: ZiggyProps }>()

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <div class="flex flex-col gap-3 px-4 py-6 lg:h-screen">
        <header class="w-full bg-card rounded-md p-4">
            <Heading title="Settings" description="Manage your profile and account settings" />
        </header>

        <div class="grid grid-cols-7 lg:grid-cols-5 gap-5 h-full">

            <aside class="col-span-7 md:col-span-2 lg:col-span-1 w-full bg-navs py-4 px-2 rounded-md h-full">
                <nav class="flex flex-col space-x-0 space-y-2">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="tab"
                        :class="['w-full justify-start', { 'bg-content-2-active border border-primary text-active': currentPath === item.href }]"
                        as-child
                    >
                        <Link :href="item.href">
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <Separator class="col-span-3 my-2 md:hidden" />

            <div class="col-span-7 md:col-span-5 lg:col-span-4 w-full p-4 rounded-md flex md:justify-center">
                <section class="w-full space-y-12">
                    <slot />
                </section>
            </div>

        </div>
    </div>
</template>
