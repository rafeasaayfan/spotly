<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSplitLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const page = usePage<SharedData>();

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <AuthLayout :title="title" :description="description" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
        <slot />
    </AuthLayout>
</template>
