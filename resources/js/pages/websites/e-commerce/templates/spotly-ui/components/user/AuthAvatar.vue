<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import UserMenuContent from './UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import { Button } from '@/components/ui/button'
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';
import { computed } from 'vue';

const page = usePage<SharedData>();
const auth = computed(() => page.props.auth);
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger :as-child="true">
            <Button
                variant="ghost"
                size="icon"
                class="size-10 w-auto cursor-pointer rounded-full p-1 web-bg-primary web-text-for-primary"
            >
                <Avatar class="size-8 overflow-hidden rounded-full">
                    <AvatarImage v-if="auth.website_user?.avatar" :src="auth.website_user.avatar" :alt="auth.website_user.name" />
                    <AvatarFallback class="rounded-lg font-bold web-bg-primary web-text-for-primary">
                        {{ getInitials(auth.website_user?.name) }}
                    </AvatarFallback>
                </Avatar>
            </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent class="w-56 web-bg-dropdown web-border-color">
            <UserMenuContent :website_user="auth.website_user" :websiteUserRole="auth.websiteUserRole" />
        </DropdownMenuContent>
    </DropdownMenu>
</template>
