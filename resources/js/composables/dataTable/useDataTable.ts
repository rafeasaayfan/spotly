import { router } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

export interface DataTableProps {
    data: Record<string, any>[];
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    total: number;
    per_page: number;
    current_page: number;
    last_page: number;
    from: number;
    to: number;
}

export interface Column {
    key: string;
    label: string;
    visible?: boolean;
    type?: string;
    placeholder?: string;
    relation?: Array<{ value: string | number; label: string }>;
    options?: Array<{ value: string | number; label: string }>;
    required?: boolean;
}

export interface FilterOptions {
    search?: string;
    // Record<string, any> allows for any key-value pair
    // This is useful for dynamic filters where the keys are not known in advance
    // and can be any string
    filter?: Record<string, any>;
    sort_by?: string;
    sort_dir?: 'asc' | 'desc';
    limit?: number;
    page?: number;
}

export interface DataTableOptions {
    routeName?: string;
    initialFilters?: FilterOptions;
    columns?: Column[];
}

export function useDataTable(options: DataTableOptions) {
    const query = route().queryParams;

    const filters = reactive<FilterOptions>({
        search: '',
        filter: {},
        sort_by: 'id',
        sort_dir: 'desc',
        limit: 10,
        page: 1,
        ...options.initialFilters,
        ...query,
    });

    const columnsVisibility = ref<Record<string, boolean>>(
        options.columns
            ? options.columns.reduce(
                  (acc, column) => {
                      acc[column.key] = column.visible ?? true;
                      return acc;
                  },
                  {} as Record<string, boolean>,
              )
            : {},
    );

    // Partial means that the object can have any of the properties of FilterOptions
    // but not necessarily all of them
    function applyFilters(overrides: Partial<FilterOptions> = {}) {
        Object.assign(filters, overrides);

        if (options.routeName) {
            router.get(
                route(options.routeName + '.index'),
                { ...filters },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                },
            );
        }
    }

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

    function updateColumnVisibility(key: string, visible: boolean) {
        columnsVisibility.value[key] = visible;
    }

    return {
        filters,
        applyFilters,
        createPaginationMeta,
        columnsVisibility,
        updateColumnVisibility,
    };
}
