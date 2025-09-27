<script setup lang="ts">
import Image from '@/components/ui/image/Image.vue';
import { formatters } from '@/lib/dataTable';

const props = defineProps<{
    data: Record<string, any>;
}>();
</script>

<template>
    <div class="relative pb-3">
        <div class="absolute inset-0 top-0 start-0 bg-gradient-to-br from-transparent via-[var(--background)] to-transparent z-0 
        pointer-events-none opacity-30 blur-xl"></div>

        <div class="relative z-10 border-muted grid grid-cols-1 gap-5 border-b px-4 lg:grid-cols-3">
            <!-- Product Details -->
            <div class="border-muted grid grid-cols-1 gap-4 border-e pe-4 lg:col-span-2">
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

                <!-- Pricing & Stock -->
                <div class="border-muted flex flex-col gap-3 border-b pb-4">
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

                <!-- Status & Dates -->
                <div class="border-muted flex flex-col gap-3 border-b pb-4">
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

                <!-- Short Description -->
                <div v-if="props.data.short_description" class="border-muted flex flex-col gap-2-b pb-4" :class="props.data.description ? 'border' : ''">
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
            <div class="flex flex-col gap-2">
                <h4 class="text-body-muted font-bold border-b border-muted pb-2">Variants:</h4>
                <div class="flex max-h-[60vh] flex-col gap-2.5 overflow-y-auto lg:col-span-1">
                    <div v-if="props.data.variants && props.data.variants.length" class="grid grid-cols-1 gap-4">
                        <div
                            v-for="variant in props.data.variants"
                            :key="variant.id"
                            class="border-muted flex flex-col items-center gap-2 border-b py-2"
                        >
                            <Image
                                v-if="variant.ecommerce_product_image"
                                :src="variant.ecommerce_product_image"
                                alt="Variant Image"
                                class="h-24 w-24 rounded-full object-cover shadow"
                            />
                            <p
                                v-else
                                class="border-muted text-body-muted flex h-24 w-24 items-center justify-center rounded-full border text-center text-sm"
                            >
                                No Image
                            </p>
                            <div v-if="variant.color" class="flex items-center gap-2">
                                <span
                                    :style="{ backgroundColor: variant.color }"
                                    class="h-5 w-5 rounded-full border border-gray-300 shadow-sm"
                                ></span>
                                <span class="text-body-muted text-sm font-medium">{{ variant.color }}</span>
                            </div>
                            <p class="text-body-muted text-sm">
                                Stock: <span class="text-active text-sm font-bold">{{ variant.stock_quantity }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="relative z-10 text-body-muted flex items-center justify-between gap-1 text-xs px-4 pt-4">
            Created At: <span class="font-medium">{{ formatters.date(props.data.created_at, 'long') }}</span>
        </p>
    </div>
</template>
