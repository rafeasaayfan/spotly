<script setup lang="ts">
import { formatters } from '@/lib/dataTable';
import { type Column } from '@/composables/dataTable/useDataTable';

const props = defineProps<{
    data: Record<string, any>;
    columns: Column[];
}>();
</script>

<template>
    <div class="grid gap-3 rounded-md lg:grid-cols-2">
        <div v-for="item in props.columns" :key="item.key" class="border-muted bg-card flex flex-col gap-3 rounded-md border p-4 shadow-sm">
            <div class="flex items-center gap-2">
                <p class="text-body-muted">{{ item.label.charAt(0).toUpperCase() + item.label.slice(1) }}</p>
            </div>

            <p class="text-body text-lg font-medium">
                <template v-if="item.type === 'date' || item.type === 'datetime'">
                    {{ formatters.date(props.data[item.key], 'short') }}
                </template>

                <template v-else-if="item.type === 'image'"></template>

                <template v-else>
                    {{ props.data[item.key] }}
                </template>
            </p>
        </div>
    </div>
</template>
