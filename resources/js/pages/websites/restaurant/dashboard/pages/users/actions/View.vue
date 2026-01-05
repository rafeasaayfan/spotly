const columns = [ { key: 'role', label: 'Role', type: 'status' }, ];

<script setup lang="ts">
import { formatters } from '@/lib/dataTable';
import { Crown, Shield, User } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
}>();
</script>

<template>
    <div class="relative pb-3">
        <div class="border-muted relative z-10 grid grid-cols-1 gap-5 border-b px-4">
            <!-- User Details -->
            <div class="border-muted grid gap-4">
                <!-- General Information -->
                <div class="border-muted flex flex-col gap-2 border-b pb-4">
                    <div class="flex items-center gap-2">
                        <h3 class="text-active-link text-2xl font-bold">{{ props.data.name }}</h3>
                        <div
                            class="flex items-center gap-1 rounded px-2 py-1 text-xs font-medium"
                            :class="
                                props.data.role === 'owner'
                                    ? 'bg-yellow-500/15 text-yellow-600'
                                    : props.data.role === 'admin'
                                      ? 'bg-blue-500/15 text-blue-600'
                                      : 'text-active bg-content border-muted border'
                            "
                        >
                            <Crown v-if="props.data.role === 'owner'" class="size-3" />
                            <Shield v-else-if="props.data.role === 'admin'" class="size-3" />
                            <User v-else class="size-3" />
                            <span>{{ props.data.role }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <div v-html="formatters.emailVerified(props.data.email_verified_at)"></div>
                        <div v-html="formatters.status(props.data.status)"></div>
                    </div>
                </div>

                <div class="border-muted border-muted flex flex-col gap-3 border-b pb-4">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Email:
                        <a :href="`mailto:${props.data.email}`" class="text-body text-base font-medium">
                            {{ props.data.email }}
                        </a>
                    </p>
                    <p v-if="props.data.phone_number" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Phone Number:
                        <a :href="`tel:${props.data.phone_number}`" class="text-body text-base font-medium">
                            {{ props.data.phone_number }}
                        </a>
                    </p>
                </div>

                <div
                    v-if="
                        props.data.cart_count !== undefined ||
                        props.data.pending_orders_count !== undefined ||
                        props.data.confirmed_orders_count !== undefined ||
                        props.data.delivered_orders_count !== undefined ||
                        props.data.cancelled_orders_count !== undefined ||
                        props.data.refunded_orders_count !== undefined ||
                        props.data.rejected_orders_count !== undefined
                    "
                    class="border-muted flex flex-col gap-3 border-b pb-4"
                >
                    <div v-if="props.data.cart_count !== undefined" class="text-body-muted flex items-center justify-between gap-3 text-sm">
                        Cart Items Count:
                        <span class="text-body text-base">{{ props.data.cart_count }}</span>
                    </div>
                    <div v-if="props.data.pending_orders_count !== undefined" class="text-body-muted flex items-center justify-between gap-3 text-sm">
                        Pending Orders Count:
                        <span class="text-body text-base">{{ props.data.pending_orders_count }}</span>
                    </div>
                    <div
                        v-if="props.data.confirmed_orders_count !== undefined"
                        class="text-body-muted flex items-center justify-between gap-3 text-sm"
                    >
                        Confirmed Orders Count:
                        <span class="text-base text-green-500">{{ props.data.confirmed_orders_count }}</span>
                    </div>
                    <div
                        v-if="props.data.delivered_orders_count !== undefined"
                        class="text-body-muted flex items-center justify-between gap-3 text-sm"
                    >
                        Delivered Orders Count:
                        <span class="text-base text-blue-500">{{ props.data.delivered_orders_count }}</span>
                    </div>
                    <div
                        v-if="props.data.cancelled_orders_count !== undefined"
                        class="text-body-muted flex items-center justify-between gap-3 text-sm"
                    >
                        Cancelled Orders Count:
                        <span class="text-base text-rose-500">{{ props.data.cancelled_orders_count }}</span>
                    </div>
                    <div
                        v-if="props.data.refunded_orders_count !== undefined"
                        class="text-body-muted flex items-center justify-between gap-3 text-sm"
                    >
                        Refunded Orders Count:
                        <span class="text-base text-orange-500">{{ props.data.refunded_orders_count }}</span>
                    </div>
                    <div
                        v-if="props.data.rejected_orders_count !== undefined"
                        class="text-body-muted flex items-center justify-between gap-3 text-sm"
                    >
                        Rejected Orders Count:
                        <span class="text-base text-red-500">{{ props.data.rejected_orders_count }}</span>
                    </div>
                </div>
                <div v-if="props.data.total_spent !== undefined" class="text-body-muted flex items-center justify-between gap-3 pb-4 text-sm">
                    Total Spent:
                    <span class="text-active text-base">{{ props.data.total_spent ?? 0 }}$</span>
                </div>
            </div>
        </div>

        <p class="text-body-muted flex items-center justify-between gap-1 px-4 pt-4 text-xs">
            Created At: <span class="font-medium">{{ formatters.date(props.data.created_at, 'long') }}</span>
        </p>
        <p class="text-body-muted flex items-center justify-between gap-1 px-4 pt-4 text-xs">
            Updated At: <span class="font-medium">{{ formatters.date(props.data.updated_at, 'long') }}</span>
        </p>
    </div>
</template>
