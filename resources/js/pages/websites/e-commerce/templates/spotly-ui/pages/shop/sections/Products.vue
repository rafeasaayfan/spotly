<script setup lang="ts">
import ProductCard from '../../../components/cards/ProductCard.vue';
import { DataTableProps } from '@/composables/dataTable/useDataTable';
import Pagination from '@/components/pagination/Pagination.vue';
import { Package } from 'lucide-vue-next';

const props = defineProps<{
    products: DataTableProps;
}>();
</script>

<template>
    <div class="col-span-4 xl:col-span-3 flex flex-col gap-5">
        <div 
            v-if="!props.products.data || props.products.data.length === 0" 
            class="w-full h-full flex items-center justify-center flex-col gap-2 text-lg"
        > 
            <Package class="size-10 web-text-body-muted" />
            {{ $t('no.products.found') }}
        </div>

        <div v-else class="relative grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <ProductCard v-for="product in props.products.data" :key="product.id" :product="product" />
        </div>

        <div class="w-full flex justify-end gap-2 web-border-color pt-2 border-t-3 border-dashed">
            <Pagination
                :links="props.products.links"
                :data="props.products"
                btnClass="web-bg-content web-text-body-muted"
                activeClass="web-bg-content-active web-text-active"
            />
        </div>
    </div>
</template>
