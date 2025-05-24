<script setup lang="ts">
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import UserInfo from '@/components/userDropdown/UserInfo.vue';
import type { User } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { LogOut, Settings, LayoutGrid } from 'lucide-vue-next';

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
            <Link class="flex w-full items-center gap-2 curs2or-pointer" :href="route('profile.edit')" prefetch as="button">
                <Settings class="h-4 w-4" />
                Settings
            </Link>
        </DropdownMenuItem>

        <DropdownMenuItem :as-child="true">
            <Link class="flex w-full items-center gap-2 cursor-pointer" :href="route('dashboard.index')" prefetch as="button">
                <LayoutGrid class="h-4 w-4" />
                Dashboard
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>

    <DropdownMenuSeparator />

    <DropdownMenuItem variant="destructive" :as-child="true" class="bg-destructive text-for-bg-destructive">
        <Link class="flex w-full items-center gap-2 cursor-pointer" method="post" :href="route('logout')" @click="handleLogout" as="button">
            <LogOut class="h-4 w-4" />
            Log out
        </Link>
    </DropdownMenuItem>
</template>
