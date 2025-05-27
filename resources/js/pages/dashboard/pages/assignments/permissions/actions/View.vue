<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import Heading from '@/components/headers/Heading.vue';
import { Button } from '@/components/ui/button';

import { type BreadcrumbItem } from '@/types';
import { formatters } from '@/lib/dataTable'

import { Head, Link } from '@inertiajs/vue3';

import { ArrowBigLeft } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Permissions',
        href: '/dashboard/assignments/permissions',
    },
];
</script>

<template>
    <Head title="Permissions" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route('dashboard.permissions.index')">
                <Button variant="secondary" size="sm">
                    <ArrowBigLeft class="size-4" />
                    <p>Go Back</p>
                </Button>
            </Link>
        </div>

        <div class="bg-card mx-4 rounded-md p-4 shadow-md">
            <Heading title="View permission" description="View your permission for your application." />
        </div>

        <div class="grid grid-cols-2 gap-4 p-4">
            <div v-for="(item, index) in props.data" :key="index" class="border-muted bg-card flex flex-col gap-3 rounded-md border p-4 shadow-sm">
                <div class="flex items-center gap-2">
                    <p class="text-body-muted">{{ index.charAt(0).toUpperCase() + index.slice(1) }}</p>
                </div>

                <p class="text-body text-lg font-medium">
                    <template v-if="index === 'created_at' || index === 'updated_at'">
                        {{ formatters.date(item, 'short') }}
                    </template>

                    <template v-else>
                        {{ item }}
                    </template>
                </p>
            </div>
        </div>
    </DashboardLayout>
</template>
