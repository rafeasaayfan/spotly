<script setup lang="ts">
import { ActionAssignmentsBtn, ActionDeleteBtn, ActionEditBtn, ActionViewBtn, Chevron, Table, Tbody, Td, Th, Thead, Tr } from '@/components/ui/table';
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
    handleAction: (
        action: 'view' | 'edit' | 'delete' | 'assignRoles' | 'assignPermissions' | 'usersAssignments',
        idOrIds: number | number[],
    ) => Promise<void>;
    applyFilters: (overrides: Record<string, any>) => void;
    filters: Record<string, any>;
    tableConditions: TableConditions;
}

const props = defineProps<TableProps>();
</script>

<template>
    <div class="border-muted relative overflow-x-auto rounded-md border shadow xl:overflow-visible">
        <Table>
            <Thead>
                <Tr class="font-semibold">
                    <Th class="text-body-muted w-3" v-if="props.tableConditions.enableRowsDelete">
                        <Checkbox :model-value="allSelected" @update:modelValue="(val) => typeof val === 'boolean' && toggleSelectAll(val)" />
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
                        class="text-body-muted cursor-pointer select-none"
                    >
                        <div class="flex items-center gap-2">
                            <span>{{ column.label }}</span>
                            <Chevron :sort-key="column.key" :current-sort="{ key: filters.sort_by, direction: filters.sort_dir }" />
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
                            props.tableConditions.enableUsersAssignments
                        "
                    >
                    </Th>
                </Tr>
            </Thead>

            <Tbody v-if="props.data.length > 0">
                <!-- Row: every data row && index: is the number of this row -->
                <Tr v-for="(row, index) in props.data" :key="index" class="text-body bg-content-3 w-3 text-sm font-medium">
                    <Td class="max-w-5" v-if="props.tableConditions.enableRowsDelete">
                        <Checkbox
                            :model-value="selectedIds.includes(row.id)"
                            @update:modelValue="(val) => typeof val === 'boolean' && toggleRowSelection(row.id, val)"
                        />
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
                        <template v-else-if="formatters.shouldSplit(column.key, row[column.key])">
                            <div class="flex flex-wrap gap-1">
                                <span
                                    v-for="(item, index) in formatters.splitAndStyle(row[column.key])"
                                    :key="index"
                                    class="rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300"
                                >
                                    {{ item }}
                                </span>
                            </div>
                        </template>
                        <template v-else>
                            {{ row[column.key] }}
                        </template>
                    </Td>

                    <Td
                        class="max-w-fit"
                        v-if="
                            props.tableConditions.enableEdit ||
                            props.tableConditions.enableView ||
                            props.tableConditions.enableDelete ||
                            props.tableConditions.enableAssignPermissions ||
                            props.tableConditions.enableAssignRoles ||
                            props.tableConditions.enableUsersAssignments
                        "
                    >
                        <div class="flex items-center justify-end gap-2">
                            <ActionEditBtn v-if="props.tableConditions.enableEdit" @click="handleAction('edit', row.id)" />
                            <ActionViewBtn v-if="props.tableConditions.enableView" @click="handleAction('view', row.id)" />
                            <ActionAssignmentsBtn v-if="props.tableConditions.enableAssignRoles" @click="handleAction('assignRoles', row.id)" />
                            <ActionAssignmentsBtn
                                v-if="props.tableConditions.enableAssignPermissions"
                                @click="handleAction('assignPermissions', row.id)"
                            />
                            <ActionAssignmentsBtn
                                v-if="props.tableConditions.enableUsersAssignments"
                                @click="handleAction('usersAssignments', row.id)"
                            />
                            <ActionDeleteBtn v-if="props.tableConditions.enableDelete" @click="handleAction('delete', row.id)" />
                        </div>
                    </Td>
                </Tr>
            </Tbody>

            <Tbody v-else>
                <Tr class="text-body-muted w-3 text-sm">
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
