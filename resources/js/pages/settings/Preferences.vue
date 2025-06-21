<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/appearance/AppearanceTabs.vue';
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import LanguagesTabs from '@/components/languages/LanguagesTabs.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Appearance settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Appearance settings" description="Update your account's appearance settings" />
                <AppearanceTabs />
            </div>

            <div class="space-y-6">
                <HeadingSmall title="Languages settings" description="Update your account's language settings" />
                <LanguagesTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
