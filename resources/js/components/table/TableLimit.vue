<script setup lang="ts">
import { formatPaginationLinks, type PaginationLink } from '@/lib/pagination';
import { computed, ref, watch } from 'vue';
import { Select } from '../ui/fields';

import { type TableConditions } from '@/lib/dataTable';

const props = defineProps<{
    applyFilters: (overrides: Record<string, any>) => void;
    selectedLimit: number;
    links: PaginationLink[];
    tableConditions: TableConditions;
}>();

const formattedLinks = computed(() => formatPaginationLinks(props.links));

const limit = ref(props.selectedLimit);

watch(limit, (newLimit) => {
    props.applyFilters({ limit: newLimit });
});
</script>

<template>
    <Select v-if="formattedLinks.length > 1 && props.tableConditions.enableLimit" v-model="limit" class="h-8 w-10 p-0 px-2" :option="'Items per page'">
        <option v-for="limitOption in [5, 10, 20, 50, 100]" :key="limitOption" :value="limitOption">
            {{ limitOption }}
        </option>
    </Select>
</template>
