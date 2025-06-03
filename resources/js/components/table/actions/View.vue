<script setup lang="ts">
import { Button } from '@/components/ui/button';

import Heading from '@/components/headers/Heading.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { type BreadcrumbItem } from '@/types';
import { formatters } from '@/lib/dataTable'

import { Head, Link } from '@inertiajs/vue3';

import { ArrowBigLeft } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
    table: string;
    href?: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: props.table.charAt(0).toUpperCase() + props.table.slice(1),
        href: `/dashboard/${props.href? props.href : props.table}`,
    },
];
</script>

<template>
    <Head :title="props.table.charAt(0).toUpperCase() + props.table.slice(1)" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route(`dashboard.${props.table}.index`)">
                <Button variant="secondary" size="sm">
                    <ArrowBigLeft class="size-4" />
                    <p>Go Back</p>
                </Button>
            </Link>
        </div>

        <div class="bg-card mx-4 rounded-md p-4 shadow-md">
            <Heading :title="`Create new ${props.table}`" :description="`Create a new ${props.table} for your application.`" />
        </div>

        <div class="m-4 grid grid-cols-2 gap-3 rounded-md">
            <div v-for="(item, index) in props.data" :key="index" class="border-muted bg-card flex flex-col gap-3 rounded-md border p-4 shadow-sm">
                <div class="flex items-center gap-2">
                    <p class="text-body-muted">{{ index.charAt(0).toUpperCase() + index.slice(1) }}</p>
                </div>

                <p class="text-body text-lg font-medium">
                    <template v-if="index === 'created_at' || index === 'updated_at'">
                        {{ formatters.date(item, 'short') }}
                    </template>

                    <template v-else-if="index === 'image'">

                    </template>

                    <template v-else>
                        {{ item }}
                    </template>
                </p>
            </div>
        </div>
    </DashboardLayout>
</template>
