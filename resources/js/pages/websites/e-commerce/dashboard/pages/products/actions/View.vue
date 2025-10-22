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
            <!-- Product Details -->
            <div class="grid grid-cols-1 gap-4">
                <!-- General Information -->
                <div class="border-muted flex flex-col gap-3 border-b pb-4">
                    <h3 class="text-active-link text-xl font-bold">{{ props.data.name }}</h3>
                    <div class="flex items-center gap-2">
                        <div
                            class="text-active rounded bg-gradient-to-br from-lime-600 to-lime-500 px-3 py-1.5 text-sm dark:from-lime-700 dark:to-lime-800"
                        >
                            {{ props.data.category_name }}
                        </div>

                        <div
                            class="text-active rounded bg-gradient-to-br from-amber-600 to-amber-500 px-3 py-1.5 text-sm dark:from-amber-700 dark:to-amber-800"
                        >
                            {{ props.data.brand_name }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-y-4">
                    <!-- Pricing & Stock -->
                    <div class="border-muted flex flex-col gap-3 border-b pb-4 lg:border-e border-muted lg:pe-3">
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Price: <span class="text-active text-base font-medium">{{ props.data.price }}$</span>
                        </p>
                        <p v-if="props.data.sale_price" class="text-body-muted flex w-full items-center justify-between text-sm">
                            Sale Price: <span class="text-active text-base font-medium">{{ props.data.sale_price }}$</span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Views: <span class="text-active text-base font-medium">{{ props.data.views_count }}</span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Sales: <span class="text-active text-base font-medium">{{ props.data.sales_count }}</span>
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="border-muted flex flex-col gap-5 border-b pb-4 lg:ps-3">
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Is In Home: <span v-html="formatters.boolean(props.data.is_in_home)"></span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Is Special: <span v-html="formatters.boolean(props.data.is_special)"></span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Is Active: <span v-html="formatters.boolean(props.data.is_active)"></span>
                        </p>
                    </div>
                </div>

                <!-- Short Description -->
                <div v-if="props.data.short_description" class="border-muted gap-2-b flex flex-col border-b pb-4">
                    <h4 class="text-body-muted text-sm">Short Description:</h4>
                    <p class="text-active text-base font-medium whitespace-pre-line">{{ props.data.short_description }}</p>
                </div>

                <!-- Description -->
                <div v-if="props.data.description" class="border-muted flex flex-col gap-2 border-b pb-4">
                    <h4 class="text-body-muted text-sm font-semibold">Description:</h4>
                    <p class="text-active text-base font-medium whitespace-pre-line">{{ props.data.description }}</p>
                </div>
            </div>

            <!-- Variants Section -->
            <div class="mb-3 flex flex-col gap-2 rounded-md bg-[var(--primary)]/10 dark:bg-[var(--primary)]/5 p-2.5 lg:p-4">
                <h4 class="text-body font-bold">Product Variants:</h4>
                <div
                    v-if="props.data.variants && props.data.variants.length"
                    class="custom-scrollbar grid max-h-[60vh] md:grid-cols-2 gap-2.5 lg:gap-4 overflow-y-auto"
                >
                    <div v-for="variant in props.data.variants" :key="variant.id" class="bg-body flex flex-col gap-3 rounded-md p-2.5 lg:p-4">
                        <div class="border-muted flex w-full items-center gap-2 border-b pb-2">
                            <Image
                                v-if="variant.ecommerce_product_image"
                                :src="variant.ecommerce_product_image"
                                alt="Item Image"
                                class="size-18 rounded-full object-cover shadow"
                            />
                            <div
                                v-else
                                class="border-muted text-body-muted flex size-18 items-center justify-center rounded-full border text-center text-xs"
                            >
                                No Image
                            </div>
                        </div>
                        <div v-if="variant.color" class="flex items-center justify-between gap-2">
                            <span class="text-body-muted text-sm">Color:</span>
                            <div class="flex items-center gap-1">
                                <span :style="{ backgroundColor: variant.color }" class="border-muted size-5.5 rounded-full border shadow-sm"></span>
                                <span class="text-body-muted text-xs font-medium">{{ variant.color }}</span>
                            </div>
                        </div>
                        <p class="text-body-muted flex items-center justify-between gap-2 text-sm">
                            Stock Quantity: <span class="text-body text-base font-bold">{{ variant.stock_quantity }}</span>
                        </p>
                        <p class="text-body-muted flex items-center justify-between gap-2 text-sm">
                            Reserved Quantity: <span class="text-body text-base font-bold">{{ variant.reserved_quantity }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-body-muted relative z-10 flex items-center justify-between gap-1 px-4 pt-4 text-xs">
            Created At: <span class="font-medium">{{ formatters.date(props.data.created_at, 'long') }}</span>
        </p>
    </div>
</template>
