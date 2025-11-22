<script setup lang="ts">
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import UserInfo from '@/components/user/UserInfo.vue';

import type { User } from '@/types';

import { Link, router } from '@inertiajs/vue3';

import { LogOut, Settings, LayoutDashboard } from 'lucide-vue-next';

interface Props {
    user: User;
}

const handleLogout = () => {
    router.flushAll();
};

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-2 py-3 text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>

    <DropdownMenuSeparator />

    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <Link class="flex w-full items-center gap-2 cursor-pointer" :href="route('profile.edit')" prefetch as="button">
                <Settings class="h-4 w-4" />
                {{ $t('settings') }}
            </Link>
        </DropdownMenuItem>


        <DropdownMenuItem :as-child="true">
            <Link class="flex w-full items-center gap-2 cursor-pointer" :href="route('dashboard.index')" prefetch as="button">
                <LayoutDashboard class="h-4 w-4" />
                {{ $t('dashboard') }}
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>

    <DropdownMenuSeparator />

    <DropdownMenuItem variant="destructive" :as-child="true" class="bg-destructive text-for-bg-destructive">
        <Link class="flex w-full items-center gap-2 cursor-pointer" method="post" :href="route('logout')" @click="handleLogout" as="button">
            <LogOut class="h-4 w-4" />
            {{ $t('auth.logout') }}
        </Link>
    </DropdownMenuItem>
</template>
