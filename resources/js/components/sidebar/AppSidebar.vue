<script setup lang="ts">
import SidebarContentFooter from '@/components/sidebar/content/SidebarContentFooter.vue';
import SidebarContentHeader from '@/components/sidebar/content/SidebarContentHeader.vue';
import SidebarMain from '@/components/sidebar/content/SidebarMain.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader } from '@/components/ui/sidebar';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

//* SideBars
import { footerSidebarItems, mainSidebarItems, sidebarCollapsible, sidebarVariant } from '@/config/navigations';
import { footerSidebarItems as ecommerceFooter, mainSidebarItems as ecommerceMain } from '@/pages/websites/e-commerce/config/navigations/sidebar';
import { footerSidebarItems as restaurantFooter, mainSidebarItems as restaurantMain } from '@/pages/websites/restaurant/config/navigations/sidebar';

const page = usePage<SharedData>();

interface Props {
  dashboardForProps?: string;
  websiteNameAndLogo?: Record<string, string>
}

const props = withDefaults(defineProps<Props>(), {});

const getSidebarConfig = (dashboard: string) => {
  switch (dashboard) {
    case 'e-commerce':
      return {
        main: ecommerceMain,
        footer: ecommerceFooter,
      };
    case 'restaurant':
      return {
        main: restaurantMain,
        footer: restaurantFooter,
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
      <SidebarContentHeader :websiteNameAndLogo="props.websiteNameAndLogo"></SidebarContentHeader>
    </SidebarHeader>

    <SidebarContent>
      <SidebarMain :mainSidebarItems="sidebarConfig.main" />
    </SidebarContent>

    <SidebarFooter>
      <SidebarContentFooter :footerSidebarItems="sidebarConfig.footer" />
    </SidebarFooter>
  </Sidebar>
</template>
