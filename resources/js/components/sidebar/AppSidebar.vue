<script setup lang="ts">
import SidebarContentFooter from '@/components/sidebar/content/SidebarContentFooter.vue';
import SidebarContentHeader from '@/components/sidebar/content/SidebarContentHeader.vue';
import SidebarMain from '@/components/sidebar/content/SidebarMain.vue';
import SidebarUser from '@/components/sidebar/content/SidebarUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader } from '@/components/ui/sidebar';
import { footerSidebarItems, mainSidebarItems, sidebarCollapsible, sidebarVariant } from '@/config/navigations';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();

interface Props {
  dashboardForProps?: string;
}

const props = withDefaults(defineProps<Props>(), {});

const getSidebarConfig = (dashboard: string) => {
  switch (dashboard) {
    case 'e-commerce':
      return {
        main: [],
        footer: [],
      };
    default:
      return {
        main: mainSidebarItems(),
        footer: footerSidebarItems(),
      };
  }
};

const sidebarConfig = getSidebarConfig(props.dashboardForProps ?? '');
</script>

<template>
  <Sidebar :collapsible="sidebarCollapsible" :variant="sidebarVariant"
    :side="page.props.lang == 'ar' ? 'right' : 'left'">
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
</template>
