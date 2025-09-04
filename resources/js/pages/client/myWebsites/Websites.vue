<script setup lang="ts">
import Meta from '@/components/pagination/Meta.vue';
import Pagination from '@/components/pagination/Pagination.vue';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
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
import { type DataTableProps } from '@/composables/dataTable/useDataTable';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { toast } from '@/lib/sweetAlert';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { FilterIcon, Inbox, LoaderCircle, Search } from 'lucide-vue-next';
import { computed, reactive, ref, watch, watchEffect } from 'vue';
import Cards from './Cards.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Websites',
        href: '/dashboard/my-websites',
    },
];

const props = defineProps<{
    websites: DataTableProps;
    websiteTypes: Record<string, any>;
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info';
        message: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});

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

const hasWebsites = computed(() => props.websites.total > 0);
const hasFilteredResults = computed(() => props.websites.data.length > 0);

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
        <div
            v-if="hasWebsites"
            class="bg-card border-muted relative mx-2 my-4 flex h-full flex-col justify-between gap-3 rounded-md border p-2 sm:p-4 md:mx-4"
        >
            <PlaceholderPattern class="opacity-40" />

            <div class="flex h-full flex-col gap-4">
                <!-- Header -->
                <div class="border-muted bg-body z-5 flex w-full flex-wrap items-center justify-between gap-2 rounded-md border p-2.5 backdrop-blur">
                    <div class="flex flex-wrap items-center gap-2 sm:flex-nowrap">
                        <div class="relative sm:min-w-60">
                            <Input
                                class="w-full pl-9"
                                id="search"
                                type="search"
                                autofocus
                                :tabindex="1"
                                placeholder="searching..."
                                @input="searching"
                            />

                            <Search class="pointer-events-none absolute top-1/2 left-2 z-0 h-5 w-5 -translate-y-1/2 transform" />
                        </div>

                        <div class="flex items-center gap-2">
                            <SelectWithSearch
                                :options="[
                                    { value: 'newest', label: 'Newest' },
                                    { value: 'oldest', label: 'Oldest' },
                                    { value: 'name_asc', label: 'Name (A-Z)' },
                                    { value: 'name_desc', label: 'Name (Z-A)' },
                                ]"
                                v-model="filters.sort_by"
                                placeholder="Sort by"
                                class="max-w-30 min-w-30"
                            />

                            <DropdownMenu>
                                <DropdownMenuTrigger :as-child="true">
                                    <Button variant="ghost" size="icon" class="flex cursor-pointer items-center justify-center rounded-md">
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
                    </div>

                    <div class="flex items-center gap-2">
                        <Link :href="route('websiteBuilder.index')" target="_blank">
                            <Button>Create</Button>
                        </Link>
                    </div>
                </div>

                <!-- Websites Cards -->
                <div v-if="hasFilteredResults" class="grid h-full grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <Cards :websites="props.websites.data" />
                </div>

                <!-- No Data Found -->
                <div v-else class="border-muted relative flex h-full items-center justify-center gap-3 rounded-md border p-4">
                    <div class="flex flex-col items-center gap-1">
                        <Inbox class="text-body-muted size-30 md:size-35" />
                        <span class="text-active text-base font-medium md:text-lg">No Websites Found</span>
                    </div>

                    <div class="pointer-events-none absolute inset-0 z-0 flex h-full w-full items-center justify-center opacity-70 dark:opacity-50">
                        <div
                            class="size-[500px] bg-gradient-to-b from-transparent via-[var(--primary)] to-transparent opacity-25 blur-3xl dark:opacity-15"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div
                class="bg-body border-muted flex flex-col-reverse flex-wrap items-center justify-between gap-4 rounded-md border p-2.5 backdrop-blur sm:flex-row"
            >
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
        <div v-else class="bg-card border-muted relative m-4 flex h-full items-center justify-center gap-3 rounded-md border p-4">
            <PlaceholderPattern class="opacity-40" />

            <div class="z-5 flex flex-col items-center gap-1">
                <Inbox class="text-body-muted size-30 md:size-35" />
                <span class="text-active text-base font-medium md:text-lg">No Websites</span>
                <Link :href="route('websiteBuilder.index')" target="_blank" class="mt-8">
                    <Button size="lg">Create Your First</Button>
                </Link>
            </div>

            <div class="pointer-events-none absolute inset-0 z-0 flex h-full w-full items-center justify-center opacity-70 dark:opacity-50">
                <div
                    class="size-[500px] bg-gradient-to-b from-transparent via-[var(--primary)] to-transparent opacity-25 blur-3xl dark:opacity-15"
                ></div>
            </div>
        </div>
    </DashboardLayout>
</template>
