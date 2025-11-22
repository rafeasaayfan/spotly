<script setup lang="ts">
import Pie from '@/components/dashboard/charts/Pie.vue';
import StatCard from '@/components/dashboard/stats/StatCard.vue';
import StatsOverviewCard from '@/components/dashboard/stats/StatsOverviewCard.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { formatters } from '@/lib/dataTable';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { TrendingDown, TrendingUp } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface ProductStats {
    all: number;
    active: number;
    inactive: number;
}
interface UserStats {
    all: number;
    active: number;
    inactive: number;
    banned: number;
}
interface OrderStats {
    pending: number;
    confirmed: number;
    delivered: number;
    cancelled: number;
    rejected: number;
    refunded: number;
}
interface PaymentStats {
    totalRevenue: number;
    first_date: string;
}

interface Props {
    websiteNameAndLogo?: Record<string, string>;

    userStats: UserStats;
    productStats: ProductStats;
    orderStats: OrderStats;
    paymentStats: PaymentStats;
    topUsers: Record<string, any>;
    topViewedProducts: Record<string, any>;
    topSoldProducts: Record<string, any>;
}

const props = defineProps<Props>();
</script>

<template>
    <Head :title="$t('dashboard')" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="e-commerce" :websiteNameAndLogo="props.websiteNameAndLogo">
        <div class="mx-2 my-4 flex flex-col gap-4 md:mx-4">
            <StatsOverviewCard :gridSize="3">
                <StatCard
                    :totalCount="props.userStats.all"
                    title="Total Users"
                    icon="users"
                    :active="{
                        count: props.userStats.active,
                        title: 'Active',
                    }"
                    :inactive="{
                        count: props.userStats.inactive,
                        title: 'Inactive',
                    }"
                    :banned="{
                        count: props.userStats.banned,
                        title: 'Banned',
                    }"
                ></StatCard>

                <StatCard
                    :totalCount="props.productStats.all"
                    title="Total Products"
                    icon="box"
                    :withBorder="true"
                    :active="{
                        count: props.productStats.active,
                        title: 'Active',
                    }"
                    :inactive="{
                        count: props.productStats.inactive,
                        title: 'Inactive',
                    }"
                ></StatCard>

                <StatCard
                    :totalCount="props.paymentStats.totalRevenue"
                    title="Total Revenue"
                    currency="$"
                    icon="wallet"
                    :withBorder="true"
                >
                    <div class="flex items-center gap-2">
                        <div v-if="props.paymentStats.totalRevenue > 0" class="flex size-10 items-center justify-center rounded-md bg-green-500/10">
                            <TrendingUp class="size-5 text-green-500" />
                        </div>
                        <div v-else class="flex size-10 items-center justify-center rounded-md bg-red-500/10">
                            <TrendingDown class="size-5 text-red-500" />
                        </div>
                        <span class="text-body text-sm">
                            {{ formatters.date(props.paymentStats.first_date, 'ago') }}
                        </span>
                    </div>
                </StatCard>
            </StatsOverviewCard>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-7">
                <div class="grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 col-span-1 sm:col-span-2 grid gap-4">
                    <div class="border-muted flex flex-col rounded-md border">
                        <div
                            class="border-muted flex flex-col rounded-t-md border-b bg-gradient-to-br from-black/8 via-transparent to-black/8 px-3 pt-3 pb-2 dark:from-white/8 dark:to-white/8"
                        >
                            <h3 class="text-active font-bold">Top Users by Delivered Orders</h3>
                        </div>
                        <div v-if="props.topUsers && props.topUsers.length > 0" class="flex max-h-75 flex-col gap-3 overflow-y-auto p-3">
                            <div
                                v-for="(item, index) in props.topUsers"
                                :key="index"
                                class="border-muted flex items-center justify-between gap-3"
                                :class="Number(index) !== props.topUsers.length - 1 ? 'border-b pb-2' : ''"
                            >
                                <span :class="['text-sm font-bold']">{{ item.name }}</span>
                                <span class="text-active font-bold">{{ item.delivered_orders_count }}</span>
                            </div>
                        </div>
                        <div v-else class="flex justify-center py-8">
                            <span class="text-body-muted text-sm">No top users found</span>
                        </div>
                    </div>
                    <div class="border-muted flex flex-col rounded-md border">
                        <div
                            class="border-muted flex flex-col rounded-t-md border-b bg-gradient-to-br from-black/8 via-transparent to-black/8 px-3 pt-3 pb-2 dark:from-white/8 dark:to-white/8"
                        >
                            <h3 class="text-active font-bold">Top Products by Views</h3>
                        </div>
                        <div v-if="props.topViewedProducts && props.topViewedProducts.length > 0" class="flex max-h-75 flex-col gap-3 overflow-y-auto p-3">
                            <div
                                v-for="(item, index) in props.topViewedProducts"
                                :key="index"
                                class="border-muted flex items-center justify-between gap-3"
                                :class="Number(index) !== props.topViewedProducts.length - 1 ? 'border-b pb-2' : ''"
                            >
                                <span :class="['text-sm font-bold']">{{ item.name }}</span>
                                <span class="text-active font-bold">{{ item.views_count }}</span>
                            </div>
                        </div>
                        <div v-else class="flex justify-center py-8">
                            <span class="text-body-muted text-sm">No top-viewed products found</span>
                        </div>
                    </div>
                </div>

                <!-- By type -->
                <div class="border-muted col-span-1 lg:col-span-2 flex flex-col rounded-md border">
                    <div
                        class="border-muted flex flex-col rounded-t-md border-b bg-gradient-to-br from-black/8 via-transparent to-black/8 px-3 pt-3 pb-2 dark:from-white/8 dark:to-white/8"
                    >
                        <h3 class="text-active font-bold">Top Products by Sales</h3>
                        <p class="text-body-muted text-xs">Top-selling products by number of delivered orders</p>
                    </div>
                    <div v-if="props.topSoldProducts && props.topSoldProducts.length > 0" class="flex max-h-75 flex-col gap-3 overflow-y-auto p-3">
                        <div
                            v-for="(item, index) in props.topSoldProducts"
                            :key="index"
                            class="border-muted flex items-center justify-between gap-3"
                            :class="Number(index) !== props.topSoldProducts.length - 1 ? 'border-b pb-2' : ''"
                        >
                            <span :class="['text-sm font-bold']">{{ item.name }}</span>
                            <span class="text-active font-bold">{{ item.sales_count }}</span>
                        </div>
                    </div>

                    <div v-else class="flex justify-center py-8">
                        <span class="text-body-muted text-sm">No top-selling products found</span>
                    </div>
                </div>

                <Pie
                    parentClass="col-span-1 lg:col-span-3"
                    title="Order Status"
                    subtitle="Order status by number of orders"
                    :data="[
                        { value: props.orderStats.pending, name: 'Pending', itemStyle: { color: '#64748b' } }, 
                        { value: props.orderStats.confirmed, name: 'Confirmed', itemStyle: { color: '#22c55e' } },
                        { value: props.orderStats.delivered, name: 'Delivered', itemStyle: { color: '#2563eb' } }, 
                        { value: props.orderStats.rejected, name: 'Rejected', itemStyle: { color: '#ec4899' } }, 
                        { value: props.orderStats.cancelled, name: 'Cancelled', itemStyle: { color: '#dc2626' } },
                        { value: props.orderStats.refunded, name: 'Refunded', itemStyle: { color: '#f97316' } },
                    ]"
                />
            </div>
        </div>
    </DashboardLayout>
</template>
