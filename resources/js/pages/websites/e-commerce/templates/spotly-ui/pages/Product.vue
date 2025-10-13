<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { toast } from '@/lib/sweetAlert';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { Ban, LoaderCircle, ShoppingCart, TriangleAlert } from 'lucide-vue-next';
import { ref, watchEffect } from 'vue';
import Highlight from '../components/cards/Highlight.vue';
import Layout from './Layout.vue';

const props = defineProps<{
    iniProduct: Record<string, any>;
    colors: Record<string, string>;
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
    cartItemsCount: number;
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

const page = usePage<SharedData>();

const selectedVariant = ref<Record<string, any> | null>(null);
const quantity = ref<number>(1);

const processing = ref(false);
const addToCart = async () => {
    if (!selectedVariant.value) {
        return;
    }

    try {
        processing.value = true;

        const response = await axios.post(route('website.e-commerce.addToCart'), {
            slug: product.value.slug,
            product_id: product.value.id,
            quantity: quantity.value,
            imageUrl: selectedVariant.value.ecommerce_product_image,
            color: selectedVariant.value.color,
            unit_price: product.value.sale_price ?? product.value.price,
        });

        if (response.data.props.product) {
            product.value = response.data.props.product;
            
            selectedVariant.value = response.data.props.product.in_stock_variants.find((v: any) => v.id === selectedVariant.value!.id) || null;

            toast.fire({ icon: 'success', title: response.data.message });
        }
    } catch (error: any) {
        console.error(error.response?.data || error);
    } finally {
        processing.value = false;
    }
};
</script>

<template>
    <Layout :colors="props.colors" :websiteNameAndLogo="props.websiteNameAndLogo" :websiteFooterData="props.websiteFooterData" 
        :cartItemsCount="props.cartItemsCount"
    >
        <section class="pt-22">
            <div
                :class="[
                    'bg-black-1 rounded p-3 backdrop-blur md:p-6 dark:bg-white/1',
                    product.in_stock_variants ? '' : 'flex h-full w-full items-center justify-center',
                ]"
            >
                <div v-if="product.in_stock_variants && product.in_stock_variants.length > 0" class="flex flex-col gap-4">
                    <div class="web-border-color flex flex-col border-b pb-4">
                        <div class="flex w-full items-center justify-between gap-3">
                            <h2 class="web-text-active text-2xl font-bold md:text-3xl">{{ product.name }}</h2>

                            <div
                                v-if="product.sale_price && product.price"
                                class="web-bg-danger relative flex items-center gap-1 rounded px-2 py-1.5 text-xs font-medium text-white md:px-3"
                            >
                                <span> {{ Math.round(((Number(product.price) - Number(product.sale_price)) / Number(product.price)) * 100) }}% </span>
                                <span>{{ $t('off') }}</span>
                            </div>
                        </div>

                        <p class="web-text-body-muted text-sm">{{ product.short_description }}</p>
                        <p v-if="product.description" class="web-text-body-muted mt-1.5 text-sm">{{ product.description }}</p>

                        <div class="mt-4 flex items-center gap-3">
                            <Highlight
                                :text="page.props.lang === 'ar' ? product.category.ar_name : product.category.name"
                                type="category"
                                class="web-border-color px-2 py-1.5 text-xs md:px-3 md:text-sm"
                            />
                            <Highlight :text="product.brand.name" type="brand" class="web-border-color px-2 py-1.5 text-xs md:px-3 md:text-sm" />
                        </div>
                    </div>

                    <div class="flex w-full flex-col gap-1">
                        <Label class="web-text-body-muted">{{ $t('choose.color') }}</Label>
                        <div class="custom-scrollbar flex max-w-full items-center gap-3 overflow-x-auto">
                            <div
                                class="group flex h-27 min-h-27 w-40 min-w-40 cursor-pointer items-center justify-center overflow-hidden rounded border-4 border-double border-[var(--border_color_light)] hover:border-[var(--primary_light)] dark:border-[var(--border_color_dark)] dark:hover:border-[var(--primary_dark)]"
                                :class="selectedVariant?.id === variant.id ? 'border-[var(--primary_light)] dark:border-[var(--primary_dark)]' : ''"
                                v-for="variant in product.in_stock_variants"
                                :key="variant.id"
                                @click="selectedVariant = variant"
                            >
                                <img
                                    v-if="variant.ecommerce_product_image"
                                    :src="variant.ecommerce_product_image"
                                    class="transition-all duration-300 group-hover:scale-130"
                                    :class="selectedVariant?.id === variant.id ? 'scale-130' : ''"
                                    alt=""
                                />

                                <div v-else class="flex flex-col gap-2">
                                    <span>{{ $t('color') }}</span>
                                    <div class="flex items-center gap-2">
                                        <div class="web-border-color size-8 rounded-full border" :style="{ backgroundColor: variant.color }"></div>
                                        <span>{{ variant.color }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="selectedVariant" class="flex flex-col gap-1">
                        <span class="web-text-body-muted text-xs">{{ $t('quantity') }}</span>

                        <div class="flex flex-wrap items-center gap-1">
                            <Input
                                type="number"
                                v-model="quantity"
                                class="web-bg-field web-text-active web-border-muted w-50"
                                min="1"
                                :max="selectedVariant.stock_quantity"
                            />
                            <span class="flex items-center gap-1 rounded bg-yellow-600/20 px-2 py-1.5 text-sm text-yellow-600">
                                <TriangleAlert class="size-4" />
                                {{ $t('max') }} {{ selectedVariant.stock_quantity }}
                            </span>
                        </div>
                    </div>

                    <div class="web-border-color flex flex-col gap-2 border-t pt-4">
                        <div class="web-bg-content flex w-full items-center justify-between gap-4 rounded-md px-2 py-2 md:px-4">
                            <span class="web-text-body-muted text-xs md:text-sm">{{ $t('unit.price') }}</span>
                            <div class="flex items-center gap-1 md:gap-2">
                                <span class="web-text-active text-sm font-bold md:text-base">
                                    {{ product.sale_price ? product.sale_price : product.price }}$
                                </span>
                                <span v-if="product.sale_price" class="web-text-body-muted text-sm line-through"> {{ product.price }}$ </span>
                            </div>
                        </div>

                        <div
                            v-if="selectedVariant && quantity && selectedVariant.stock_quantity >= quantity"
                            class="flex w-full items-center justify-between gap-4 rounded-md bg-[var(--bg_content_hover_light)] px-2 py-2 md:px-4 dark:bg-[var(--bg_content_hover_dark)]"
                        >
                            <span class="web-text-body text-xs md:text-sm">{{ $t('total.price') }}</span>
                            <div class="flex items-center gap-1 md:gap-2">
                                <span class="web-text-active text-base font-bold md:text-lg">
                                    {{ product.sale_price ? product.sale_price * quantity : product.price * quantity }}$
                                </span>
                                <span v-if="product.sale_price" class="web-text-body-muted text-sm line-through">
                                    {{ product.price * quantity }}$
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="web-border-color flex w-full justify-end border-t pt-4">
                        <Button
                            type="button"
                            class="web-bg-primary web-text-for-primary eco-glow-button"
                            @click="addToCart"
                            :disabled="!selectedVariant || quantity > selectedVariant.stock_quantity || processing"
                        >
                            <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                            <span v-if="processing">{{ $t('adding') }}</span>

                            <ShoppingCart v-if="!processing" class="h-4 w-4" />
                            <span v-if="!processing">{{ $t('add.to.cart') }}</span>
                        </Button>
                    </div>
                </div>

                <div v-else class="flex flex-col items-center gap-2 py-10">
                    <Ban class="web-text-danger size-8" />
                    <span class="web-text-danger font-medium">{{ $t('out.of.stock') }}</span>
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
