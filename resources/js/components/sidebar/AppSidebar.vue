<script setup lang="ts">
import SidebarContentFooter from '@/components/sidebar/content/SidebarContentFooter.vue';
import SidebarContentHeader from '@/components/sidebar/content/SidebarContentHeader.vue';
import SidebarMain from '@/components/sidebar/content/SidebarMain.vue';
import SidebarUser from '@/components/sidebar/content/SidebarUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader } from '@/components/ui/sidebar';
import { useSidebar } from '@/components/ui/sidebar/utils';
import { sidebarCollapsible, sidebarVariant, mainSidebarItems, footerSidebarItems } from '@/config/navigations';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();

const { dashboardFor } = useSidebar();

const getSidebarConfig = (dashboard: string) => {
  switch (dashboard) {
    case 'e-commerce':
      return {
        main: [],
        footer: [],
      }
    default:
      return {
        main: mainSidebarItems,
        footer: footerSidebarItems,
      }
  }
}

const sidebarConfig = getSidebarConfig(dashboardFor.value)
</script>

<template>
    <Sidebar :collapsible="sidebarCollapsible" :variant="sidebarVariant" :side="page.props.lang == 'ar' ? 'right' : 'left'">
        <SidebarHeader>
            <SidebarContentHeader></SidebarContentHeader>
        </SidebarHeader>

        <SidebarContent>
            <SidebarMain :mainSidebarItems="sidebarConfig.main" />
        </SidebarContent>

        <SidebarFooter>
            <SidebarContentFooter :footerSidebarItems="sidebarConfig.footer" />
            <SidebarUser />
        </SidebarFooter>
    </Sidebar>

    <slot />
</template>
