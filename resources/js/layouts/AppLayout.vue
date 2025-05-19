<script setup lang="ts">
import AppNavbarLayout from '@/layouts/app/AppNavbarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const page = usePage<SharedData>();

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppNavbarLayout :breadcrumbs="breadcrumbs" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
        <slot />
    </AppNavbarLayout>
</template>
