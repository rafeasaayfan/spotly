<script setup lang="ts">
import SidebarContentFooter from '@/components/sidebar/content/SidebarContentFooter.vue';
import SidebarContentHeader from '@/components/sidebar/content/SidebarContentHeader.vue';
import SidebarMain from '@/components/sidebar/content/SidebarMain.vue';
// import SidebarUser from '@/components/sidebar/content/SidebarUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader} from '@/components/ui/sidebar';
import { sidebarVariant, sidebarCollapsible } from '@/config/navigations';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();
</script>

<template>
    <Sidebar :collapsible="sidebarCollapsible" :variant="sidebarVariant" :side="page.props.lang == 'ar' ? 'right' : 'left'">
        <SidebarHeader>
            <SidebarContentHeader></SidebarContentHeader>
        </SidebarHeader>

        <SidebarContent>
            <SidebarMain />
        </SidebarContent>

        <SidebarFooter>
            <SidebarContentFooter />
            <!-- <SidebarUser /> -->
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
