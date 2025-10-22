<script setup lang="ts">
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle2, Clock, PackageCheck, RotateCcw, XCircle } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    fetchOrders: (newStatus: string) => any;
    status: string;
    processing: boolean
}>();

const page = usePage<SharedData>();
const statusFilters = [
    {
        title: page.props.lang === 'ar' ? 'قيد الانتظار' : 'Pending',
        name: 'pending',
        icon: Clock,
        active: 'web-text-active',
    },
    {
        title: page.props.lang === 'ar' ? 'تم التأكيد' : 'Confirmed',
        name: 'confirmed',
        icon: CheckCircle2,
        active: 'text-green-600',
    },
    {
        title: page.props.lang === 'ar' ? 'تم التوصيل' : 'Delivered',
        name: 'delivered',
        icon: PackageCheck,
        active: 'web-text-active-link',
    },
    {
        title: page.props.lang === 'ar' ? 'تم الإلغاء' : 'Cancelled',
        name: 'cancelled',
        icon: XCircle,
        active: 'web-text-danger',
    },
    {
        title: page.props.lang === 'ar' ? 'تم الاسترداد' : 'Refunded',
        name: 'refunded',
        icon: RotateCcw,
        active: 'text-orange-600',
    },
];

const isProcessing = computed(() => props.processing);
</script>

<template>
    <div class="relative col-span-1 flex flex-wrap lg:flex-nowrap lg:flex-col justify-between lg:justify-start gap-3 lg:gap-10
        border-b lg:border-b-0 web-border-color pb-2 lg:pb-0"
    >
        <div class="hidden lg:block web-border-color pointer-events-none absolute start-6 top-0 z-0 w-full h-full border-b lg:border-b-0 lg:border-s"></div>

        <div
            v-for="(item, index) in statusFilters"
            :key="index"
            @click="props.fetchOrders(item.name)"
            class="bg-transparend z-5 flex lg:w-50 cursor-pointer items-center gap-1 lg:gap-2 rounded-md py-2 lg:py-3 ps-2.5 pe-2.5 lg:ps-4 
            font-medium hover:bg-[var(--bg_content_light)] dark:hover:bg-[var(--bg_content_dark)] text-xs lg:text-base"
            :class="[
                props.status === item.name
                    ? `bg-[var(--bg_content_light)] backdrop-blur-[3px] dark:bg-[var(--bg_content_dark)] ${item.active}`
                    : 'web-text-body-muted',
                isProcessing ? 'pointer-events-none' : '',
            ]"
        >
            <component :is="item.icon" class="size-3.5 lg:size-4.5" />
            <span>{{ item.title }}</span>
        </div>
    </div>
</template>
