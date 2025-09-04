<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const page = usePage<SharedData>();

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    dashboardFor?: string;
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppSidebarLayout :breadcrumbs="breadcrumbs" :dashboardFor="props.dashboardFor" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
        <slot />
    </AppSidebarLayout>
</template>
