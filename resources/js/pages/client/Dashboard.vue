<script setup lang="ts">
import Pie from '@/components/dashboard/charts/Pie.vue';
import StatCard from '@/components/dashboard/stats/StatCard.vue';
import StatsOverviewCard from '@/components/dashboard/stats/StatsOverviewCard.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { formatters } from '@/lib/dataTable';
import { typeColor } from '@/lib/websiteTypes';
import { SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { TrendingDown, TrendingUp } from 'lucide-vue-next';

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
interface Revenue {
    total: number;
    by_type: any[];
}

interface Props {
    userStats: UserStats;
    websiteStats: WebsiteStats;
    websiteCountByType: Record<string, any>;
    revenue: Revenue;
    joinedAt: string;
    topWebsites: Record<string, any>;
}

const props = defineProps<Props>();

const page = usePage<SharedData>();
</script>

<template>
    <Head :title="$t('dashboard')" />

    <DashboardLayout :breadcrumbs="[{ title: $t('dashboard'), href: '/dashboard/stats' }]">
        <div class="mx-2 my-4 flex flex-col gap-4 md:mx-4">
            <StatsOverviewCard :gridSize="3">
                <StatCard
                    :totalCount="props.userStats.all"
                    :title="$t('dashboard.total_users')"
                    icon="users"
                    :active="{
                        count: props.userStats.active,
                        title: $t('active'),
                    }"
                    :inactive="{
                        count: props.userStats.inactive,
                        title: $t('inactive'),
                    }"
                    :banned="{
                        count: props.userStats.banned,
                        title: $t('dashboard.banned'),
                    }"
                ></StatCard>

                <StatCard
                    :totalCount="props.websiteStats.all"
                    :title="$t('dashboard.total_websites')"
                    icon="globe"
                    :withBorder="true"
                    :active="{
                        count: props.websiteStats.active,
                        title: $t('active'),
                    }"
                    :inactive="{
                        count: props.websiteStats.inactive,
                        title: $t('inactive'),
                    }"
                ></StatCard>

                <StatCard :totalCount="props.revenue.total" :title="$t('dashboard.total_revenue')" currency="$" icon="wallet" :withBorder="true">
                    <div class="flex items-center gap-2">
                        <div v-if="props.revenue.total > 0" class="flex size-10 items-center justify-center rounded-md bg-green-500/10">
                            <TrendingUp class="size-5 text-green-500" />
                        </div>
                        <div v-else class="flex size-10 items-center justify-center rounded-md bg-red-500/10">
                            <TrendingDown class="size-5 text-red-500" />
                        </div>
                        <span class="text-body text-sm">
                            {{ formatters.date(props.joinedAt, 'ago') }}
                        </span>
                    </div>
                </StatCard>
            </StatsOverviewCard>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-7">
                <div class="col-span-1 grid grid-cols-1 gap-4 sm:col-span-2 sm:grid-cols-2 lg:grid-cols-1">
                    <div class="border-muted flex flex-col rounded-md border">
                        <div
                            class="border-muted flex flex-col rounded-t-md border-b bg-gradient-to-br from-black/8 via-transparent to-black/8 px-3 pt-3 pb-2 dark:from-white/8 dark:to-white/8"
                        >
                            <h3 class="text-active font-bold">{{$t('dashboard.top_websites_title')}}</h3>
                        </div>
                        <div v-if="props.topWebsites && props.topWebsites.length > 0" class="flex max-h-75 flex-col gap-3 overflow-y-auto p-3">
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
                        <div v-else class="flex justify-center py-8">
                            <span class="text-body-muted text-sm">{{$t('dashboard.no_top_websites')}}</span>
                        </div>
                    </div>
                </div>

                <!-- By type -->
                <div class="border-muted col-span-1 flex flex-col rounded-md border lg:col-span-2">
                    <div
                        class="border-muted flex flex-col rounded-t-md border-b bg-gradient-to-br from-black/8 via-transparent to-black/8 px-3 pt-3 pb-2 dark:from-white/8 dark:to-white/8"
                    >
                        <h3 class="text-active font-bold">{{$t('dashboard.websites_by_type')}}</h3>
                        <p class="text-body-muted text-xs">{{$t('dashboard.websites_by_type_desc')}}</p>
                    </div>
                    <div
                        v-if="props.websiteCountByType && props.websiteCountByType.length > 0"
                        class="flex max-h-75 flex-col gap-3 overflow-y-auto p-3"
                    >
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
                    <div v-else class="flex justify-center py-8">
                        <span class="text-body-muted text-sm">{{$t('dashboard.no_website_types')}}</span>
                    </div>
                </div>

                <Pie
                    parentClass="col-span-1 lg:col-span-3"
                    :title="$t('dashboard.website_status_chart_title')"
                    :subtitle="$t('dashboard.website_status_chart_subtitle')"
                    :data="[
                        { value: props.websiteStats.approved, name: page.props.lang === 'ar' ? 'مقبول' : 'Approved', itemStyle: { color: '#22c55e' } },
                        { value: props.websiteStats.pending, name: page.props.lang === 'ar' ? 'قيد المراجعة' : 'Pending', itemStyle: { color: '#eab308' } },
                        { value: props.websiteStats.denied, name: page.props.lang === 'ar' ? 'مرفوض' : 'Denied', itemStyle: { color: '#ef4444' } }
                    ]"
                />
            </div>
        </div>
    </DashboardLayout>
</template>
