<script setup lang="ts">
import { Dialog, DialogScrollContent, DialogHeader, DialogTitle, DialogTrigger, DialogDescription } from '@/components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input, Toggle } from '@/components/ui/fields';
import { Button } from '../ui/button';

import { useForm } from '@inertiajs/vue3';
import { Filter as FilterIcon, Search, Settings } from 'lucide-vue-next';
import { ref, watch } from 'vue';

import FilterContent from './actions/Filter.vue';

import { type Column } from '@/composables/dataTable/useDataTable';
import { type TableConditions } from '@/lib/dataTable';
import { type Filter } from '@/types';
import Modal from './actions/Modal.vue';

interface HeaderProps {
    columns: Column[];
    filter?: Filter[];
    applyFilters: (overrides: Record<string, any>) => void;
    updateColumnVisibility: (key: string, value: boolean) => void;
    columnsVisibility: Record<string, boolean>;
    routeName: string;
    selectedIds: number[];
    search?: string;
    handleAction: (action: 'delete', idOrIds: number | number[]) => Promise<void>;
    tableConditions: TableConditions;
    path: string;
}

const props = defineProps<HeaderProps>();

const localSearch = ref(props.search);

// Update localSearch if props.search changes from outside
watch(
    () => props.search,
    (newVal) => {
        localSearch.value = newVal;
    },
);

// Watch localSearch and call applyFilters when it changes
watch(localSearch, (val) => {
    props.applyFilters({ search: val, page: 1 });
});

// Form for filter inputs
const form = useForm(Object.fromEntries((props.filter ?? []).map((column) => [column.key, ''])));

function setFormData(key: string, value: any) {
    form[key] = value;
}
</script>

<template>
    <div class="flex items-center justify-between gap-4 flex-wrap p-3">
        <div class="flex items-center gap-2">
            <div class="relative" v-if="props.tableConditions.enableSearch">
                <Input
                    class="w-full pl-9"
                    id="search"
                    type="search"
                    autofocus
                    :tabindex="1"
                    placeholder="searching..."
                    :value="localSearch"
                    v-model="localSearch"
                />

                <Search class="absolute top-1/2 left-2 h-5 w-5 -translate-y-1/2 transform z-0 pointer-events-none" />
            </div>

            <DropdownMenu v-if="props.tableConditions.enableColsVisible">
                <DropdownMenuTrigger :as-child="true">
                    <Button variant="ghost" size="icon" class="relative flex cursor-pointer items-center justify-center rounded-full">
                        <Settings class="size-5" />
                    </Button>
                </DropdownMenuTrigger>

                <DropdownMenuContent align="end" class="w-48">
                    <DropdownMenuShortcut>Colums Settings</DropdownMenuShortcut>

                    <DropdownMenuSeparator />

                    <DropdownMenuGroup class="gap-2">
                        <Toggle
                            v-for="column in props.columns"
                            :key="column.key"
                            :label="column.label"
                            :modelValue="columnsVisibility[column.key]"
                            @update:modelValue="(val) => updateColumnVisibility(column.key, val)"
                        />
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>

        <div class="flex items-center gap-2">
            <DropdownMenu v-if="tableConditions.enableFilter">
                <DropdownMenuTrigger :as-child="true">
                    <Button variant="ghost" size="icon" class="relative flex cursor-pointer items-center justify-center rounded-md">
                        <FilterIcon class="size-5" />
                    </Button>
                </DropdownMenuTrigger>

                <DropdownMenuContent align="end" class="w-60">
                    <DropdownMenuShortcut>Filter options</DropdownMenuShortcut>

                    <DropdownMenuSeparator />

                    <DropdownMenuGroup>
                        <FilterContent :filter="props.filter" :applyFilters="props.applyFilters" :form="form" :setFormData="setFormData" />
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>

            <Dialog v-if="props.selectedIds.length === 0 && props.tableConditions.enableCreate">
                <DialogTrigger as-child>
                    <Button>Create</Button>
                </DialogTrigger>

                <DialogScrollContent>
                    <DialogHeader>
                        <DialogTitle>Create</DialogTitle>
                        <DialogDescription class="sr-only"> No description provided. </DialogDescription>
                    </DialogHeader>

                    <Modal action="create" :path="props.path" :routeName="props.routeName" />
                </DialogScrollContent>
            </Dialog>

            <Button
                variant="destructive"
                v-else-if="props.selectedIds.length > 0 && props.tableConditions.enableRowsDelete"
                @click="handleAction('delete', props.selectedIds)"
            >
                Delete {{ props.selectedIds.length }} item
            </Button>
        </div>
    </div>
</template>