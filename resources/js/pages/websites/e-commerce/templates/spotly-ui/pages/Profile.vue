<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { CheckCircle2, CircleDollarSign, Clock, Crown, Mail, Phone, Settings, Shield, ShoppingBag, Truck, User as UserIcon, XCircle } from 'lucide-vue-next';
import { computed } from 'vue';
import Layout from './Layout.vue';
import { formatters } from '@/lib/dataTable';

const props = defineProps<{
    user: {
        name: string;
        email: string;
        phone_number: string;
        created_at: string;
        status: string;
        role: string;
    };
    pendingOrdersCount: number;
    confirmedOrdersCount: number;
    deliveredOrdersCount: number;
    failedOrdersCount: number;
    totalOrdersCount: number;
    totalSpent: number;
    colors: Record<string, string>;
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
    cartItemsCount: number;
}>();

const orderStatusStats = computed(() => [
    {
        titleKey: 'pending.orders',
        value: props.pendingOrdersCount,
        icon: Clock,
        color: 'text-yellow-500',
        bgColor: 'bg-yellow-500/15 dark:bg-yellow-500/10',
    },
    {
        titleKey: 'confirmed.orders',
        value: props.confirmedOrdersCount,
        icon: CheckCircle2,
        color: 'text-green-500',
        bgColor: 'bg-green-500/15 dark:bg-green-500/10',
    },
    {
        titleKey: 'delivered.orders',
        value: props.deliveredOrdersCount,
        icon: Truck,
        color: 'text-blue-500',
        bgColor: 'bg-blue-500/15 dark:bg-blue-500/10',
    },
    {
        titleKey: 'failed.orders',
        value: props.failedOrdersCount,
        icon: XCircle,
        color: 'text-red-500',
        bgColor: 'bg-red-500/15 dark:bg-red-500/10',
    },
]);
</script>

<template>
    <Layout
        :colors="props.colors"
        :websiteNameAndLogo="props.websiteNameAndLogo"
        :websiteFooterData="props.websiteFooterData"
        :cartItemsCount="props.cartItemsCount"
    >
        <section class="pt-28 pb-22">
            <div class="relative flex h-full w-full flex-col gap-10 px-2 md:px-8">
                <div class="pointer-events-none absolute inset-0 z-0 border-s border-e border-dashed blur"></div>

                <!-- User Info Section -->
                <div class="web-border-color relative flex gap-4 md:gap-0 flex-wrap justify-between items-end border-b pb-4">
                    <div class="flex items-center gap-3 md:gap-6">
                        <div class="relative">
                            <div class="web-border-color flex size-20 items-center justify-center rounded-full border-2">
                                <Crown v-if="props.user.role === 'owner'" class="size-8 text-yellow-500" />
                                <Shield v-else-if="props.user.role === 'admin'" class="web-text-active-link size-8" />
                                <UserIcon v-else class="web-text-body-muted size-8" />
                            </div>
                            <div
                                class="absolute end-1.5 bottom-1 size-4 rounded-full border-2 border-[var(--bg_body_light)] dark:border-[var(--bg_body_dark)]"
                                :class="props.user.status === 'active' ? 'bg-green-500' : 'bg-yellow-400'"
                            ></div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <h1 class="web-text-active text-2xl font-bold md:text-3xl">{{ props.user.name }}</h1>

                            <div class="flex flex-wrap items-center gap-x-5 gap-y-4 text-xs sm:text-sm">
                                <div class="web-text-body-muted flex items-center gap-1 sm:gap-1.5">
                                    <Mail class="size-3 sm:size-3.5" />
                                    <span>{{ props.user.email }}</span>
                                </div>
                                <div v-if="props.user.phone_number" class="web-text-body-muted flex items-center gap-1 sm:gap-1.5 ![direction:ltr]">
                                    <Phone class="size-3 sm:size-3.5" />
                                    <span>{{ props.user.phone_number }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <Button as-child variant="outline" size="sm" class="group web-bg-content web-text-body-muted border-0">
                            <Link :href="route('website.profile.edit')" class="flex items-center gap-2">
                                <Settings class="size-4 transition-all duration-200 ease-in-out group-hover:rotate-360" />
                                {{ $t('settings') }}
                            </Link>
                        </Button>
                    </div>
                </div>

                <!-- Total Orders and Total Spent - First Row -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="relative border-b-2 border-dashed border-emerald-500/30 dark:border-emerald-500/20 p-6">
                        <div class="mb-7 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <ShoppingBag class="web-text-body-muted size-6" />
                                <span class="web-text-body-muted text-sm font-bold tracking-wide uppercase">{{ $t('total.orders') }}</span>
                            </div>
                        </div>
                        <div class="web-text-active text-4xl font-bold md:text-5xl">{{ props.totalOrdersCount }}</div>
                    </div>

                    <div class="relative border-b-2 border-dashed border-emerald-500/30 dark:border-emerald-500/20 p-6">
                        <div class="mb-7 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <CircleDollarSign class="web-text-body-muted size-6" />
                                <span class="web-text-body-muted text-sm font-semibold tracking-wide uppercase">{{ $t('total.spent') }}</span>
                            </div>
                        </div>
                        <div class="web-text-active text-4xl font-bold md:text-5xl">{{ props.totalSpent }}$</div>
                    </div>
                </div>

                <!-- Order Status Counts - Second Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:grid-cols-4">
                    <div
                        v-for="(stat, index) in orderStatusStats"
                        :key="index"
                        class="web-border-color flex flex-col items-center gap-3 rounded-md border bg-gradient-to-br from-transparent via-black/3 to-transparent p-6 transition-all hover:from-black/3 hover:via-transparent hover:to-black/3 dark:via-white/3 dark:hover:from-white/3 dark:hover:via-transparent dark:hover:to-white/3"
                    >
                        <div :class="[stat.bgColor, 'flex flex-shrink-0 items-center justify-center rounded-md p-3']">
                            <component :is="stat.icon" :class="[stat.color, 'size-5 flex-shrink-0']" />
                        </div>
                        <div class="min-w-0 text-center">
                            <p class="web-text-body-muted mb-2 text-sm font-medium">{{ $t(stat.titleKey) }}</p>
                            <p class="web-text-active text-2xl font-bold">{{ stat.value }}</p>
                        </div>
                    </div>
                </div>

                <div class="web-border-color flex items-center justify-between gap-4 border-t pt-4">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1 text-xs">
                            <div
                                class="size-3.5 rounded-full border-2 border-[var(--bg_body_light)] dark:border-[var(--bg_body_dark)]"
                                :class="props.user.status === 'active' ? 'bg-green-500' : 'bg-yellow-400'"
                            ></div>

                            <span class="lowercase">{{ props.user.status === 'active' ? $t('active') : $t('inactive') }}</span>
                        </div>

                        <div class="flex items-center gap-1 text-xs">
                            <Crown v-if="props.user.role === 'owner'" class="size-3.5 text-yellow-500" />
                            <Shield v-else-if="props.user.role === 'admin'" class="web-text-active-link size-3.5" />
                            <UserIcon v-else class="web-text-body-muted size-3.5" />

                            <span class="lowercase">{{ props.user.role }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 text-xs web-text-body-muted">
                        <Calendar class="size-3.5" />
                        <span>{{ $t('joined') || 'Joined' }}: {{ formatters.date(props.user.created_at) }}</span>
                    </div>
                </div>
            </div>
        </section>
    </Layout>
</template>
