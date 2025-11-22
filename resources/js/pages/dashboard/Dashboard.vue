<script setup lang="ts">
import Pie from '@/components/dashboard/charts/Pie.vue';
import StatCard from '@/components/dashboard/stats/StatCard.vue';
import StatsOverviewCard from '@/components/dashboard/stats/StatsOverviewCard.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { formatters } from '@/lib/dataTable';
import { typeColor } from '@/lib/websiteTypes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { TrendingDown, TrendingUp } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }];

interface UserStats {
    all: number;
    active: number;
    inactive: number;
    banned: number;
}
interface WebsiteStats {
    all: number;
    active: number;
    inactive: number;
    approved: number;
    denied: number;
    pending: number;
}
interface PaymentStats {
    totalRevenue: number;
    first_date: string;
}

interface Props {
    userStats: UserStats;
    paymentStats: PaymentStats;
    websiteStats: WebsiteStats;
    websiteUserStats: UserStats;
    websiteCountByType: Record<string, any>;
    topUsers: Record<string, any>;
    topWebsites: Record<string, any>;
}

const props = defineProps<Props>();
</script>

<template>
    <Head title="Dashboard" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="mx-2 my-4 flex flex-col gap-4 md:mx-4">
            <StatsOverviewCard>
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

                <StatCard :totalCount="props.paymentStats.totalRevenue" title="Total Revenue" currency="$" icon="wallet" :withBorder="true">
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

                <StatCard
                    :totalCount="props.websiteStats.all"
                    title="Total Websites"
                    icon="globe"
                    :withBorder="true"
                    :active="{
                        count: props.websiteStats.active,
                        title: 'Active',
                    }"
                    :inactive="{
                        count: props.websiteStats.inactive,
                        title: 'Inactive',
                    }"
                ></StatCard>

                <StatCard
                    :totalCount="props.websiteUserStats.all"
                    title="Total Websites Users"
                    icon="users"
                    :withBorder="true"
                    :active="{
                        count: props.websiteUserStats.active,
                        title: 'Active',
                    }"
                    :inactive="{
                        count: props.websiteUserStats.inactive,
                        title: 'Inactive',
                    }"
                    :banned="{
                        count: props.websiteUserStats.banned,
                        title: 'Banned',
                    }"
                ></StatCard>
            </StatsOverviewCard>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-4">
                <div class="grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 col-span-1 sm:col-span-2 grid gap-4">
                    <div class="border-muted flex flex-col rounded-md border">
                        <div
                            class="border-muted flex flex-col rounded-t-md border-b bg-gradient-to-br from-black/8 via-transparent to-black/8 px-3 pt-3 pb-2 dark:from-white/8 dark:to-white/8"
                        >
                            <h3 class="text-active font-bold">Top Users by Approved Websites</h3>
                        </div>
                        <div class="flex max-h-75 flex-col gap-3 overflow-y-auto p-3">
                            <div
                                v-for="(item, index) in props.topUsers"
                                :key="index"
                                class="border-muted flex items-center justify-between gap-3"
                                :class="Number(index) !== props.topUsers.length - 1 ? 'border-b pb-2' : ''"
                            >
                                <span :class="['text-sm font-bold']">{{ item.name }}</span>
                                <span class="text-active font-bold">{{ item.approved_websites_count }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="border-muted flex flex-col rounded-md border">
                        <div
                            class="border-muted flex flex-col rounded-t-md border-b bg-gradient-to-br from-black/8 via-transparent to-black/8 px-3 pt-3 pb-2 dark:from-white/8 dark:to-white/8"
                        >
                            <h3 class="text-active font-bold">Top Websites by Total Users</h3>
                        </div>
                        <div class="flex max-h-75 flex-col gap-3 overflow-y-auto p-3">
                            <div
                                v-for="(item, index) in props.topWebsites"
                                :key="index"
                                class="border-muted flex items-center justify-between gap-3"
                                :class="Number(index) !== props.topWebsites.length - 1 ? 'border-b pb-2' : ''"
                            >
                                <span :class="['text-sm font-bold']">{{ item.name }}</span>
                                <span class="text-active font-bold">{{ item.website_users_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- By type -->
                <div class="border-muted col-span-1 lg:col-span-2 flex flex-col rounded-md border">
                    <div
                        class="border-muted flex flex-col rounded-t-md border-b bg-gradient-to-br from-black/8 via-transparent to-black/8 px-3 pt-3 pb-2 dark:from-white/8 dark:to-white/8"
                    >
                        <h3 class="text-active font-bold">Websites by Type</h3>
                        <p class="text-body-muted text-xs">Total number of websites grouped by their type</p>
                    </div>
                    <div class="flex max-h-75 flex-col gap-3 overflow-y-auto p-3">
                        <div
                            v-for="(item, index) in props.websiteCountByType"
                            :key="index"
                            class="border-muted flex items-center justify-between gap-3"
                            :class="Number(index) !== props.websiteCountByType.length - 1 ? 'border-b pb-2' : ''"
                        >
                            <span :class="['text-sm font-bold', typeColor.text(item.website_type.type)]">{{ item.website_type.type }}</span>
                            <span class="text-active font-bold">{{ item.total }}</span>
                        </div>
                    </div>
                </div>

                <!-- Website Status Chart -->
                <Pie
                    parentClass="col-span-1 lg:col-span-3"
                    title="Website Status Distribution"
                    subtitle="Current approval status"
                    :data="[
                        { value: props.websiteStats.approved, name: 'Approved', itemStyle: { color: '#22c55e' } },   
                        { value: props.websiteStats.pending, name: 'Pending', itemStyle: { color: '#eab308' } },   
                        { value: props.websiteStats.denied, name: 'Denied', itemStyle: { color: '#ef4444' } },      
                    ]"
                />
            </div>
        </div>
    </DashboardLayout>
</template>
