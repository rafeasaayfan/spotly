<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/fields';
import { toast } from '@/lib/sweetAlert';
import { SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { Ban, Info, LoaderCircle, ShoppingCart, TriangleAlert } from 'lucide-vue-next';
import { ref, watch, watchEffect } from 'vue';
import Highlight from '../components/cards/Highlight.vue';
import Layout from './Layout.vue';

const props = defineProps<{
    iniProduct: Record<string, any>;
    colors: Record<string, string>;
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
    iniCartItemsCount: number;
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info';
        message: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});

const product = ref<Record<string, any>>(props.iniProduct);
const cartItemsCount = ref<number>(props.iniCartItemsCount);

const page = usePage<SharedData>();

const selectedVariant = ref<Record<string, any> | null>(null);

const bigImage = ref<string>(
    selectedVariant.value
        ? selectedVariant.value.ecommerce_product_images[0]?.original_url
        : product.value.variants[0]?.ecommerce_product_images[0]?.original_url || '',
);

watch(selectedVariant, (newVariant) => {
    if (newVariant && newVariant.ecommerce_product_images && newVariant.ecommerce_product_images.length > 0) {
        bigImage.value = newVariant.ecommerce_product_images[0].original_url;
    } else if (
        product.value.variants &&
        product.value.variants.length > 0 &&
        product.value.variants[0].ecommerce_product_images &&
        product.value.variants[0].ecommerce_product_images.length > 0
    ) {
        bigImage.value = product.value.variants[0].ecommerce_product_images[0].original_url;
    }
});

const addToCartCondition = () => {
    if (selectedVariant.value && quantity.value && quantity.value > 0) {
        if (selectedVariant.value.display_quantity) {
            return selectedVariant.value.display_quantity >= quantity.value;
        }

        return true;
    }

    return false;
};

const quantity = ref<number>(1);

const processing = ref(false);
const addToCart = async () => {
    if (!selectedVariant.value) {
        return;
    }

    try {
        processing.value = true;

        const response = await axios.post(route('website.e-commerce.addToCart'), {
            product_id: product.value.id,
            quantity: quantity.value,
            variantId: selectedVariant.value.id,
        });

        if (response.data.props.product) {
            product.value = response.data.props.product;
            cartItemsCount.value = response.data.props.cartItemsCount;

            selectedVariant.value = null;

            toast.fire({ icon: 'success', title: response.data.message });
        }
    } catch (error: any) {
        console.log(error);

        toast.fire({ icon: 'error', title: error.response?.data.message });
    } finally {
        processing.value = false;
    }
};
</script>

<template>
    <Head :title="product.name" />

    <Layout
        :colors="props.colors"
        :websiteNameAndLogo="props.websiteNameAndLogo"
        :websiteFooterData="props.websiteFooterData"
        :cartItemsCount="cartItemsCount"
    >
        <section class="pt-22 pb-10">
            <div
                :class="[
                    'web-border-color border-s-4 border-e-4 border-double p-3 md:p-6',
                    product.variants ? '' : 'flex h-full w-full items-center justify-center',
                ]"
            >
                <div v-if="product.variants && product.variants.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-5">
                    <!-- Images -->
                    <div class="col-span-1 flex w-full flex-col gap-1 sm:col-span-2">
                        <div class="flex flex-col items-center gap-3">
                            <img
                                v-if="bigImage"
                                :src="bigImage"
                                class="max-h-55 min-h-55 w-80 rounded-md object-cover transition-all duration-300 sm:max-h-50 sm:min-h-50 sm:w-full md:max-h-65 md:min-h-65 lg:max-h-80 lg:min-h-80 xl:max-h-100 xl:min-h-100"
                            />

                            <div class="custom-scrollbar flex max-w-full flex-nowrap gap-1 overflow-x-auto">
                                <template v-for="variant in product.variants" :key="variant.id">
                                    <template v-if="variant.ecommerce_product_images">
                                        <div
                                            v-for="image in variant.ecommerce_product_images"
                                            :key="image.id"
                                            class="group h-20 min-h-20 w-30 min-w-30 cursor-pointer items-center justify-center overflow-hidden rounded border-4 border-double border-[var(--border_color_light)] hover:border-[var(--primary_light)] lg:h-24 lg:min-h-24 lg:w-35 lg:min-w-35 dark:border-[var(--border_color_dark)] dark:hover:border-[var(--primary_dark)]"
                                            :class="selectedVariant ? (selectedVariant.id === variant.id ? 'flex' : 'hidden') : 'flex'"
                                            @click="bigImage = image.original_url"
                                        >
                                            <img :src="image.original_url" class="transition-all duration-300 group-hover:scale-130" alt="" />
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="col-span-1 flex flex-col gap-4 sm:col-span-3">
                        <!-- Product Name and Discount -->
                        <div class="web-border-color flex flex-col border-b pb-4">
                            <div class="flex w-full items-center justify-between gap-3">
                                <h2 class="web-text-active text-2xl font-bold md:text-4xl">{{ product.name }}</h2>

                                <div
                                    v-if="product.is_discount && product.discount_price && product.price"
                                    class="web-bg-danger relative flex items-center gap-1 rounded px-2 py-1.5 text-xs font-medium text-white md:px-3"
                                >
                                    <span v-if="selectedVariant?.price">
                                        {{
                                            Math.round(
                                                ((Number(selectedVariant?.price) - Number(product.discount_price)) / Number(selectedVariant?.price)) *
                                                    100,
                                            )
                                        }}%
                                    </span>
                                    <span v-else>
                                        {{ Math.round(((Number(product.price) - Number(product.discount_price)) / Number(product.price)) * 100) }}%
                                    </span>
                                    <span>{{ $t('off') }}</span>
                                </div>
                            </div>

                            <p v-if="product.short_description" class="web-text-body-muted text-sm">{{ product.short_description }}</p>
                            <p v-if="product.description" class="web-text-body-muted mt-1.5 text-sm">{{ product.description }}</p>

                            <div v-if="product.category || product.brand" class="mt-4 flex items-center gap-3">
                                <Highlight
                                    v-if="product.category"
                                    :text="page.props.lang === 'ar' ? product.category.ar_name : product.category.name"
                                    type="category"
                                    class="web-border-color px-2 py-1.5 text-xs md:px-3 md:text-sm"
                                />
                                <Highlight
                                    v-if="product.brand"
                                    :text="product.brand.name"
                                    type="brand"
                                    class="web-border-color px-2 py-1.5 text-xs md:px-3 md:text-sm"
                                />
                            </div>
                        </div>

                        <!-- Variants -->
                        <div class="flex flex-col" v-if="product.variants && product.variants.length > 0">
                            <span
                                class="web-text-active-link flex w-fit items-center gap-1.5 rounded-md bg-[var(--bg_content_light)] px-3 py-2 text-xs dark:bg-[var(--bg_content_dark)]"
                            >
                                <Info class="size-3.5" />
                                {{ $t('choose.order') }}
                            </span>

                            <div class="custom-scrollbar flex max-w-full gap-3 overflow-x-auto pt-4">
                                <div
                                    v-for="variant in product.variants"
                                    :key="variant.id"
                                    @click="selectedVariant = variant"
                                    class="web-text-body flex min-w-45 cursor-pointer flex-col gap-2 rounded-md border-2 border-[var(--border_color_light)] bg-black/2 px-2 py-2 transition-all duration-300 hover:-translate-y-0.5 hover:border-[var(--primary_light)] dark:border-[var(--border_color_dark)] dark:bg-white/2 dark:hover:border-[var(--primary_dark)]"
                                    :class="[
                                        selectedVariant?.id === variant.id
                                            ? '-translate-y-1 border-[var(--primary_light)] hover:-translate-y-1 dark:border-[var(--primary_dark)]'
                                            : '',
                                        variant.display_quantity === 0 ? 'pointer-events-none relative' : '',
                                    ]"
                                >
                                    <div
                                        v-if="variant.display_quantity === 0"
                                        class="absolute inset-0 z-10 flex h-full w-full items-center justify-center"
                                    >
                                        <div
                                            class="flex -rotate-18 items-center gap-1 rounded-md bg-red-500/10 p-2 text-sm backdrop-blur-lg dark:bg-red-500/10"
                                        >
                                            <Ban class="web-text-danger size-3.5" />
                                            <span class="web-text-danger font-bold">{{ $t('out.of.stock') }}</span>
                                        </div>
                                    </div>

                                    <div
                                        class="web-border-color flex flex-col gap-2 border-b pb-1"
                                        v-if="variant.display_quantity !== null || variant.price !== null"
                                        :class="variant.display_quantity === 0 ? 'pointer-events-none opacity-50 blur-[1px]' : ''"
                                    >
                                        <div class="flex w-full items-center justify-between" v-if="variant.display_quantity !== null">
                                            <span class="web-text-body-muted text-[10px] uppercase"> {{ $t('quantity') }}: </span>
                                            <span class="web-text-active text-sm font-bold">
                                                {{ variant.display_quantity }}
                                            </span>
                                        </div>
                                        <div class="flex w-full items-center justify-between" v-if="variant.price !== null">
                                            <span class="web-text-body-muted text-[10px] uppercase">
                                                {{ $t('unit.price') }}
                                            </span>
                                            <span class="web-text-active text-sm font-bold"> {{ variant.price }}$ </span>
                                        </div>
                                    </div>

                                    <div
                                        v-for="attribute in variant.attributes"
                                        :key="attribute.id"
                                        class="flex items-center justify-between"
                                        :class="variant.display_quantity === 0 ? 'pointer-events-none opacity-50 blur-[1px]' : ''"
                                    >
                                        <span class="web-text-body-muted text-[10px] uppercase"
                                            >{{ page.props.lang === 'ar' ? attribute.attribute_name_ar : attribute.attribute_name }}:</span
                                        >
                                        <div class="flex flex-wrap items-center gap-2 text-sm font-medium">
                                            <span v-if="attribute.attribute_value_id">{{
                                                page.props.lang === 'ar' ? attribute.attribute_value_value_ar : attribute.attribute_value_value
                                            }}</span>
                                            <span v-else-if="attribute.color_id" class="flex items-center gap-1">
                                                <div
                                                    class="web-border-color size-4 rounded-full border"
                                                    :style="{ backgroundColor: attribute.color_code }"
                                                ></div>
                                                {{ page.props.lang === 'ar' ? attribute.color_name_ar : attribute.color_name }}
                                            </span>
                                            <span v-else>{{
                                                page.props.lang === 'ar' ? attribute.attribute_value_value_ar : attribute.attribute_value_value
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div v-if="selectedVariant" class="flex flex-col gap-1">
                            <span class="web-text-body-muted text-xs">{{ $t('quantity') }}</span>

                            <div class="flex flex-wrap items-center gap-1">
                                <Input
                                    type="number"
                                    v-model="quantity"
                                    class="web-bg-field web-text-active web-border-muted w-50"
                                    min="1"
                                    :max="selectedVariant.display_quantity"
                                />
                                <span
                                    v-if="selectedVariant.display_quantity"
                                    class="flex items-center gap-1 rounded bg-yellow-600/20 px-2 py-1.5 text-sm text-yellow-600"
                                >
                                    <TriangleAlert class="size-4" />
                                    {{ $t('max') }} {{ selectedVariant.display_quantity }}
                                </span>
                            </div>
                        </div>

                        <!-- Unit Price -->
                        <div class="web-border-color flex flex-col gap-2 border-t pt-4">
                            <div class="web-bg-content flex w-full items-center justify-between gap-4 rounded-md px-2 py-2 md:px-4">
                                <span class="web-text-body-muted text-xs md:text-sm">{{ $t('unit.price') }}</span>
                                <div class="flex items-center gap-1 md:gap-2">
                                    <span v-if="selectedVariant?.price" class="web-text-active text-sm font-bold md:text-base">
                                        {{ product.is_discount ? selectedVariant.price - product.discount_price : selectedVariant.price }}$
                                    </span>
                                    <span v-else class="web-text-active text-sm font-bold md:text-base">
                                        {{ product.is_discount ? product.price - product.discount_price : product.price }}$
                                    </span>
                                    <span v-if="product.is_discount" class="web-text-body-muted text-sm line-through">
                                        {{ selectedVariant?.price ?? product.price }}$
                                    </span>
                                </div>
                            </div>

                            <div
                                v-if="addToCartCondition()"
                                class="flex w-full items-center justify-between gap-4 rounded-md bg-[var(--bg_content_hover_light)] px-2 py-2 md:px-4 dark:bg-[var(--bg_content_hover_dark)]"
                            >
                                <span class="web-text-body text-xs md:text-sm">{{ $t('total.price') }}</span>
                                <div class="flex items-center gap-1 md:gap-2">
                                    <span v-if="selectedVariant?.price" class="web-text-active text-base font-bold md:text-lg">
                                        {{
                                            product.is_discount
                                                ? (selectedVariant.price - product.discount_price) * quantity
                                                : selectedVariant.price * quantity
                                        }}$
                                    </span>
                                    <span v-else class="web-text-active text-base font-bold md:text-lg">
                                        {{ product.is_discount ? (product.price - product.discount_price) * quantity : product.price * quantity }}$
                                    </span>
                                    <span v-if="product.is_discount" class="web-text-body-muted text-sm line-through">
                                        {{ selectedVariant?.price ? selectedVariant.price * quantity : product.price * quantity }}$
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Add to Cart -->
                        <div class="web-border-color flex w-full justify-end border-t pt-4" v-if="!product.is_out_of_stock">
                            <Button
                                type="button"
                                class="web-bg-primary web-text-for-primary eco-glow-button"
                                @click="addToCart"
                                :disabled="!addToCartCondition() || processing"
                            >
                                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                                <span v-if="processing">{{ $t('adding') }}</span>

                                <ShoppingCart v-if="!processing" class="h-4 w-4" />
                                <span v-if="!processing">{{ $t('add.to.cart') }}</span>
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </Layout>
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
