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

const props = defineProps<{
    tableData: DataTableProps;
    columns: Column[];
    routeName: string;
    tableConditions: TableConditions;
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

const meta = createPaginationMeta(props.tableData);

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
    <div class="flex flex-col gap-4 p-4">
        <!-- Table header with search and column visibility -->
        <TableHeader
            :columns="props.columns"
            :applyFilters="applyFilters"
            :columnsVisibility="columnsVisibility"
            :updateColumnVisibility="updateColumnVisibility"
            :routeName="props.routeName"
            :selectedIds="selectedIds"
            :search="filters.search"
            :handleAction="handleAction"
            :tableConditions="tableConditions"
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
        />
        
        <!-- Table footer with pagination -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <TableLimit
                    :applyFilters="applyFilters"
                    :selectedLimit="filters.limit ?? 10"
                    :links="props.tableData.links"
                    :tableConditions="tableConditions"
                />
                <Meta :meta="meta" />
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
