<script setup lang="ts">
import { Input, Select } from '@/components/ui/fields';
import { Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';

interface Filter {
    search: string;
    sort_by: string;
    sort_dir: string;
    page: number,
}

const props = defineProps<{
    fetchFilter: (filter: Filter) => any;
    filter: Filter;
}>();

const filter = ref<Filter>({
    ...props.filter,
});

watch(
    filter,
    () => {
        props.fetchFilter(filter.value);
    },
    { deep: true },
);
</script>

<template>
    <div class="col-span-1 md:col-span-2 flex items-center flex-wrap justify-between gap-2">
        <div class="relative sm:min-w-60">
            <Input
                v-model="filter.search"
                class="web-bg-field web-text-active web-border-color w-full h-8 pl-9 text-xs"
                id="search"
                type="search"
                :placeholder="$t('search.order.number')"
            />

            <Search class="pointer-events-none absolute top-1/2 left-2 z-0 size-4.5 -translate-y-1/2 transform web-text-body-muted" />
        </div>
        <div class="flex items-center gap-1 lg:gap-2">
            <Select
                v-model="filter.sort_by"
                class="web-bg-field web-text-active web-border-color h-8 gap-2 text-xs"
                :placeholder="$t('sort_by')"
                dropdownClass="web-border-color web-bg-dropdown"
                :withReset="false"
            >
                <option value="date">{{ $t('date') }}</option>
                <option value="amount">{{ $t('amount') }}</option>
            </Select>
            <Select
                v-model="filter.sort_dir"
                class="web-bg-field web-text-active web-border-color h-8 gap-2 text-xs"
                :placeholder="$t('sort_dir')"
                dropdownClass="web-border-color web-bg-dropdown"
                :withReset="false"
            >
                <option value="asc">{{ $t('asc') }}</option>
                <option value="desc">{{ $t('desc') }}</option>
            </Select>
        </div>
    </div>
</template>
