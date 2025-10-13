<script setup lang="ts">
import { toast } from '@/lib/sweetAlert';
import { ref, watchEffect } from 'vue';
import Layout from '../Layout.vue';
import Filters from './sections/Filters.vue';
import Products from './sections/Products.vue';
import { DataTableProps } from '@/composables/dataTable/useDataTable';
import { Button } from '@/components/ui/button';
import { FilterIcon } from 'lucide-vue-next';

const props = defineProps<{
    colors: Record<string, string>;
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
    categories: Record<string, any>;
    brands: Record<string, any>;
    products: DataTableProps;
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

const isFilterOpen = ref(false);
function toggleFilter() {
    isFilterOpen.value = !isFilterOpen.value;
}
</script>

<template>
    <Layout :colors="props.colors" :websiteNameAndLogo="props.websiteNameAndLogo" :websiteFooterData="props.websiteFooterData"
        :cartItemsCount="props.cartItemsCount"
    >
        <section class="flex flex-col gap-8 pt-30 pb-22">
            <div class="w-full text-center">
                <h2 class="web-text-active eco-section-title-underline w-fit text-3xl font-bold sm:text-4xl lg:text-5xl">
                    {{ $t('our') }} <span class="eco-gradient-text">{{ $t('our.store') }}</span>
                </h2>
            </div>

            <div class="grid grid-cols-4">
                <Button type="button" @click="toggleFilter" class="col-span-4 eco-glow-btn w-fit xl:hidden web-text-for-primary rounded-none rounded mb-5">
                    <FilterIcon class="size-4" />
                    <span>{{ $t('filters') }}</span>
                </Button>

                <Filters :isFilterOpen="isFilterOpen" :categories="props.categories" :brands="props.brands" />

                <Products :products="props.products" />
            </div>
        </section>
    </Layout>
</template>
