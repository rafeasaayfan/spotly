<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import { type BreadcrumbItem } from '@/types';

import DeleteUser from '@/components/user/DeleteUser.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

import DashboardLayout from '@/layouts/DashboardLayout.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Settings',
        href: '/settings/profile',
    },
    {
        title: 'Delete account',
        href: '/settings/delete-account',
    },
];
</script>

<template>
    <DashboardLayout :breadcrumbs="breadcrumbs">
        <Head title="Delete account" />

        <SettingsLayout>
            <DeleteUser />
        </SettingsLayout>
    </DashboardLayout>
</template>
