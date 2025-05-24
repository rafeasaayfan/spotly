<script setup lang="ts">
import { ActionDeleteBtn, ActionEditBtn, ActionViewBtn, Chevron, Table, Tbody, Td, Th, Thead, Tr } from '@/components/ui/table';
import Checkbox from '../ui/fields/Checkbox.vue';
import { Image } from '../ui/image';

import { Inbox } from 'lucide-vue-next';

import { type Column } from '@/composables/dataTable/useDataTable';
import { formatters, type TableConditions } from '@/lib/dataTable';

interface TableProps {
    columns: Column[];
    data: Record<string, any>[];
    toggleRowSelection: (id: number, value: boolean) => void;
    toggleSelectAll: (value: boolean) => void;
    allSelected: boolean;
    selectedIds: number[];
    handleAction: (action: 'view' | 'edit' | 'delete', idOrIds: number | number[]) => Promise<void>;
    applyFilters: (overrides: Record<string, any>) => void;
    filters: Record<string, any>;
    tableConditions: TableConditions;
}

const props = defineProps<TableProps>();
</script>

<template>
    <div class="relative overflow-x-auto rounded-md border border-muted shadow xl:overflow-visible">
        <Table>
            <Thead>
                <Tr class="font-semibold">
                    <Th class="w-3 text-body-muted" v-if="props.tableConditions.enableRowsDelete">
                        <Checkbox :model-value="allSelected" @update:modelValue="val => typeof val === 'boolean' && toggleSelectAll(val)" />
                    </Th>
                    <Th
                        v-for="column in props.columns"
                        :key="column.key"
                        @click="
                            applyFilters({
                                sort_by: column.key,
                                sort_dir: filters.sort_dir === 'asc' ? 'desc' : 'asc',
                            })
                        "
                        class="cursor-pointer select-none text-body-muted"
                    >
                        <div class="flex items-center gap-2">
                            <span>{{ column.label }}</span>
                            <Chevron :sort-key="column.key" :current-sort="{ key: filters.sort_by, direction: filters.sort_dir }" />
                        </div>
                    </Th>
                    <Th
                        class="w-10 text-body-muted"
                        v-if="props.tableConditions.enableEdit || props.tableConditions.enableView || props.tableConditions.enableDelete"
                    >
                    </Th>
                </Tr>
            </Thead>

            <Tbody v-if="props.data.length > 0">
                <!-- Row: every data row && index: is the number of this row -->
                <Tr v-for="(row, index) in props.data" :key="index" class="w-3 text-sm text-body font-medium bg-content-3">
                    <Td class="max-w-5" v-if="props.tableConditions.enableRowsDelete">
                        <Checkbox :model-value="selectedIds.includes(row.id)" @update:modelValue="val => typeof val === 'boolean' && toggleRowSelection(row.id, val)" />
                    </Td>

                    <Td v-for="column in props.columns" :key="column.key">
                        <template v-if="column.key === 'image'">
                            <Image v-if="row[column.key]" :src="row[column.key]" alt="Image" class="h-12 w-12 !rounded-full object-cover" />
                        </template>
                        <template v-else-if="column.key === 'email_verified_at'">
                            <span v-html="formatters.EmailVerified(row[column.key])"></span>
                        </template>
                        <template v-else-if="column.key === 'created_at'">
                            {{ formatters.date(row[column.key], 'short') }}
                        </template>
                        <template v-else>
                            {{ row[column.key] }}
                        </template>
                    </Td>

                    <Td v-if="props.tableConditions.enableEdit || props.tableConditions.enableView || props.tableConditions.enableDelete">
                        <div class="flex items-center justify-end gap-2">
                            <ActionEditBtn v-if="props.tableConditions.enableEdit" @click="handleAction('edit', row.id)" />
                            <ActionViewBtn v-if="props.tableConditions.enableView" @click="handleAction('view', row.id)" />
                            <ActionDeleteBtn v-if="props.tableConditions.enableDelete" @click="handleAction('delete', row.id)" />
                        </div>
                    </Td>
                </Tr>
            </Tbody>

            <Tbody v-else>
                <Tr class="w-3 text-sm text-body-muted">
                    <Td colspan="12">
                        <div class="flex w-full flex-col items-center justify-center gap-2 py-3">
                            <Inbox class="size-10" />
                            <span class="text-muted text-sm font-semibold">No data founded</span>
                        </div>
                    </Td>
                </Tr>
            </Tbody>
        </Table>
    </div>
</template>
