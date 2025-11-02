<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogDescription, DialogFooter, DialogHeader, DialogScrollContent, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { DataTableProps } from '@/composables/dataTable/useDataTable';
import { formatters } from '@/lib/dataTable';
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowUp } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    orders: DataTableProps;
    processings: boolean;
    handleDialogCancel: (orderId: number) => void;
}>();

const processing = computed(() => props.processings);

const page = usePage<SharedData>();
const handleStatusText = (status: string): string => {
    if (status === 'confirmed') {
        return page.props.lang === 'ar' ? 'تم التأكيد في:' : 'Confirmed At:';
    }
    if (status === 'delivered') {
        return page.props.lang === 'ar' ? 'تم التوصيل في:' : 'Delivered At:';
    }
    if (status === 'cancelled') {
        return page.props.lang === 'ar' ? 'تم الإلغاء في:' : 'Cancelled At:';
    }
    if (status === 'refunded') {
        return page.props.lang === 'ar' ? 'تم الاسترداد في:' : 'Refunded At:';
    }

    return '';
};
</script>

<template>
    <div
        v-for="order in props.orders.data"
        :key="order.id"
        class="web-border-color col-span-1 flex h-fit flex-col justify-between gap-4 rounded-md border bg-[var(--bg_card_light)] transition-all duration-300 dark:bg-[var(--bg_card_dark)]"
    >
        <div class="web-border-color flex w-full items-center justify-between border-b p-2">
            <p class="web-text-body-muted flex items-center gap-1 text-xs">
                #
                <span class="web-text-active-link text-[11px] font-medium">{{ order.order_number }}</span>
            </p>
            <p class="web-text-body text-xs font-medium uppercase">{{ order.payment_method.name }}</p>
        </div>

        <div class="flex flex-col gap-2 px-4">
            <div class="flex w-full items-center justify-between">
                <p class="web-text-body-muted text-xs">{{ $t('phone') }}</p>
                <p class="text-sm [direction:ltr]">{{ order.phone_number }}</p>
            </div>
            <div class="flex w-full items-center justify-between">
                <p class="web-text-body-muted text-xs">{{ $t('city') }}</p>
                <p class="text-sm">{{ order.city }}</p>
            </div>
            <div class="flex w-full items-center justify-between">
                <p class="web-text-body-muted text-xs">{{ $t('address') }}</p>
                <p class="text-sm">{{ order.delivery_address }}</p>
            </div>
            <div class="flex w-full items-center justify-between">
                <p class="web-text-body-muted text-xs">{{ $t('total.amount') }}</p>
                <p class="web-text-active text-sm font-bold">{{ order.total_amount }}$</p>
            </div>
            <div v-if="order.status !== 'pending'" class="flex w-full items-center justify-between">
                <p class="web-text-body-muted text-xs">{{ handleStatusText(order.status) }}</p>
                <p class="text-xs [direction:ltr]">{{ formatters.date(order.status_changed_at, 'long') }}</p>
            </div>
            <div v-if="order.status === 'cancelled' && order.cancellation_reason" class="flex w-full flex-col gap-1">
                <p class="web-text-body-muted text-xs">{{ $t('cancellation.reason') }}</p>
                <p class="text-xs">{{ order.cancellation_reason }}</p>
            </div>
        </div>

        <div class="web-border-color flex w-full items-center justify-between border-t p-2">
            <p class="web-text-body-muted text-xs [direction:ltr]">{{ formatters.date(order.created_at, 'long') }}</p>

            <div class="flex items-center gap-2">
                <Dialog>
                    <DialogTrigger as-child>
                        <Button type="button" size="sm" class="web-bg-primary web-text-for-primary h-fit rounded px-2 py-2 md:text-xs">
                            {{ $t('see.items') }} {{ order.items.length }}
                        </Button>
                    </DialogTrigger>

                    <DialogScrollContent class="web-bg-body web-border-color">
                        <DialogHeader class="web-bg-dropdown web-border-color">
                            <DialogTitle class="web-text-active">{{ $t('order.items') }} ({{ order.items.length }})</DialogTitle>
                            <DialogDescription class="sr-only"> No description provided. </DialogDescription>
                        </DialogHeader>

                        <div class="flex flex-col gap-3 p-4">
                            <div v-if="order.note" class="flex-flex-col gap-2">
                                <p class="web-text-body-muted text-sm">{{ $t('order.note') }}</p>
                                <p class="text-xs font-medium">{{ order.note }}</p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                                <div v-for="item in order.items" :key="item.id" class="group web-border-color flex rounded-md border">
                                    <div
                                        class="relative w-25 min-w-30 overflow-hidden bg-[var(--bg_content_light)] sm:rounded-s-md dark:bg-[var(--bg_content_dark)]"
                                    >
                                        <img
                                            v-if="item.imageUrl"
                                            :src="item.imageUrl"
                                            class="h-full transition-all duration-300 group-hover:scale-130 sm:w-full sm:rounded-s-md"
                                        />
                                        <div v-else class="flex h-full w-full flex-col items-center justify-center text-sm">
                                            <p class="web-text-body-muted">{{ $t('color') }}</p>
                                            <div class="flex items-center gap-1">
                                                <div
                                                    class="web-border-color size-4.5 rounded-full border"
                                                    :style="{ backgroundColor: item.color }"
                                                ></div>
                                                <span class="text-xs">{{ item.color }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex w-full flex-col gap-1 px-3 py-1">
                                        <Link
                                            :href="`/product/${encodeURIComponent(item.product.slug)}`"
                                            class="group/link web-text-active mb-1 flex w-fit items-center gap-1 text-xl font-bold"
                                        >
                                            {{ item.product.name }}
                                            <ArrowUp
                                                class="size-3.5 rotate-45 transition-transform duration-300 group-hover/link:translate-x-[2px] group-hover/link:-translate-y-[2px]"
                                            />
                                        </Link>
                                        <div class="web-text-body-muted flex w-full items-center justify-between gap-2 text-xs">
                                            <span>{{ $t('quantity') }}</span>

                                            <p class="web-text-active text-base font-medium">
                                                {{ item.quantity }}
                                            </p>
                                        </div>

                                        <div class="web-text-body-muted flex w-full items-center justify-between gap-2 text-xs">
                                            <span>{{ $t('total.price') }}</span>
                                            <span class="web-text-active-link text-base font-bold">{{ item.unit_price * item.quantity }}$</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <DialogFooter v-if="order.status === 'pending'" class="web-bg-dropdown web-border-color">
                            <Button
                                type="button"
                                variant="destructive"
                                size="sm"
                                :disabled="processing"
                                @click="handleDialogCancel(order.id)"
                                class="web-bg-danger web-text-for-danger h-fit rounded px-2 py-2 opacity-60 hover:opacity-100 md:text-xs"
                            >
                                {{ $t('cancel.order') }}
                            </Button>
                        </DialogFooter>
                    </DialogScrollContent>
                </Dialog>

                <Button
                    type="button"
                    v-if="order.status === 'pending'"
                    variant="destructive"
                    size="sm"
                    :disabled="processing"
                    @click="handleDialogCancel(order.id)"
                    class="web-bg-danger web-text-for-danger h-fit rounded px-2 py-2 opacity-60 hover:opacity-100 md:text-xs"
                >
                    {{ $t('cancel.order') }}
                </Button>
            </div>
        </div>
    </div>
</template>
