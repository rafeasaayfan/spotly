<script setup lang="ts">
import { computed, ref } from 'vue';

import Pagination from '@/components/pagination/Pagination.vue';
import TableContent from './TableContent.vue';
import TableHeader from './TableHeader.vue';
import TableLimit from './TableLimit.vue';

import Meta from '@/components/pagination/Meta.vue';

import { useDataTable, type Column, type DataTableProps } from '@/composables/dataTable/useDataTable';
import { useTableActions } from '@/composables/dataTable/useTableActions';
import { type TableConditions } from '@/lib/dataTable';
import { type Filter } from '@/types';

const props = defineProps<{
    tableData: DataTableProps;
    filter?: Filter[];
    columns: Column[];
    routeName: string;
    tableConditions: TableConditions;
    path: string;
    dashboardFor?: string,
}>();

// Manage selected rows
const selectedIds = ref<number[]>([]);
const allSelected = computed(() => {
    return props.tableData.data.length > 0 && selectedIds.value.length === props.tableData.data.length;
});

const { filters, applyFilters, createPaginationMeta, columnsVisibility, updateColumnVisibility } = useDataTable({
    routeName: props.routeName,
    columns: props.columns,
});

const filteredCols = computed(() => {
    return props.columns.filter((column) => columnsVisibility.value[column.key] !== false);
});

const meta = computed(() => createPaginationMeta(props.tableData));

function toggleRowSelection(id: number, checked: boolean) {
    if (checked) {
        if (!selectedIds.value.includes(id)) {
            selectedIds.value.push(id);
        }
    } else {
        selectedIds.value = selectedIds.value.filter((i: number) => i !== id);
    }
}

function toggleSelectAll(checked: boolean) {
    selectedIds.value = checked ? props.tableData.data.map((row: Record<string, any>) => row.id) : [];
}

const { handleAction } = useTableActions(selectedIds, props.routeName);
</script>

<template>
    <div class="flex flex-col my-4 mx-2 md:mx-4 border border-muted rounded-md">
        <!-- Table header with search and column visibility -->
        <TableHeader
            :columns="props.columns"
            :filter="props.filter"
            :applyFilters="applyFilters"
            :columnsVisibility="columnsVisibility"
            :updateColumnVisibility="updateColumnVisibility"
            :routeName="props.routeName"
            :selectedIds="selectedIds"
            :search="filters.search"
            :handleAction="handleAction"
            :tableConditions="tableConditions"
            :path="props.path"
        />

        <!-- Table content -->
        <TableContent
            :columns="filteredCols"
            :data="props.tableData.data"
            :toggleRowSelection="toggleRowSelection"
            :allSelected="allSelected"
            :toggleSelectAll="toggleSelectAll"
            :selectedIds="selectedIds"
            :handleAction="handleAction"
            :applyFilters="applyFilters"
            :filters="filters"
            :tableConditions="tableConditions"
            :path="props.path"
            :routeName="props.routeName"
            :dashboardFor="props.dashboardFor"
        />

        <!-- Table footer with pagination -->
        <div class="flex flex-col-reverse sm:flex-row flex-wrap items-center justify-between gap-4 p-3">
            <div class="flex items-center justify-center md:justify-start gap-3 flex-wrap">
                <TableLimit
                    :applyFilters="applyFilters"
                    :selectedLimit="Number(filters.limit ?? 10)"
                    :links="props.tableData.links"
                    :tableConditions="tableConditions"
                />
                <Meta :meta="meta.value" />
            </div>

            <Pagination
                :links="props.tableData.links"
                :data="props.tableData"
                :applyFilters="applyFilters"
                :tableConditions="tableConditions"
            />
        </div>
    </div>
</template>
