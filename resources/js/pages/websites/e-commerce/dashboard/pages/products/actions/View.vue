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
                    <div class="flex items-center gap-2" v-if="props.data.category_name || props.data.brand_name">
                        <div
                            v-if="props.data.category_name"
                            class="rounded bg-gradient-to-br from-lime-600 to-lime-500 px-3 py-1.5 text-sm text-white dark:from-lime-700 dark:to-lime-800"
                        >
                            {{ props.data.category_name }}
                        </div>

                        <div
                            v-if="props.data.brand_name"
                            class="rounded bg-gradient-to-br from-amber-600 to-amber-500 px-3 py-1.5 text-sm text-white dark:from-amber-700 dark:to-amber-800"
                        >
                            {{ props.data.brand_name }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-y-4 lg:grid-cols-2">
                    <!-- Pricing & Stock -->
                    <div class="border-muted border-muted flex flex-col gap-4 border-b pb-4 lg:border-e lg:pe-3">
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Base Price: <span class="text-active text-base font-medium">{{ props.data.price }}$</span>
                        </p>
                        <p v-if="props.data.discount_price" class="text-body-muted flex w-full items-center justify-between text-sm">
                            Discount Price: <span class="text-active text-base font-medium">{{ props.data.discount_price }}$</span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Views Count: <span class="text-active text-base font-medium">{{ props.data.views_count }}</span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Sales Count: <span class="text-active text-base font-medium">{{ props.data.sales_count }}</span>
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="border-muted flex flex-col gap-5 border-b pb-4 lg:ps-3">
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Is Discount: <span v-html="formatters.boolean(props.data.is_discount)"></span>
                        </p>
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
            <div class="mb-3 flex flex-col gap-2 rounded-md bg-[var(--primary)]/10 p-2.5 lg:p-4 dark:bg-[var(--primary)]/5">
                <h4 class="text-body font-bold">Product Variants:</h4>
                <div
                    v-if="props.data.variants && props.data.variants.length > 0"
                    class="custom-scrollbar grid max-h-[60vh] max-w-full gap-2.5 overflow-y-auto md:grid-cols-2 lg:gap-4"
                >
                    <div
                        v-for="variant in props.data.variants"
                        :key="variant.id"
                        class="bg-body flex flex-col gap-3 rounded-md px-2.5 pb-2.5 lg:px-4 lg:pb-4"
                    >
                        <div
                            class="border-muted custom-scrollbar flex w-full max-w-full items-center gap-2 
                            overflow-x-auto border-b pt-2 pb-2 lg:pt-4"
                        >
                            <Image
                                v-for="image in variant.ecommerce_product_images"
                                :key="image.uid"
                                :src="image.original_url"
                                alt="Item Image"
                                class="max-h-17 min-h-17 max-w-17 min-w-17 flex-shrink-0 rounded-full object-cover shadow"
                            />
                            <!-- <div
                                v-else
                                class="border-muted text-body-muted flex size-18 items-center justify-center rounded-full border text-center text-xs"
                            >
                                No Image
                            </div> -->
                        </div>
                        <div class="flex flex-col gap-1">
                            <p class="text-body-muted flex items-center justify-between gap-2 text-xs">
                                Stock Quantity: <span class="text-body text-sm font-bold">{{ variant.stock_quantity }}</span>
                            </p>
                            <p class="text-body-muted flex items-center justify-between gap-2 text-xs">
                                Reserved Quantity: <span class="text-body text-sm font-bold">{{ variant.reserved_quantity }}</span>
                            </p>
                            <p class="text-body-muted flex items-center justify-between gap-2 text-xs">
                                Price: <span class="text-body text-sm font-bold">{{ variant.price }}$</span>
                            </p>
                            <p v-for="attribute in variant.attributes" :key="attribute.id" class="text-body-muted flex items-center justify-between gap-2 text-xs">
                                <span class="capitalize">{{ attribute.attribute_name }}: </span>

                                <span v-if="attribute.color_id" class="text-body text-sm font-bold flex items-center gap-1">
                                    <span :style="{ backgroundColor: attribute.color.code }" 
                                        class="border-muted size-4 rounded-full border shadow-sm">
                                    </span>
                                    {{ attribute.color.name }}
                                </span>

                                <span v-else class="text-body text-sm font-bold">
                                    {{ attribute.attribute_value.value }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-body-muted relative flex items-center justify-between gap-1 px-4 pt-4 text-xs">
            Created At: <span class="font-medium">{{ formatters.date(props.data.created_at, 'long') }}</span>
        </p>
        <p class="text-body-muted relative flex items-center justify-between gap-1 px-4 pt-3 text-xs">  
            Updated At: <span class="font-medium">{{ formatters.date(props.data.updated_at, 'long') }}</span>
        </p>
    </div>
</template>
