<script setup lang="ts">
import Image from '@/components/ui/image/Image.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { formatters } from '@/lib/dataTable';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/dashboard/products',
    },
    {
        title: 'View',
        href: '/dashboard/products/view',
    },
];

const props = defineProps<{
    data: Record<string, any>;
    websiteNameAndLogo: Record<string, any>;
}>();
</script>

<template>
    <Head title="View product" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="e-commerce" :websiteNameAndLogo="props.websiteNameAndLogo">
        <div class="relative p-2 md:p-4">
            <div class="border-muted relative z-10 grid grid-cols-1 gap-5 border-b">
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
                            <div class="text-body-muted flex w-full items-center justify-between text-sm">
                                Base Price:
                                <div class="text-active flex items-center gap-1 text-base font-medium md:gap-2">
                                    <span> {{ props.data.is_discount ? props.data.price - props.data.discount_price : props.data.price }}$ </span>
                                    <span v-if="props.data.is_discount" class="text-body-muted text-sm line-through"> {{ props.data.price }}$ </span>
                                </div>
                            </div>
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
                <div class="mb-3 flex flex-col gap-2">
                    <h4 class="text-body font-bold">Product Variants:</h4>
                    <div
                        v-if="props.data.variants && props.data.variants.length > 0"
                        class="grid max-w-full gap-2.5 md:grid-cols-3 lg:gap-4"
                    >
                        <div
                            v-for="variant in props.data.variants"
                            :key="variant.id"
                            class="bg-[var(--primary)]/10 dark:bg-[var(--primary)]/8 rounded-md flex flex-col gap-3 rounded-md px-2.5 pb-2.5 lg:px-4 lg:pb-4"
                        >
                            <div
                                class="border-muted custom-scrollbar flex w-full max-w-full items-center gap-2 overflow-x-auto border-b pt-2 pb-2 lg:pt-4"
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
                                <p v-if="variant.stock_quantity" class="text-body-muted flex items-center justify-between gap-2 text-xs">
                                    Stock Quantity: <span class="text-body text-sm font-bold">{{ variant.stock_quantity }}</span>
                                </p>
                                <p v-if="variant.reserved_quantity" class="text-body-muted flex items-center justify-between gap-2 text-xs">
                                    Reserved Quantity: <span class="text-body text-sm font-bold">{{ variant.reserved_quantity }}</span>
                                </p>
                                <div class="text-body-muted flex items-center justify-between gap-2 text-xs">
                                    Price:

                                    <div class="text-active flex items-center gap-1 text-base font-medium md:gap-2">
                                        <span v-if="variant.price">
                                            {{ props.data.is_discount ? variant.price - props.data.discount_price : variant.price }}$
                                        </span>
                                        <span v-else>
                                            {{ props.data.is_discount ? props.data.price - props.data.discount_price : props.data.price }}$
                                        </span>
                                        <span v-if="props.data.is_discount" class="text-body-muted text-sm line-through"
                                            >{{ variant.price ?? props.data.price }}$
                                        </span>
                                    </div>
                                </div>
                                <p
                                    v-for="attribute in variant.attributes"
                                    :key="attribute.id"
                                    class="text-body-muted flex items-center justify-between gap-2 text-xs"
                                >
                                    <span class="capitalize">{{ attribute.attribute_name }}: </span>

                                    <span v-if="attribute.color_id" class="text-body flex items-center gap-1 text-sm font-bold">
                                        <span
                                            :style="{ backgroundColor: attribute.color.code }"
                                            class="border-muted size-4 rounded-full border shadow-sm"
                                        >
                                        </span>
                                        {{ attribute.color.name }}
                                    </span>

                                    <span v-else class="text-body text-sm font-bold">
                                        {{ attribute.attribute_value_value }}
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
    </DashboardLayout>
</template>
