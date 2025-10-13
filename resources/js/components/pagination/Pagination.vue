<script setup lang="ts">
import { type TableConditions } from '@/lib/dataTable';
import { generateBladeStylePagination, type PaginationData, type PaginationLink } from '@/lib/pagination';
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { ArrowBigLeftDash, ArrowBigRightDash, Ellipsis } from 'lucide-vue-next';
import { computed } from 'vue';
import { PaginationBtn } from '../ui/pagination';

const props = defineProps<{
    links: PaginationLink[];
    data?: PaginationData;
    applyFilters?: (overrides: Record<string, any>) => void;
    tableConditions?: TableConditions;
    btnClass?: string;
    activeClass?: string;
}>();

const emit = defineEmits<{
    (e: 'updatePage', field: number): void;
}>();

const paginationLinks = computed(() => generateBladeStylePagination(props.data, props.links));

const page = usePage<SharedData>();

function goToPage(url: string | null) {
    if (!url) return;

    const pageNumber = new URL(url).searchParams.get('page');

    if (pageNumber) {
        if (props.applyFilters) {
            props.applyFilters({ page: pageNumber });
        } else {
            emit('updatePage', Number(pageNumber));
        }
    }
}
</script>

<template>
    <div v-if="paginationLinks.length > 1 && (props.tableConditions?.enablePagination ?? true)" class="flex items-center gap-1.5">
        <div v-for="link in paginationLinks" :key="link.label">
            <PaginationBtn @click="goToPage(link.url)" :disabled="!link.url" :active="link.active" :class="props.btnClass" :activeClass="props.activeClass">
                <template v-if="!isNaN(Number(link.label))">
                    {{ link.label }}
                </template>

                <template v-else-if="link.label.includes('Previous')">
                    <component :is="page.props.lang == 'ar' ? ArrowBigRightDash : ArrowBigLeftDash" class="h-5.5 w-5.5" />
                </template>

                <template v-else-if="link.label.includes('Next')">
                    <component :is="page.props.lang == 'ar' ? ArrowBigLeftDash : ArrowBigRightDash" class="h-5.5 w-5.5" />
                </template>

                <template v-else-if="link.label === '...'">
                    <Ellipsis class="h-5.5 w-5.5" />
                </template>
            </PaginationBtn>
        </div>
    </div>
</template>
