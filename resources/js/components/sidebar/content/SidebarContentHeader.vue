<script setup lang="ts">
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { Link } from '@inertiajs/vue3';
import AppLogo from '@/components/logo/AppLogo.vue';

defineProps<{
  websiteNameAndLogo?: Record<string, string>;
}>();
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <SidebarMenuButton size="lg" as-child>
                <Link v-if="!websiteNameAndLogo" :href="route('landing')">
                    <AppLogo />
                </Link>

                <Link v-else-if="websiteNameAndLogo" href="/" class="flex items-center gap-3">
                    <img 
                        :src="websiteNameAndLogo.light_logo"
                        class="size-12 rounded-full block dark:hidden"
                    />
                    <img 
                        :src="websiteNameAndLogo.dark_logo"
                        class="size-12 rounded-full hidden dark:block"
                    />
                    <p class="text-active text-lg font-bold">{{ websiteNameAndLogo.name }}</p>
                </Link>
            </SidebarMenuButton>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
