<script setup lang="ts">
import Pagination from '@/components/pagination/Pagination.vue';
import { DataTableProps } from '@/composables/dataTable/useDataTable';
import { confirmDialog, toast } from '@/lib/sweetAlert';
import { Head, router } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref, watchEffect } from 'vue';
import Layout from '../Layout.vue';
import Filter from './sections/Filter.vue';
import NoOrdersFound from './sections/NoOrdersFound.vue';
import OrderCard from './sections/OrderCard.vue';
import OrderStatusActions from './sections/OrderStatusActions.vue';

const props = defineProps<{
    orders: DataTableProps;
    orderStats: Record<string, any>;
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

const cartItemsCount = ref<number>(props.iniCartItemsCount);

const query = route().queryParams || {};
interface Filter {
    search: string;
    sort_by: string;
    sort_dir: string;
    page: number;
}
const filter = ref<Filter>({
    search: query?.search ? String(query.search) : '',
    sort_by: query?.sort_by ? String(query.sort_by) : 'date',
    sort_dir: query?.sort_dir ? String(query.sort_dir) : 'desc',
    page: query?.page ? Number(query.page) : 1,
});
const status = ref(String(query.status ?? 'pending'));

let timeout: number | undefined;
const processingFilter = ref(false);

const fetchFilter = (newFilter: Filter) => {
    clearTimeout(timeout);
    processingFilter.value = true;

    timeout = window.setTimeout(() => {
        router.get(
            route('website.e-commerce.orders'),
            {
                status: status.value,
                page: newFilter.page,
                search: newFilter.search,
                sort_by: newFilter.sort_by,
                sort_dir: newFilter.sort_dir,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                onFinish: () => {
                    processingFilter.value = false;
                    filter.value.search = newFilter.search;
                    filter.value.sort_by = newFilter.sort_by;
                    filter.value.sort_dir = newFilter.sort_dir;
                },
            },
        );
    }, 300);
};
const goToPage = (page: number) => {
    filter.value.page = page;

    fetchFilter(filter.value);
};

const processing = ref(false);
const fetchOrders = (newStatus: string) => {
    clearTimeout(timeout);

    timeout = window.setTimeout(() => {
        processing.value = true;
        router.get(
            route('website.e-commerce.orders'),
            {
                status: newStatus,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                onFinish: () => {
                    processing.value = false;
                    status.value = newStatus;
                    filter.value.page = 1;
                    filter.value.search = '';
                    filter.value.sort_by = 'date';
                    filter.value.sort_dir = 'desc';
                },
            },
        );
    }, 300);
};

const filterCondition = () => {
    if (processing.value) {
        return false;
    } else {
        if (filter.value.search === '') {
            if (props.orders.data && props.orders.data.length > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
};

const noOrdersFor = ref('noStatusData');
const noOrdersCondition = () => {
    if (processing.value) {
        return false;
    } else {
        if (filter.value.search === '') {
            if (props.orders.data && props.orders.data.length > 0) {
                return false;
            } else {
                noOrdersFor.value = 'noStatusData';
                return true;
            }
        } else {
            if (props.orders.data && props.orders.data.length > 0) {
                return false;
            } else {
                noOrdersFor.value = 'noFilterData';
                return true;
            }
        }
    }
};

const cancelOrderProcessing = ref(false);
const handleDialogCancel = async (orderId: number) => {
    const result = await confirmDialog({ text: 'Cancel Order?' });
    if (!result.isConfirmed) return;
    cancelOrderProcessing.value = true;

    try {
        await router.patch(
            route('website.e-commerce.orders.cancelOrder', orderId),
            {},
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                onFinish: () => {
                    cancelOrderProcessing.value = false;
                },
            },
        );
    } catch (error: any) {
        toast.fire({
            icon: 'error',
            title: error.response?.data?.message || 'Something went wrong',
        });
    }
};
</script>

<template>
    <Head :title="$t('my.title') + ' ' + $t('my.orders')" />

    <Layout
        :colors="props.colors"
        :websiteNameAndLogo="props.websiteNameAndLogo"
        :websiteFooterData="props.websiteFooterData"
        :cartItemsCount="cartItemsCount"
    >
        <section class="pt-28 pb-22">
            <div class="mb-10 w-full text-center">
                <h2 class="web-text-active eco-section-title-underline w-fit text-3xl font-bold sm:text-4xl lg:text-5xl">
                    {{ $t('my.title') }} <span class="eco-gradient-text">{{ $t('my.orders') }}</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-5">
                <OrderStatusActions
                    :orderStats="props.orderStats"
                    :fetchOrders="fetchOrders"
                    :status="status"
                    :processing="processing || processingFilter || cancelOrderProcessing"
                />

                <div class="col-span-1 lg:col-span-4">
                    <div class="flex h-full flex-col gap-4">
                        <Filter v-if="filterCondition()" :filter="filter" :fetchFilter="fetchFilter" />

                        <div
                            class="grid h-full grid-cols-1 gap-4 md:grid-cols-2"
                            v-if="!processing && props.orders.data && props.orders.data.length > 0"
                        >
                            <OrderCard
                                :orders="props.orders"
                                :processings="processingFilter || cancelOrderProcessing"
                                :handleDialogCancel="handleDialogCancel"
                            />
                        </div>

                        <NoOrdersFound v-if="noOrdersCondition()" :status="status" :noOrdersFor="noOrdersFor" />

                        <div v-if="processing" class="col-span-1 flex min-h-full w-full flex-col items-center justify-center gap-3 lg:col-span-2">
                            <LoaderCircle class="web-text-body-muted size-8 animate-spin" />
                            <span class="web-text-body-muted">{{ $t('please.wait') }}</span>
                        </div>

                        <div class="web-border-color flex w-full justify-end gap-2 border-t-3 border-dashed pt-2">
                            <Pagination
                                v-if="!processing"
                                :links="props.orders.links"
                                :data="props.orders"
                                btnClass="web-bg-content web-text-body-muted"
                                activeClass="web-bg-content-active web-text-active"
                                @updatePage="goToPage"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </Layout>
</template>
