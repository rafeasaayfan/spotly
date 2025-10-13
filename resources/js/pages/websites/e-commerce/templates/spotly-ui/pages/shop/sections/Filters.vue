<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox, Input, SelectWithSearch } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { SharedData } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { FilterIcon } from 'lucide-vue-next';
import { computed, reactive, watch } from 'vue';

const props = defineProps<{
    categories: Record<string, any>;
    brands: Record<string, any>;
    isFilterOpen: boolean;
}>();

const page = usePage<SharedData>();

const isOpen = computed(() => props.isFilterOpen);

const mappedCategories = props.categories.map((item: any) => ({
    value: page.props.lang === 'ar' ? item.ar_name : item.name,
    label: page.props.lang === 'ar' ? item.ar_name : item.name,
}));

const mappedBrands = props.brands.map((item: any) => ({
    value: item.name,
    label: item.name,
}));

const query = route().queryParams || {};

const form = reactive({
    search: query.search ?? '',
    category: query.category ? String(query.category) : null, 
    brand: query.brand ? String(query.brand) : null, 
    minPrice: query.minPrice ? Number(query.minPrice) : null,
    maxPrice: query.maxPrice ? Number(query.maxPrice) : null,
    onSale: query.onSale === 'true' || false,
    special: query.special === 'true' || false,
});

let timeout: number | undefined;

const fetchProducts = (data: any) => {
    clearTimeout(timeout);

    timeout = window.setTimeout(() => {
        router.get(
            route('website.e-commerce.shop'),
            {
                search: data.search || undefined,
                category: data.category || undefined,
                brand: data.brand || undefined,
                minPrice: data.minPrice || undefined,
                maxPrice: data.maxPrice || undefined,
                onSale: data.onSale || undefined,
                special: data.special || undefined,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    }, 400);
};

watch(
    form,
    () => {
        fetchProducts(form);
    },
    { deep: true },
);

function resetFilters() {
    form.search = '';
    form.category = null;
    form.brand = null;
    form.minPrice = null;
    form.maxPrice = null;
    form.onSale = false;
    form.special = false;

    fetchProducts(form);
}
</script>

<template>
    <aside 
        class="fixed end-0 top-0 xl:relative xl:col-span-1 xl:flex-shrink-0 xl:pe-6 backdrop-blur-xl transition-width duration-200
        border-s-2 web-border-color xl:border-none xl:opacity-100
        bg-[var(--bg_dropdown_light)] dark:bg-[var(--bg_dropdown_dark)] xl:bg-transparent xl:dark:bg-transparent"
        :class="isOpen ? 'w-72 min-h-screen z-90 opacity-100' : 'w-0 z-0 xl:w-fit opacity-0'"
    >

        <div class="web-border-color flex flex-col gap-5 rounded-md xl:border-t-4 px-4 py-6">
            <div class="border-muted flex w-full items-center gap-2 border-b pb-2">
                <FilterIcon class="web-text-active size-5" />
                <span class="web-text-active text-lg font-bold tracking-wide">{{ $t('filters') }}</span>
            </div>

            <div class="flex w-full flex-col gap-8">
                <!-- Search -->
                <div class="relative w-full">
                    <Input
                        v-model="form.search"
                        class="web-bg-field web-text-active w-full pl-9"
                        type="search"
                        :placeholder="$t('myWebsites.search_placeholder')"
                    />
                    <svg
                        class="web-text-body-muted absolute top-1/2 left-2 h-5 w-5 -translate-y-1/2 transform"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </div>

                <!-- Category -->
                <div class="flex w-full flex-col gap-1">
                    <Label class="web-text-body-muted">{{ $t('category') }}</Label>
                    <SelectWithSearch
                        v-model="form.category"
                        :options="mappedCategories"
                        :placeholder="$t('category')"
                        class="web-bg-field web-text-active w-full"
                    />
                </div>

                <!-- Brand -->
                <div class="flex w-full flex-col gap-1">
                    <Label class="web-text-body-muted">{{ $t('brand') }}</Label>
                    <SelectWithSearch
                        v-model="form.brand"
                        :options="mappedBrands"
                        :placeholder="$t('brand')"
                        class="web-bg-field web-text-active w-full"
                    />
                </div>

                <!-- Price Range -->
                <div class="flex w-full flex-col gap-1">
                    <Label class="web-text-body-muted">{{ $t('price.range') }}</Label>
                    <div class="flex gap-2">
                        <Input v-model="form.minPrice" type="number" class="web-bg-field web-text-active" min="0" placeholder="Min" />
                        <Input v-model="form.maxPrice" type="number" class="web-bg-field web-text-active" min="0" placeholder="Max" />
                    </div>
                </div>

                <!-- Quick Filters -->
                <div class="flex w-full flex-col gap-1">
                    <Label class="web-text-body-muted">{{ $t('quick.filters') }}</Label>
                    <div class="flex flex-col gap-2">
                        <label class="flex cursor-pointer items-center gap-2 text-sm select-none">
                            <Checkbox v-model="form.special" class="bg-[var(--bg_field_light)] dark:bg-[var(--bg_field_dark)]" />
                            {{ $t('special') }}
                        </label>
                        <label class="flex cursor-pointer items-center gap-2 text-sm select-none">
                            <Checkbox v-model="form.onSale" class="bg-[var(--bg_field_light)] dark:bg-[var(--bg_field_dark)]" />
                            {{ $t('on.sale') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="web-border-color border-t pt-2">
                <Button
                    @click="resetFilters"
                    variant="destructive"
                    size="sm"
                    class="web-text-for-danger rounded-none rounded bg-[var(--danger_light)]/30 hover:bg-[var(--danger_light)] dark:bg-[var(--danger_dark)]/30 dark:hover:bg-[var(--danger_dark)]"
                >
                    {{ $t('reset') }}
                </Button>
            </div>
        </div>
    </aside>
</template>
