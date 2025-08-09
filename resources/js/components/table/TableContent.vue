<script setup lang="ts">
import { Chevron, Table, Th, Thead, Tr } from '@/components/ui/table';
import { Checkbox } from '../ui/fields';
import TableBody from './tableBody.vue';

import { type Column } from '@/composables/dataTable/useDataTable';
import { type TableConditions } from '@/lib/dataTable';

interface TableProps {
    columns: Column[];
    data: Record<string, any>[];
    toggleRowSelection: (id: number, value: boolean) => void;
    toggleSelectAll: (value: boolean) => void;
    allSelected: boolean;
    selectedIds: number[];
    handleAction: (action: 'delete', idOrIds: number | number[]) => Promise<void>;
    applyFilters: (overrides: Record<string, any>) => void;
    filters: Record<string, any>;
    tableConditions: TableConditions;
    path: string;
    routeName: string;
}

const props = defineProps<TableProps>();
</script>

<template>
    <div class="custom-scrollbar relative overflow-x-auto xl:overflow-visible">
        <Table>
            <Thead>
                <Tr class="font-semibold">
                    <Th class="text-body-muted w-3 border-none" v-if="props.tableConditions.enableRowsDelete">
                        <Checkbox 
                            :modelValue="allSelected" 
                            @update:modelValue="(val) => typeof val === 'boolean' && toggleSelectAll(val)"
                            class="border-blue-600/20 dark:border-blue-600/10"
                        />
                    </Th>
                    <Th
                        v-for="column in props.columns"
                        :key="column.key"
                        @click="
                            applyFilters({
                                sort_by: column.key,
                                sort_dir: props.filters.sort_dir === 'asc' ? 'desc' : 'asc',
                            })
                        "
                        class="text-body-muted cursor-pointer text-xs select-none"
                    >
                        <div class="flex items-center gap-2">
                            <span>{{ column.label }}</span>
                            <Chevron :sortKey="column.key" :currentSort="{ key: props.filters.sort_by, direction: props.filters.sort_dir }" />
                        </div>
                    </Th>
                    <Th
                        class="text-body-muted w-10"
                        v-if="
                            props.tableConditions.enableEdit ||
                            props.tableConditions.enableView ||
                            props.tableConditions.enableDelete ||
                            props.tableConditions.enableAssignPermissions ||
                            props.tableConditions.enableAssignRoles ||
                            props.tableConditions.enableUserAssignments
                        "
                    >
                    </Th>
                </Tr>
            </Thead>

            <TableBody
                :columns="props.columns"
                :data="props.data"
                :selectedIds="props.selectedIds"
                :tableConditions="props.tableConditions"
                :toggleRowSelection="props.toggleRowSelection"
                :path="props.path"
                :routeName="props.routeName"
                :handleAction="props.handleAction"
            />
        </Table>
    </div>
</template>

<style scoped>
.custom-scrollbar {
    scrollbar-width: thin;
}
</style>
