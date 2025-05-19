<script setup lang="ts">
import Meta from '@/components/pagination/Meta.vue';
import Pagination from '@/components/pagination/Pagination.vue';
import Header from '@/components/table/Header.vue';
import Limit from '@/components/table/Limit.vue';
import Table from '@/components/table/Table.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { useDataTable, type PaginationData } from '@/composables/dataTable/useDataTable';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/dashboard/users',
    },
];

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'email_verified_at', label: 'Email Verified' },
    { key: 'created_at', label: 'Created At' },
];

const props = defineProps<{
    users: PaginationData;
}>();

const { applyFilters, createPaginationMeta, columnsVisibility, updateColumnVisibility } = useDataTable({
    routeName: 'dashboard.users.index',
    initialFilters: {
        // sort_by: 'created_at',
        // You can override any default filters here
    },
    columns: columns,
});

const filteredCols = computed(() => {
    return columns.filter((column) => columnsVisibility.value[column.key] !== false);
});

const meta = createPaginationMeta(props.users);
</script>

<template>
    <Head title="Users" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-2 p-4">
            <Header
                :columns="columns"
                :applyFilters="applyFilters"
                :columnsVisibility="columnsVisibility"
                :updateColumnVisibility="updateColumnVisibility"
            />

            <Table :columns="filteredCols" :data="props.users.data" />

            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <Limit :applyFilters="applyFilters" />
                    <Meta :meta="meta" />
                </div>

                <Pagination :links="props.users.links" :applyFilters="applyFilters" />
            </div>
        </div>
    </DashboardLayout>
</template>
