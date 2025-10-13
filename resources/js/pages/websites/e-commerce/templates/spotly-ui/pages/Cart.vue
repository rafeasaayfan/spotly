<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { toast } from '@/lib/sweetAlert';
import { Link } from '@inertiajs/vue3';
import { ArrowUp, CheckCheck, DeleteIcon, LoaderCircle } from 'lucide-vue-next';
import { ref, watchEffect } from 'vue';
import Layout from './Layout.vue';
import axios from 'axios';

const props = defineProps<{
    iniItems: Record<string, any>;
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

const items = ref<Record<string, any>>(props.iniItems);

const deleteProcessing = ref(false);
const removeItem = async (itemId: number) => {
    try {
        deleteProcessing.value = true;

        const response = await axios.delete(route('website.e-commerce.cart.removeItem'), {
            data: { itemId },
        });

        if (response.data.props.items) {
            items.value = response.data.props.items;
            
            toast.fire({ icon: 'success', title: response.data.message });
        }
    } catch (error: any) {
        console.error(error.response?.data || error);
    } finally {
        deleteProcessing.value = false;
    }
}
</script>

<template>
    <Layout
        :colors="props.colors"
        :websiteNameAndLogo="props.websiteNameAndLogo"
        :websiteFooterData="props.websiteFooterData"
        :cartItemsCount="props.cartItemsCount"
    >
        <section class="pt-28 pb-22">
            <div class="mb-6 w-full text-center">
                <h2 class="web-text-active eco-section-title-underline w-fit text-3xl font-bold sm:text-4xl lg:text-5xl">
                    My <span class="eco-gradient-text">Cart</span>
                </h2>
            </div>

            <div
                :class="[
                    'bg-black-1 rounded p-3 backdrop-blur md:p-6 dark:bg-white/1',
                    true ? 'grid grid-cols-1 gap-3 lg:grid-cols-2' : 'flex h-full w-full items-center justify-center',
                ]"
            >
                <div v-for="item in items" :key="item.id" class="group web-border-color relative flex flex-col rounded-md border sm:flex-row">
                    <div
                        class="web-border-color flex h-50 w-full min-w-full items-center justify-center overflow-hidden rounded-s-md border-e bg-[var(--bg_content_light)] p-3 sm:h-full sm:w-53 sm:min-w-53 sm:p-0 lg:w-53 lg:min-w-53 dark:bg-[var(--bg_content_dark)]"
                    >
                        <img
                            v-if="item.imageUrl"
                            :src="item.imageUrl"
                            class="h-full transition-all duration-300 group-hover:scale-130 sm:w-full sm:rounded-s-md"
                        />
                        <div v-else class="flex h-full w-full flex-col items-center justify-center text-sm">
                            <p class="web-text-body-muted">color:</p>
                            <div class="flex items-center gap-1">
                                <div class="web-border-color size-5 rounded-full border" :style="{ backgroundColor: item.color }"></div>
                                <span class="text-sm">{{ item.color }}</span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex w-full flex-col gap-3 rounded-e-md bg-[var(--bg_card_light)] p-2 sm:h-full sm:justify-between dark:bg-[var(--bg_card_dark)]"
                    >
                        <div class="flex w-full items-center justify-between gap-3">
                            <Link
                                :href="`/product/${encodeURIComponent(item.product.slug)}`"
                                class="group/link web-text-active flex w-fit items-center gap-1 text-xl font-bold"
                            >
                                {{ item.product.name }}
                                <ArrowUp
                                    class="size-3.5 rotate-45 transition-transform duration-300 group-hover/link:translate-x-[2px] group-hover/link:-translate-y-[2px]"
                                />
                            </Link>

                            <div class="flex items-center gap-2">
                                <div
                                    class="web-text-for-primary web-bg-primary flex size-6.5 cursor-pointer items-center justify-center rounded-full 
                                    opacity-30 transition-all duration-300 hover:opacity-100"
                                >
                                    <CheckCheck class="size-3.5" />
                                </div>

                                <button
                                    type="button"
                                    @click="removeItem(item.id)"
                                    class="web-bg-danger web-text-for-danger cursor-pointer rounded-full size-6.5 flex items-center justify-center
                                    opacity-70 transition-all duration-300 hover:opacity-100"
                                    :class="deleteProcessing ? 'opacity-50 cursor-default' : ''"
                                >
                                    <DeleteIcon v-if="!deleteProcessing" class="size-3.5" />
                                    <LoaderCircle v-else class="size-3.5 animate-spin" />
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <p class="web-text-body-muted flex w-full items-center justify-between gap-2 text-xs">
                                <span>Quantity:</span>
                                {{ item.quantity }}
                            </p>
                            <p class="web-text-body-muted flex w-full items-center justify-between gap-2 text-xs">
                                <span>Unit Price:</span>
                                {{ item.unit_price }}$
                            </p>
                            <p class="web-text-body-muted flex w-full items-center justify-between gap-2 text-xs">
                                <span>Total Price:</span>
                                <span class="web-text-active-link text-sm">{{ item.unit_price * item.quantity }}$</span>
                            </p>
                        </div>

                        <p class="web-text-body-muted flex w-full items-center justify-between gap-2 text-xs">
                            <span>Expires At:</span>
                            {{ item.expires_at }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-3 flex w-full items-center justify-end gap-2">
                <Button type="button" class="eco-glow-button web-bg-primary web-text-for-primary">Checkout Selected</Button>
                <Button type="button" class="eco-glow-button web-bg-primary web-text-for-primary">Checkout All</Button>
            </div>
        </section>
    </Layout>
</template>
