<script setup lang="ts">
import { Dialog, DialogDescription, DialogHeader, DialogScrollContent, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { ActionAssignmentsBtn, ActionDeleteBtn, ActionEditBtn, ActionViewBtn, Tbody, Td, Tr } from '@/components/ui/table';
import { Checkbox, Select, Toggle } from '../ui/fields';
import { Image } from '../ui/image';
import Modal from './actions/Modal.vue';

import { Inbox } from 'lucide-vue-next';

import { type Column } from '@/composables/dataTable/useDataTable';
import { formatters, type TableConditions } from '@/lib/dataTable';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    columns: Column[];
    data: Record<string, any>[];
    selectedIds: number[];
    tableConditions: TableConditions;
    toggleRowSelection: (id: number, value: boolean) => void;
    path: string;
    routeName: string;
    handleAction: (action: 'delete', idOrIds: number | number[]) => Promise<void>;
    dashboardFor?: string;
}>();

function updateCol(key: string, value: any, id: number, oldVal?: string) {
    if (oldVal !== value) {
        const form = useForm({ [key]: value });

        form.patch(route(`${props.routeName}.${key}`, id), {
            preserveScroll: true,
        });
    }
}
</script>

<template>
    <Tbody v-if="props.data.length > 0">
        <!-- Row: every data row && index: is the number of this row -->
        <Tr v-for="(row, index) in props.data" :key="index" class="text-body bg-content-3 w-3 text-sm font-medium">
            <Td class="max-w-5 border-none" v-if="props.tableConditions.enableRowsDelete">
                <Checkbox
                    :modelValue="selectedIds.includes(row.id)"
                    @update:modelValue="(val) => typeof val === 'boolean' && toggleRowSelection(row.id, val)"
                />
            </Td>

            <Td v-for="column in props.columns" :key="column.key">
                <template v-if="column.type === 'images'">
                    <div class="flex max-w-full items-center gap-1 overflow-x-auto py-1">
                        <Image
                            v-for="item in row[column.key]"
                            :key="item.uid"
                            :src="item.original_url"
                            alt="Image"
                            class="h-10 w-10 !rounded-full object-cover"
                        />
                    </div>
                </template>
                <template v-else-if="column.type === 'image'">
                    <Image v-if="row[column.key]" :src="row[column.key]" alt="Image" class="h-10 w-10 !rounded-full object-cover" />
                </template>
                <template v-else-if="column.key === 'email_verified_at'">
                    <span v-html="formatters.emailVerified(row[column.key])"></span>
                </template>
                <template v-else-if="column.type === 'date'">
                    {{ formatters.date(row[column.key], 'long') }}
                </template>
                <template v-else-if="column.type === 'highlight'">
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
                <template v-else-if="column.type === 'toggle'">
                    <Toggle :modelValue="row[column.key] === 1" @update:modelValue="(val) => updateCol(column.key, val, row.id)" />
                </template>
                <template v-else-if="column.type === 'select'">
                    <Select
                        :id="column.label"
                        class="ps-1 text-xs"
                        :modelValue="row[column.key]"
                        :placeholder="column.placeholder ?? column.label"
                        :withStatusColors="true"
                        :required="column.required"
                        @update:modelValue="
                            (val) => {
                                const oldVal = row[column.key];
                                updateCol(column.key, val, row.id, oldVal);
                            }
                        "
                    >
                        <option v-for="option in column.options" :key="option.label" :value="option.value">{{ option.label }}</option>
                    </Select>
                </template>
                <template v-else-if="column.type === 'boolean'">
                    <span v-html="formatters.boolean(row[column.key])"></span>
                </template>
                <template v-else-if="column.type === 'active'">
                    <span v-html="formatters.active(row[column.key])"></span>
                </template>
                <template v-else-if="column.type === 'status'">
                    <span v-html="formatters.status(row[column.key])"></span>
                </template>
                <template v-else-if="column.type === 'email'">
                    <a :href="`mailto:${row[column.key]}`">{{ row[column.key] }}</a>
                </template>
                <template v-else-if="column.type === 'phone_number'">
                    <a :href="`tel:${row[column.key]}`">{{ row[column.key] }}</a>
                </template>
                <template v-else-if="column.type === 'url'">
                    <a :href="`${row[column.key]}`" target="_blank">{{ column.key }}</a>
                </template>
                <template v-else-if="column.type === 'color'">
                    <span v-html="formatters.color(row[column.key])"></span>
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
                <div class="flex items-center justify-end gap-1.5">
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

                            <Modal :action="action" :path="props.path" :routeName="props.routeName" :id="row.id" :dashboardFor="props.dashboardFor" />
                        </DialogScrollContent>
                    </Dialog>

                    <ActionDeleteBtn v-if="props.tableConditions.enableDelete" @click="handleAction('delete', row.id)" />
                </div>
            </Td>
        </Tr>
    </Tbody>

    <Tbody v-else>
        <Tr class="text-body-muted w-3 w-full text-sm">
            <Td colspan="20" class="border-none">
                <div class="flex w-full flex-col items-center justify-center gap-2 py-3">
                    <Inbox class="size-10" />
                    <span class="text-body-muted text-sm font-semibold">No data founded</span>
                </div>
            </Td>
        </Tr>
    </Tbody>
</template>
