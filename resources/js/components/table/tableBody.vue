<script setup lang="ts">
import { Dialog, DialogDescription, DialogHeader, DialogScrollContent, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { ActionAssignmentsBtn, ActionDeleteBtn, ActionEditBtn, ActionViewBtn, Tbody, Td, Tr } from '@/components/ui/table';
import { Checkbox } from '../ui/fields';
import { Image } from '../ui/image';
import Modal from './actions/Modal.vue';

import { Inbox } from 'lucide-vue-next';

import { type Column } from '@/composables/dataTable/useDataTable';
import { formatters, type TableConditions } from '@/lib/dataTable';

const props = defineProps<{
    columns: Column[];
    data: Record<string, any>[];
    selectedIds: number[];
    tableConditions: TableConditions;
    toggleRowSelection: (id: number, value: boolean) => void;
    path: string;
    routeName: string;
    handleAction: (action: 'delete', idOrIds: number | number[]) => Promise<void>;
}>();
</script>

<template>
    <Tbody v-if="props.data.length > 0">
        <!-- Row: every data row && index: is the number of this row -->
        <Tr v-for="(row, index) in props.data" :key="index" class="text-body bg-content-3 w-3 text-sm font-medium">
            <Td class="max-w-5 border-none" v-if="props.tableConditions.enableRowsDelete">
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
                    props.tableConditions.enableUserAssignments
                "
            >
                <div class="flex items-center justify-end gap-2">
                    <Dialog v-for="action in ['edit', 'view', 'assignRoles', 'assignPermissions', 'userAssignments']" :key="action">
                        <DialogTrigger as-child>
                            <ActionEditBtn v-if="props.tableConditions.enableEdit && action === 'edit'" />
                            <ActionViewBtn v-if="props.tableConditions.enableView && action === 'view'" />
                            <ActionAssignmentsBtn v-if="props.tableConditions.enableAssignRoles && action === 'assignRoles'" />
                            <ActionAssignmentsBtn v-if="props.tableConditions.enableAssignPermissions && action === 'assignPermissions'" />
                            <ActionAssignmentsBtn v-if="props.tableConditions.enableUserAssignments && action === 'userAssignments'" />
                        </DialogTrigger>

                        <DialogScrollContent>
                            <DialogHeader>
                                <DialogTitle>{{ action.charAt(0).toUpperCase() + action.slice(1) }}</DialogTitle>
                                <DialogDescription class="sr-only"> No description provided. </DialogDescription>
                            </DialogHeader>

                            <Modal :action="action" :path="props.path" :routeName="props.routeName" :id="row.id" />
                        </DialogScrollContent>
                    </Dialog>

                    <ActionDeleteBtn v-if="props.tableConditions.enableDelete" @click="handleAction('delete', row.id)" />
                </div>
            </Td>
        </Tr>
    </Tbody>

    <Tbody v-else>
        <Tr class="w-full text-body-muted w-3 text-sm">
            <Td colspan="20">
                <div class="flex w-full flex-col items-center justify-center gap-2 py-3">
                    <Inbox class="size-10" />
                    <span class="text-body-muted text-sm font-semibold">No data founded</span>
                </div>
            </Td>
        </Tr>
    </Tbody>
</template>
