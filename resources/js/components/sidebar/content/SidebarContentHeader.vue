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
            <SidebarMenuButton size="lg" as-child>
                <Link :href="route('dashboard.index')">
                    <AppLogo />
                </Link>
            </SidebarMenuButton>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
