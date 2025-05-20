<script setup lang="ts">
import { Image } from '@/components/ui/image';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowBigLeft } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Restaurants',
        href: '/dashboard/restaurants',
    },
];
</script>

<template>
    <Head title="Restaurants" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route('dashboard.restaurants.index')"
                class="w-fit py-1 px-2 rounded flex items-center gap-1 cursor-pointer bg-blue-600/30 hover:bg-blue-600/50">
                <ArrowBigLeft class="size-4" />
                <p class="text-semibold text-sm">Go Back</p>
            </Link>
        </div>

        <div class="flex flex-col px-4 pb-4">
            <div class="w-full grid grid-cols-2 gap-3 flex-wrap">
                <Image :src="data.image" class="w-[600px] h-[350px]" />

                <div class="flex gap-3 bg-gray-800/20">
                    <div class="flex flex-col gap-2 px-3 py-2">
                        <p class="text-muted">Name:</p>
                        <p class="text-lg font-semibold text-gray-200">{{ props.data.name.charAt(0).toUpperCase() + props.data.name.slice(1) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
