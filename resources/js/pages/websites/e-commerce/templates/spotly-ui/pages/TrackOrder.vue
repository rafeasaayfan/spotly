<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/fields';
import { formatters } from '@/lib/dataTable';
import { SharedData } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ArrowUp, Info, Search } from 'lucide-vue-next';
import { ref } from 'vue';
import Layout from './Layout.vue';

const props = defineProps<{
    order: Record<string, any> | null;
    colors: Record<string, string>;
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
    cartItemsCount: number;
}>();

console.log(props.order);

const page = usePage<SharedData>();

const query = route().queryParams;
const searchOrderInput = ref<string | null>(query?.order_number as string | null);

const searchOrder = () => {
    router.get(
        route('website.e-commerce.trackOrder', {
            order_number: searchOrderInput.value?.trim(),
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};
</script>

<template>
    <Head :title="$t('nav.trackOrder')" />

    <Layout
        :colors="props.colors"
        :websiteNameAndLogo="props.websiteNameAndLogo"
        :websiteFooterData="props.websiteFooterData"
        :cartItemsCount="props.cartItemsCount"
    >
        <section class="pt-28 pb-22">
            <div class="mb-10 w-full text-center">
                <h2 class="web-text-active eco-section-title-underline w-fit text-3xl font-bold sm:text-4xl lg:text-5xl">
                    {{ $t('trackOrder.trackYour') }} <span class="eco-gradient-text">{{ $t('trackOrder.order') }}</span>
                </h2>
            </div>

            <div class="flex flex-col gap-3">
                <div class="flex w-full flex-wrap items-center justify-between gap-3">
                    <div class="font-medium">
                        <p v-if="props.order" class="web-text-active-link">#{{ props.order.order_number }}</p>
                        <p v-else class="web-text-active-link flex items-center gap-1.5 rounded-md bg-blue-500/10 p-2 text-sm">
                            <Info class="size-3.5" />
                            <span>{{ $t('trackOrder.searchForOrder') }}</span>
                        </p>
                    </div>

                    <div class="relative h-9 sm:min-w-70">
                        <Input
                            v-model="searchOrderInput"
                            class="web-bg-field web-text-active web-border-color h-9 w-full ps-12 text-xs"
                            id="search"
                            type="search"
                            :placeholder="$t('search.order.number')"
                        />

                        <div class="absolute start-0 top-1/2 -translate-y-1/2 transform">
                            <Button @click="searchOrder" size="sm" class="web-bg-primary web-border-color h-8.5 rounded-none rounded-s-md sm:text-xs">
                                <Search class="size-4.5 text-white" />
                            </Button>
                        </div>
                    </div>
                </div>

                <div class="web-border-color grid grid-cols-1 gap-5 sm:gap-3 border-t-4 border-double py-4 sm:grid-cols-5">
                    <div class="web-border-color col-span-1 flex flex-col gap-3 sm:col-span-2 sm:border-e sm:pe-3 md:col-span-1">
                        <div class="bg-[var(--bg_content_light)] p-2 text-sm dark:bg-[var(--bg_content_dark)]">
                            {{ $t('trackOrder.orderTracking') }}
                        </div>

                        <div v-if="props.order" class="relative flex flex-col gap-8">
                            <div v-for="(track, index) in props.order.track_order" :key="track.id" class="flex items-center gap-5">
                                <span class="bg-[var(--bg_content_hover_light)] dark:bg-[var(--bg_content_hover_dark)] backdrop-blur flex size-9 items-center justify-center rounded-full text-active z-3">
                                    {{ Number(index) + 1 }}
                                </span>
                                <div class="flex flex-col gap-1">
                                    <span v-html="formatters.status(track.status)"></span>
                                    <span class="web-text-body-muted text-xs">{{ formatters.date(track.created_at, 'long') }}</span>
                                </div>
                            </div>

                            <div class="absolute top-0 start-4 h-full py-2 z-0">
                                <div class="web-border-color h-full w-full border-s-3 border-dashed z-0">
                                </div>
                            </div>
                        </div>

                        <div v-else class="flex w-full items-center justify-center gap-2 rounded-md bg-black/1 px-4 py-25 dark:bg-white/1">
                            <p class="web-text-body-muted text-sm font-medium">{{ $t('trackOrder.noOrderTrackingFound') }}</p>
                        </div>
                    </div>

                    <div class="col-span-1 flex flex-col gap-3 sm:col-span-3 md:col-span-4">
                        <div class="flex flex-col gap-2">
                            <div class="bg-[var(--bg_content_light)] p-2 text-sm dark:bg-[var(--bg_content_dark)]">
                                {{ $t('trackOrder.orderDetails') }}
                            </div>

                            <div v-if="props.order" class="flex flex-col gap-2">
                                <div class="web-border-color flex items-center justify-between gap-2 border-b pb-1">
                                    <span class="web-text-body-muted text-[10px] font-medium uppercase">{{ $t('trackOrder.orderFor') }}</span>
                                    <span class="web-text-body text-sm font-medium">
                                        {{ props.order.session_id ? $t('trackOrder.sessionOrder') : props.order.user.name }}
                                    </span>
                                </div>

                                <div class="web-border-color flex items-center justify-between gap-2 border-b pb-1">
                                    <span class="web-text-body-muted text-[10px] font-medium uppercase">{{ $t('trackOrder.totalAmount') }}</span>
                                    <span class="web-text-active-link text-sm font-medium"> {{ props.order.total_amount }}$ </span>
                                </div>

                                <div class="web-border-color flex items-center justify-between gap-2 border-b pb-1">
                                    <span class="web-text-body-muted text-[10px] font-medium uppercase">{{ $t('trackOrder.phoneNumber') }}</span>
                                    <a :href="`tel:${props.order.phone_number}`" class="web-text-body text-sm font-medium">
                                        {{ props.order.phone_number }}
                                    </a>
                                </div>

                                <div class="web-border-color flex items-center justify-between gap-2 border-b pb-1">
                                    <span class="web-text-body-muted text-[10px] font-medium uppercase">{{ $t('trackOrder.city') }}</span>
                                    <span class="web-text-body text-sm font-medium">
                                        {{ props.order.city }}
                                    </span>
                                </div>

                                <div class="web-border-color flex flex-col border-b pb-1">
                                    <span class="web-text-body-muted text-[10px] font-medium uppercase">{{ $t('trackOrder.deliveryAddress') }}</span>
                                    <span class="web-text-body text-sm font-medium">
                                        {{ props.order.delivery_address }}
                                    </span>
                                </div>

                                <div class="web-border-color flex flex-col border-b pb-1" v-if="props.order.note">
                                    <span class="web-text-body-muted text-[10px] font-medium uppercase">{{ $t('trackOrder.note') }}</span>
                                    <span class="web-text-body text-sm font-medium">
                                        {{ props.order.note }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-2">
                                    <span class="web-text-body-muted text-[10px] font-medium uppercase">{{ $t('trackOrder.currentStatus') }}</span>
                                    <span v-html="formatters.status(props.order.status)"></span>
                                </div>
                            </div>

                            <div v-else class="flex w-full items-center justify-center gap-2 rounded-md bg-black/1 px-4 py-25 dark:bg-white/1">
                                <p class="web-text-body-muted text-sm font-medium">{{ $t('trackOrder.noOrderTrackingFound') }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <div class="bg-[var(--bg_content_light)] p-2 text-sm dark:bg-[var(--bg_content_dark)]">
                                {{ $t('order.items') }}
                            </div>

                            <div v-if="props.order" class="grid grid-cols-1 gap-2 lg:grid-cols-2">
                                <div
                                    v-for="item in props.order.items"
                                    :key="item.id"
                                    class="group web-border-color web-bg-card flex flex-col rounded-md border sm:flex-row"
                                >
                                    <div class="flex w-full items-center justify-center p-2 sm:w-auto">
                                        <div
                                            class="relative max-h-35 w-35 overflow-hidden rounded-md bg-[var(--bg_content_light)] dark:bg-[var(--bg_content_dark)]"
                                        >
                                            <img
                                                v-if="item.image_urls && item.image_urls.length > 0"
                                                :src="item.image_urls[0]"
                                                class="h-full w-full rounded-md transition-all duration-300 group-hover:scale-130"
                                            />
                                        </div>
                                    </div>

                                    <div class="flex w-full flex-col gap-1 overflow-hidden px-2 py-1">
                                        <Link
                                            :href="`/product/${encodeURIComponent(item.product.slug)}`"
                                            class="group/link web-text-active mb-1 flex w-fit items-center gap-1 text-xl font-bold"
                                        >
                                            {{ item.product.name }}
                                            <ArrowUp
                                                class="size-3.5 rotate-45 transition-transform duration-300 group-hover/link:translate-x-[2px] group-hover/link:-translate-y-[2px]"
                                            />
                                        </Link>

                                        <div
                                            v-if="item.attributes && item.attributes.length > 0"
                                            class="custom-scrollbar flex max-w-full items-center gap-1 overflow-x-auto"
                                        >
                                            <div
                                                v-for="attribute in item.attributes"
                                                :key="attribute.id"
                                                class="web-border-color flex flex-shrink-0 items-center gap-1 rounded-md border px-1 py-0.5 whitespace-nowrap"
                                            >
                                                <span v-if="attribute.color_code" class="web-text-body text-[11px] font-medium">
                                                    {{ page.props.lang === 'ar' ? attribute.color_name_ar : attribute.color_name }}
                                                </span>
                                                <span v-else class="web-text-body text-[11px] font-medium">
                                                    {{
                                                        page.props.lang === 'ar' ? attribute.attribute_value_name_ar : attribute.attribute_value_name
                                                    }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="web-text-body-muted flex w-full items-center justify-between gap-2 text-xs">
                                            <span>{{ $t('quantity') }}</span>

                                            <p class="web-text-active text-base font-medium">
                                                {{ item.quantity }}
                                            </p>
                                        </div>

                                        <div class="web-text-body-muted flex w-full items-center justify-between gap-2 text-xs">
                                            <span>{{ $t('total.price') }}</span>
                                            <span class="web-text-active-link text-base font-medium">{{ item.unit_price * item.quantity }}$</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="flex w-full items-center justify-center gap-2 rounded-md bg-black/1 px-4 py-25 dark:bg-white/1">
                                <p class="web-text-body-muted text-sm font-medium">{{ $t('trackOrder.noOrderTrackingFound') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </Layout>
</template>
