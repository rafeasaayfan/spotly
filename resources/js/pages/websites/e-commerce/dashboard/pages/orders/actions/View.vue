<script setup lang="ts">
import Image from '@/components/ui/image/Image.vue';
import { formatters } from '@/lib/dataTable';

const props = defineProps<{
    data: Record<string, any>;
}>();
</script>

<template>
    <div class="relative pb-3">
        <div class="border-muted relative z-10 grid grid-cols-1 gap-5 border-b px-4">
            <!-- Order Details -->
            <div class="border-muted grid gap-4">
                <!-- General Information -->
                <div class="border-muted flex flex-col gap-3 border-b pb-4">
                    <h3 class="text-active-link text-xl font-bold">{{ props.data.order_number }}</h3>
                    <div class="flex items-center gap-2">
                        <div
                            class="text-active rounded bg-gradient-to-br from-lime-600 to-lime-500 px-3 py-1.5 text-sm dark:from-lime-700 dark:to-lime-800"
                        >
                            {{ props.data.websiteUser_name }}
                        </div>

                        <div
                            class="text-active rounded bg-gradient-to-br from-amber-600 to-amber-500 px-3 py-1.5 text-sm dark:from-amber-700 dark:to-amber-800"
                        >
                            {{ props.data.paymentMethod_name }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-y-4">
                    <!-- Pricing & Quantity -->
                    <div class="border-muted flex flex-col gap-3 border-b pb-4 lg:border-e border-muted lg:pe-3">
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Subtotal: <span class="text-body text-base font-medium">{{ props.data.subtotal }}$</span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Discount Amount: <span class="text-body text-base font-medium">{{ props.data.discount_amount }}$</span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Delivery Amount: <span class="text-body text-base font-medium">{{ props.data.deliveryFee_amount }}$</span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Total Amount: <span class="text-active text-base font-medium">{{ props.data.total_amount }}$</span>
                        </p>
                    </div>

                    <div class="border-muted flex flex-col gap-3 border-b pb-4 lg:ps-3">
                        <p class="text-body-muted flex flex-col w-full text-sm">
                            Delivery Address: <span class="text-body text-base font-medium">{{ props.data.delivery_address }}</span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            City: <span class="text-body text-base font-medium">{{ props.data.city }}</span>
                        </p>
                    </div>
                </div>

                <!-- Status -->
                <div class="border-muted flex flex-col gap-3 border-b pb-4">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Status: <span v-html="formatters.status(props.data.status)"></span>
                    </p>
                    <p v-if="props.data.status_changed_at" class="text-body-muted flex w-full items-center justify-between text-sm">
                        {{ props.data.status.charAt(0).toUpperCase() + props.data.status.slice(1) }} At:
                        <span v-html="formatters.date(props.data.status_changed_at, 'long')"></span>
                    </p>
                </div>

                <!-- Note -->
                <div v-if="props.data.note" class="border-muted gap-2-b flex flex-col pb-4" :class="props.data.description ? 'border' : ''">
                    <h4 class="text-body-muted text-sm">Note:</h4>
                    <p class="text-active text-base font-medium whitespace-pre-line">{{ props.data.note }}</p>
                </div>

                <!-- cancellation_reason -->
                <div v-if="props.data.cancellation_reason" class="border-muted flex flex-col gap-2 border-b pb-4">
                    <h4 class="text-body-muted text-sm font-semibold">Cancellation Reason:</h4>
                    <p class="text-active text-base font-medium whitespace-pre-line">{{ props.data.cancellation_reason }}</p>
                </div>
            </div>

            <!-- Order Items Section -->
            <div class="mb-3 flex flex-col gap-2 rounded-md bg-[var(--primary)]/10 dark:bg-[var(--primary)]/5 p-2.5 lg:p-4">
                <h4 class="text-body font-bold">Order Items:</h4>
                <div v-if="props.data.items && props.data.items.length" class="custom-scrollbar grid max-h-[60vh] md:grid-cols-2 gap-2.5 lg:gap-4 overflow-y-auto">
                    <div v-for="item in props.data.items" :key="item.id" class="bg-body flex flex-col gap-3 rounded-md p-2.5 lg:p-4">
                        <div class="border-muted flex w-full items-center gap-2 border-b pb-2">
                            <Image
                                v-if="item.product_image_path"
                                :src="item.product_image_path"
                                alt="Item Image"
                                class="size-18 rounded-full object-cover shadow"
                            />
                            <div
                                v-else
                                class="border-muted text-body-muted flex size-18 items-center justify-center rounded-full border text-center text-xs"
                            >
                                No Image
                            </div>
                            <p class="text-active-link font-bold">{{ item.product.name }}</p>
                        </div>

                        <div v-if="item.color" class="flex items-center justify-between gap-2">
                            <span class="text-body-muted text-sm">Color:</span>
                            <div class="flex items-center gap-1">
                                <span :style="{ backgroundColor: item.color }" class="border-muted size-5.5 rounded-full border shadow-sm"></span>
                                <span class="text-body-muted text-xs font-medium">{{ item.color }}</span>
                            </div>
                        </div>
                        <p class="text-body-muted flex items-center justify-between gap-2 text-sm">
                            Quantity: <span class="text-body text-base font-bold">{{ item.quantity }}</span>
                        </p>
                        <p class="text-body-muted flex items-center justify-between gap-2 text-sm">
                            Unit Price: <span class="text-body text-base font-bold">{{ item.unit_price }}$</span>
                        </p>
                        <p class="text-body-muted flex items-center justify-between gap-2 text-sm">
                            Total Price: <span class="text-active text-base font-bold">{{ item.total_price }}$</span>
                        </p>

                        <div v-if="item.note" class="border-muted flex flex-col gap-1 border-t pt-2">
                            <p class="text-body-muted text-sm">Note:</p>
                            <p class="text-body text-sm font-medium whitespace-pre-line">{{ item.note }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-body-muted relative z-10 flex items-center justify-between gap-1 px-4 pt-4 text-xs">
            Created At: <span class="font-medium">{{ formatters.date(props.data.created_at, 'long') }}</span>
        </p>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 1px;
    height: 1px;
    scrollbar-width: thin;
    background: transparent !important;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent !important;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: transparent !important;
}
</style>
