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
        title: 'Appearance',
        href: '/settings/appearance',
    },
];

interface ZiggyProps {
  location: string
}

const page = usePage<{ ziggy?: ZiggyProps }>()

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <div class="flex flex-col gap-3 px-4 py-6">
        <header class="w-full bg-card rounded-md p-2">
            <Heading title="Settings" description="Manage your profile and account settings" />
        </header>

        <div class="grid grid-cols-5 space-y-8 md:space-y-0 lg:space-x-8 lg:space-y-0">

            <aside class="col-span-1 w-full max-w-xl lg:w-48 bg-navs py-4 px-2 rounded-md">
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

            <Separator class="my-6 md:hidden" />

            <div class="col-span-4 w-full p-4 rounded-md">
                <section class="max-w-xl space-y-12">
                    <slot />
                </section>
            </div>

        </div>
    </div>
</template>
