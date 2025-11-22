<script setup lang="ts">
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import UserInfo from './UserInfo.vue';

import type { User } from '@/types';
import { Link, router } from '@inertiajs/vue3';

import { LayoutDashboard, LogOut, Settings, User as UserIcon } from 'lucide-vue-next';

interface Props {
    website_user: User;
    websiteUserRole: string[];
}

const handleLogout = () => {
    router.flushAll();
};

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="web-bg-content p-0 font-normal">
        <div class="flex items-center gap-2 px-2 py-3 text-sm">
            <UserInfo :user="website_user" :show-email="true" />
        </div>
    </DropdownMenuLabel>

    <DropdownMenuSeparator class="bg-[var(--border_color_light)] dark:bg-[var(--border_color_dark)]" />

    <DropdownMenuGroup>
        <DropdownMenuItem
            :as-child="true"
            class="web-text-body bg-transparent hover:bg-[var(--bg_content_hover_light)] dark:hover:bg-[var(--bg_content_hover_dark)]"
        >
            <Link class="flex w-full cursor-pointer items-center gap-2" :href="route('website.profile.edit')" prefetch as="button">
                <Settings class="h-4 w-4" />
                {{ $t('settings') }}
            </Link>
        </DropdownMenuItem>

        <DropdownMenuItem
            :as-child="true"
            class="web-text-body bg-transparent hover:bg-[var(--bg_content_hover_light)] dark:hover:bg-[var(--bg_content_hover_dark)]"
        >
            <Link class="flex w-full cursor-pointer items-center gap-2" :href="route('website.profile.index')" prefetch as="button">
                <UserIcon class="h-4 w-4" />
                {{ $t('my.profile') }}
            </Link>
        </DropdownMenuItem>

        <DropdownMenuItem
            v-if="websiteUserRole.includes('admin') || websiteUserRole.includes('owner')"
            :as-child="true"
            class="web-text-body bg-transparent hover:bg-[var(--bg_content_hover_light)] dark:hover:bg-[var(--bg_content_hover_dark)]"
        >
            <Link class="flex w-full cursor-pointer items-center gap-2" :href="route('website.e-commerce.dashboard.index')" prefetch as="button">
                <LayoutDashboard class="h-4 w-4" />
                {{ $t('dashboard') }}
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>

    <DropdownMenuSeparator class="bg-[var(--border_color_light)] dark:bg-[var(--border_color_dark)]" />

    <DropdownMenuItem variant="destructive" :as-child="true" class="web-bg-danger web-text-for-danger">
        <Link class="flex w-full cursor-pointer items-center gap-2" method="post" :href="route('website.logout')" @click="handleLogout" as="button">
            <LogOut class="h-4 w-4" />
            {{ $t('auth.logout') }}
        </Link>
    </DropdownMenuItem>
</template>
