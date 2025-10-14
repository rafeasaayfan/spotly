<script setup lang="ts">
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import UserInfo from './UserInfo.vue';

import type { User } from '@/types';
import { Link, router } from '@inertiajs/vue3';

import { LogOut, Settings, LayoutGrid } from 'lucide-vue-next';

interface Props {
    website_user: User,
    websiteUserRole: string
}

const handleLogout = () => {
    router.flushAll();
};

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal web-bg-content">
        <div class="flex items-center gap-2 px-2 py-3 text-sm">
            <UserInfo :user="website_user" :show-email="true" />
        </div>
    </DropdownMenuLabel>

    <DropdownMenuSeparator class="bg-[var(--border_color_light)] dark:bg-[var(--border_color_dark)]" />

    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true" class="bg-transparent hover:bg-[var(--bg_content_hover_light)] dark:hover:bg-[var(--bg_content_hover_dark)]
            web-text-body"
        >
            <Link class="flex w-full items-center gap-2 cursor-pointer" :href="route('website.profile.edit')" prefetch as="button">
                <Settings class="h-4 w-4" />
                {{ $t('settings') }}
            </Link>
        </DropdownMenuItem>


        <DropdownMenuItem v-if="['admin', 'owner'].includes(websiteUserRole)" :as-child="true" class="bg-transparent 
            hover:bg-[var(--bg_content_hover_light)] dark:hover:bg-[var(--bg_content_hover_dark)] web-text-body"
        >
            <Link class="flex w-full items-center gap-2 cursor-pointer" :href="route('website.e-commerce.dashboard.index')" prefetch as="button">
                <LayoutGrid class="h-4 w-4" />
                {{ $t('dashboard') }}
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>

    <DropdownMenuSeparator class="bg-[var(--border_color_light)] dark:bg-[var(--border_color_dark)]" />

    <DropdownMenuItem variant="destructive" :as-child="true" class="web-bg-danger web-text-for-danger">
        <Link class="flex w-full items-center gap-2 cursor-pointer" method="post" :href="route('website.logout')" @click="handleLogout" as="button">
            <LogOut class="h-4 w-4" />
            {{ $t('auth.logout') }}
        </Link>
    </DropdownMenuItem>
</template>
