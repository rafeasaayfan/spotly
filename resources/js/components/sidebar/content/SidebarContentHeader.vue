<script setup lang="ts">
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { Link } from '@inertiajs/vue3';
import AppLogo from '@/components/logo/AppLogo.vue';

// defineProps<{
//     items: SidebarSection[];
// }>();
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <SidebarMenuButton size="lg" as-child class="flex items-center justify-center">
                <Link :href="route('landing')">
                    <AppLogo />
                </Link>
            </SidebarMenuButton>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
