<script setup lang="ts">
import { Dialog, DialogDescription, DialogHeader, DialogScrollContent, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input, InputError, Select, SelectWithSearch, Toggle } from '@/components/ui/fields';
import { Button } from '../ui/button';

import { Filter as FilterIcon, LoaderCircle, Search, Settings } from 'lucide-vue-next';
import { computed, nextTick, ref, watch } from 'vue';

import { type Column } from '@/composables/dataTable/useDataTable';
import { type TableConditions } from '@/lib/dataTable';
import { type Filter } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { Label } from '../ui/label';
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
    handleAction: (action: 'create' | 'delete', idOrIds?: number | number[]) => Promise<void>;
    tableConditions: TableConditions;
    path: string;
    dashboardFor?: string;
    isModalForCreate?: boolean;
}

const props = withDefaults(defineProps<HeaderProps>(), {
    isModalForCreate: true,
});

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

const form = useForm(Object.fromEntries((props.filter ?? []).map((column) => [column.key, ''])));

function submit(key: string, val: any) {
    form[key] = val;

    props.applyFilters({ filter: form.data(), page: 1 });
}

const isResetting = ref(false);

function resetForm() {
    isResetting.value = true;

    setTimeout(() => {
        form.reset();
        isResetting.value = false;
    }, 500);

    nextTick(() => {
        props.applyFilters({ filter: '' });
    });
}

// Computed property for grid columns class
const gridColsClass = computed(() => {
    const filterLength = props.filter?.length ?? 0;
    if (filterLength >= 4) {
        return 'lg:grid-cols-4';
    }
    switch (filterLength) {
        case 1:
            return 'lg:grid-cols-1';
        case 2:
            return 'lg:grid-cols-2';
        case 3:
            return 'lg:grid-cols-3';
        default:
            return '';
    }
});

// Computed property for column span class
const colSpanClass = computed(() => {
    const filterLength = props.filter?.length ?? 0;
    if (filterLength >= 4) {
        return 'lg:col-span-4';
    }
    switch (filterLength) {
        case 1:
            return 'lg:col-span-1';
        case 2:
            return 'lg:col-span-2';
        case 3:
            return 'lg:col-span-3';
        default:
            return '';
    }
});
</script>

<template>
    <div class="flex flex-wrap items-center justify-between gap-4 p-3">
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

                <Search class="pointer-events-none absolute top-1/2 left-2 z-0 h-5 w-5 -translate-y-1/2 transform" />
            </div>

            <DropdownMenu v-if="props.tableConditions.enableColsVisible">
                <DropdownMenuTrigger :as-child="true">
                    <Button variant="ghost" size="icon" class="relative flex cursor-pointer items-center justify-center rounded-full">
                        <Settings class="size-5" />
                    </Button>
                </DropdownMenuTrigger>

                <DropdownMenuContent class="w-48">
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

                <DropdownMenuContent
                    class="w-[250px] sm:w-[500px]"
                    :class="props.filter && props.filter?.length > 1 ? 'lg:w-[800px]' : 'lg:w-[600px]'"
                >
                    <DropdownMenuShortcut>Filter options</DropdownMenuShortcut>

                    <DropdownMenuSeparator />

                    <DropdownMenuGroup>
                        <form class="grid grid-cols-1 gap-5 sm:grid-cols-2" :class="gridColsClass" enctype="multipart/form-data">
                            <div class="grid gap-1" v-for="(column, index) in props.filter" :key="index">
                                <Label class="text-[11px]" :for="column.label">{{
                                    column.label.charAt(0).toUpperCase() + column.label.slice(1)
                                }}</Label>

                                <Select
                                    v-if="column.type === 'select'"
                                    :id="column.label"
                                    v-model="form[column.key]"
                                    :option="column.placeholder ?? null"
                                    :withStatusColors="true"
                                    :placeholder="column.placeholder ?? column.label"
                                    @update:modelValue="(val: any) => submit(column.key, val)"
                                    class="gap-5 py-1.5 text-xs"
                                >
                                    <option v-for="option in column.options" :key="option.label" :value="option.value">{{ option.label }}</option>
                                </Select>

                                <SelectWithSearch
                                    v-if="column.type === 'select_with_search'"
                                    :id="column.label"
                                    v-model="form[column.key]"
                                    :placeholder="column.placeholder ?? column.label"
                                    :options="
                                        column.options?.map((option) => ({
                                            label: option.label ?? option,
                                            value: option.value ?? option,
                                        }))
                                    "
                                    class="h-7.5 py-1.5 text-xs"
                                    @change="submit"
                                />

                                <InputError class="mt-1" :message="form.errors?.[column.key]" />
                            </div>

                            <div class="border-muted col-span-1 mt-1 flex justify-end border-t pt-2 sm:col-span-2" :class="colSpanClass">
                                <Button
                                    variant="secondary"
                                    size="sm"
                                    type="button"
                                    :disabled="isResetting"
                                    @click="resetForm()"
                                    class="h-7 px-2 sm:text-xs"
                                >
                                    <LoaderCircle v-if="isResetting" class="h-4 w-4 animate-spin" />
                                    <span v-else>Reset</span>
                                </Button>
                            </div>
                        </form>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>

            <template v-if="props.selectedIds.length === 0 && props.tableConditions.enableCreate">
                <Dialog v-if="props.isModalForCreate">
                    <DialogTrigger as-child>
                        <Button type="button">Create</Button>
                    </DialogTrigger>

                    <DialogScrollContent>
                        <DialogHeader>
                            <DialogTitle>Create</DialogTitle>
                            <DialogDescription class="sr-only"> No description provided. </DialogDescription>
                        </DialogHeader>

                        <Modal action="create" :path="props.path" :routeName="props.routeName" :dashboardFor="props.dashboardFor" />
                    </DialogScrollContent>
                </Dialog>

                <Button v-else type="button" @click="handleAction('create')">Create</Button>
            </template>

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
