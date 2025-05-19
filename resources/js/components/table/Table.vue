<script setup lang="ts">
import { ActionDeleteBtn, ActionEditBtn, ActionViewBtn, Table, Tbody, Td, Th, Thead, Tr } from '@/components/ui/table';
import Checkbox from '../ui/fields/Checkbox.vue';
import { type Column } from '@/composables/dataTable/useDataTable';

interface TableProps {
    columns: Column[];
    data: Record<string, any>[];
}

const props = defineProps<TableProps>();
</script>

<template>
    <div class="relative overflow-x-auto rounded-md border border-slate-500/10 shadow xl:overflow-visible dark:border-slate-600/10">
        <Table>
            <Thead>
                <Tr>
                    <Th class="w-3">
                        <Checkbox />
                    </Th>
                    <Th v-for="column in props.columns" :key="column.key">{{ column.label }}</Th>
                    <Th class="w-10">
                    </Th>
                </Tr>
            </Thead>

            <Tbody>
                <!-- Row: every data row && index: is the number of this row -->
                <Tr v-for="(row, index) in props.data" :key="index" class="w-3 text-sm text-slate-950/90 dark:text-slate-100/90">
                    <Td class="max-w-5">
                        <Checkbox />
                    </Td>

                    <Td v-for="column in props.columns" :key="column.key">{{ row[column.key] }}</Td>

                    <Td>
                        <div class="flex gap-2 items-center justify-end">
                            <ActionEditBtn />
                            <ActionViewBtn />
                            <ActionDeleteBtn />
                        </div>
                    </Td>
                </Tr>
            </Tbody>
        </Table>
    </div>
</template>
