<script setup lang="ts">
import Meta from '@/components/pagination/Meta.vue';
import Pagination from '@/components/pagination/Pagination.vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input, Select, SelectWithSearch } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import Cards from './Cards.vue';
import { type DataTableProps } from '@/composables/dataTable/useDataTable';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    FilterIcon,
    GridIcon,
    Inbox,
    ListIcon,
    LoaderCircle,
    Search,
} from 'lucide-vue-next';
import { computed, reactive, ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Websites',
        href: '/dashboard/my-websites',
    },
];

const props = defineProps<{
    websites: DataTableProps;
    websiteTypes: Record<string, any>;
}>();

function createPaginationMeta(paginationData: DataTableProps) {
    return computed(() => ({
        current_page: paginationData.current_page,
        per_page: paginationData.per_page,
        total: paginationData.total,
        last_page: paginationData.last_page,
        from: paginationData.from,
        to: paginationData.to,
    }));
}

const meta = computed(() => createPaginationMeta(props.websites));

const mappedTypes = props.websiteTypes.map((item: any) => ({
    value: item.id,
    label: item.title,
}));

const query = route().queryParams;
const filters = reactive({
    search: String(query.search || ''),
    sort_by: String(query.sort_by || ''),
    website_type: String(query.website_type || ''),
    status: String(query.status || ''),
    active: String(query.active || ''),
    limit: Number(query.limit || 6),
    page: Number(query.page || 1),
});

const isApplied = ref(false);
const applyFilters = () => {
    isApplied.value = true;
    router.get(
        route('client.myWebsites'),
        { ...filters },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onFinish: () => {
                isApplied.value = false;
            },
        },
    );
};

watch(
    () => ({ ...filters }),
    () => {
        applyFilters();
    },
    { deep: true },
);

const isResetting = ref(false);
const resetDropDownFilter = async () => {
    isResetting.value = true;

    setTimeout(() => {
        filters.active = '';
        filters.status = '';
        filters.website_type = '';
        isResetting.value = false;
        applyFilters();
    }, 500);
};

const updatePage = (field: number) => {
    filters.page = field;
};

const searching = (event: Event) => {
    const target = event.target as HTMLInputElement;
    filters.search = target.value;
    filters.page = 1;
};
</script>

<template>
    <Head title="My Websites" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div v-if="props.websites.data.length >= 0" class="border-muted relative m-4 flex h-full flex-col gap-3 rounded-md border p-4">
            <!-- Header -->
            <div class="border-muted flex w-full flex-wrap gap-2 items-center justify-between rounded-md border bg-black/1 p-2 dark:bg-white/1">
                <div class="flex items-center gap-2">
                    <div class="relative min-w-60">
                        <Input class="w-full pl-9" id="search" type="search" autofocus :tabindex="1" placeholder="searching..." @input="searching" />

                        <Search class="pointer-events-none absolute top-1/2 left-2 z-0 h-5 w-5 -translate-y-1/2 transform" />
                    </div>

                    <SelectWithSearch
                        :options="[
                            { value: 'newest', label: 'Newest' },
                            { value: 'oldest', label: 'Oldest' },
                            { value: 'name_asc', label: 'Name (A-Z)' },
                            { value: 'name_desc', label: 'Name (Z-A)' },
                        ]"
                        v-model="filters.sort_by"
                        placeholder="Sort by"
                        class="min-w-30"
                    />

                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button variant="ghost" size="icon" class="relative flex cursor-pointer items-center justify-center rounded-md">
                                <FilterIcon class="size-5" />
                            </Button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent align="end" class="w-70">
                            <DropdownMenuShortcut>Filter options</DropdownMenuShortcut>

                            <DropdownMenuSeparator />

                            <DropdownMenuGroup>
                                <div class="grid gap-5">
                                    <div class="flex flex-col gap-1">
                                        <Label class="text-xs" for="website_type">Website Type</Label>
                                        <SelectWithSearch
                                            id="website_type"
                                            v-model="filters.website_type"
                                            placeholder="Choose a website type"
                                            :options="mappedTypes"
                                        />
                                    </div>

                                    <div class="flex flex-col gap-1">
                                        <Label class="text-xs" for="status">Status</Label>
                                        <Select id="status" v-model="filters.status" placeholder="Select website status">
                                            <option v-for="(option, index) in ['pending', 'denied', 'approved']" :key="index" :value="option">
                                                {{ option }}
                                            </option>
                                        </Select>
                                    </div>

                                    <div class="flex flex-col gap-1">
                                        <Label class="text-xs" for="active">Active</Label>
                                        <Select id="active" v-model="filters.active" placeholder="Select active website">
                                            <option
                                                v-for="(option, index) in [
                                                    { value: '1', label: 'active' },
                                                    { value: '0', label: 'inactive' },
                                                ]"
                                                :key="index"
                                                :value="option.value"
                                            >
                                                {{ option.label }}
                                            </option>
                                        </Select>
                                    </div>
                                </div>

                                <div class="border-muted mt-4 flex justify-end border-t pt-2">
                                    <Button
                                        variant="secondary"
                                        size="sm"
                                        type="button"
                                        :disabled="isResetting || isApplied"
                                        @click="resetDropDownFilter()"
                                    >
                                        <LoaderCircle v-if="isResetting" class="h-4 w-4 animate-spin" />
                                        <span v-else>Reset</span>
                                    </Button>
                                </div>
                            </DropdownMenuGroup>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>

                <div class="flex items-center gap-2">
                    <div class="flex">
                        <!-- grid view -->
                        <Button variant="outline" class="rounded-s-md rounded-e-none">
                            <GridIcon class="size-4" />
                        </Button>

                        <!-- list view -->
                        <Button variant="outline" class="rounded-s-none rounded-e-md">
                            <ListIcon class="size-4" />
                        </Button>
                    </div>

                    <Link :href="route('websiteBuilder.index')" target="_blank">
                        <Button>Create</Button>
                    </Link>
                </div>
            </div>

            <!-- Websites Cards -->
            <div v-if="props.websites.data.length > 0" class="mt-1 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <Cards :websites="props.websites.data" />
            </div>

            <!-- No Data Found -->
            <div v-else class="border-muted relative flex h-full items-center justify-center gap-3 rounded-md border p-4">
                <div class="flex size-70 flex-col items-center gap-1 rounded-full">
                    <Inbox class="size-40" />
                    <span class="text-lg font-bold">No Websites Found</span>
                </div>

                <div class="pointer-events-none absolute inset-0 z-0 flex h-full w-full items-center justify-center">
                    <div
                        class="size-[500px] bg-gradient-to-b from-transparent via-[var(--primary)] to-transparent opacity-25 blur-3xl dark:opacity-15"
                    ></div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="border-muted flex flex-col-reverse flex-wrap items-center justify-between gap-4 rounded-md border p-2 sm:flex-row">
                <div class="flex flex-wrap items-center gap-3">
                    <Select
                        v-if="props.websites.data.length > 1"
                        v-model="filters.limit"
                        parentClass="h-8 w-14"
                        class="ps-1.5"
                        :option="'Items per page'"
                        @change="filters.page = 1"
                    >
                        <option v-for="limitOption in [3, 6, 9, 12]" :key="limitOption" :value="limitOption">
                            {{ limitOption }}
                        </option>
                    </Select>
                    <Meta :meta="meta.value" />
                </div>

                <Pagination :links="props.websites.links" :data="props.websites" @updatePage="updatePage" />
            </div>
        </div>

        <!-- The Client Dont Have Any Website -->
        <div v-else class="border-muted relative m-4 flex h-full items-center justify-center gap-3 rounded-md border p-4">
            <div class="flex size-70 flex-col items-center gap-1 rounded-full">
                <Inbox class="size-40" />
                <span class="text-lg font-bold">No Websites</span>
                <Link :href="route('websiteBuilder.index')" target="_blank" class="mt-8">
                    <Button size="lg">Create Your First</Button>
                </Link>
            </div>

            <div class="pointer-events-none absolute inset-0 z-0 flex h-full w-full items-center justify-center">
                <div
                    class="size-[500px] bg-gradient-to-b from-transparent via-[var(--primary)] to-transparent opacity-25 blur-3xl dark:opacity-15"
                ></div>
            </div>
        </div>
    </DashboardLayout>
</template>
