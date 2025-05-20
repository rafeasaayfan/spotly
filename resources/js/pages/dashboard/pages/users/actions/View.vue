<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowBigLeft } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/dashboard/users',
    },
];
</script>

<template>
    <Head title="Users" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route('dashboard.restaurants.index')"
                class="w-fit py-1 px-2 rounded flex items-center gap-1 cursor-pointer bg-blue-600/30 hover:bg-blue-600/50">
                <ArrowBigLeft class="size-4" />
                <p class="text-semibold text-sm">Go Back</p>
            </Link>
        </div>

        <!-- <div>{{ props.data.name }}</div> -->

        <div class="grid grid-cols-2 gap-4 p-4">
            <div v-for="(item, index) in props.data" :key="index" class="flex flex-col gap-3 rounded-md border bg-gray-950/30 p-4 shadow-sm">
                <div class="flex items-center gap-2">
                    <p class="text-muted">{{ index.charAt(0).toUpperCase() + index.slice(1) }}</p>
                </div>

                <p class="text-lg font-semibold text-gray-200">{{ item }}</p>
            </div>
        </div>
    </DashboardLayout>
</template>
