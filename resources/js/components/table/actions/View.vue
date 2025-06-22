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
        <div v-for="column in props.columns" :key="column.key"
            :class="column.type === 'textarea' ? 'col-span-2' : ''" class="border-muted bg-body flex flex-col gap-3 rounded-md border p-4 shadow-sm">
            <div class="flex items-center gap-2">
                <p class="text-body-muted">{{ column.label.charAt(0).toUpperCase() + column.label.slice(1) }}</p>
            </div>

            <p class="text-base font-medium">
                <template v-if="column.type === 'date'">
                    {{ formatters.date(props.data[column.key], 'short') }}
                </template>

                <template v-else-if="column.type === 'image'">
                    <Image v-if="props.data[column.key]" :src="props.data[column.key]" alt="Image" class="h-12 w-12 !rounded-full object-cover" />
                </template>

                <template v-else-if="column.key === 'email_verified_at'">
                    <span v-html="formatters.EmailVerified(props.data[column.key])"></span>
                </template>

                <template v-else-if="column.type === 'highlight'">
                    <div class="flex flex-wrap gap-1">
                        <span
                            v-for="(item, index) in formatters.splitAndStyle(props.data[column.key])"
                            :key="index"
                            class="rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300"
                        >
                            {{ item }}
                        </span>
                    </div>
                </template>

                <template v-else-if="column.type === 'boolean'">
                    {{ formatters.boolean(props.data[column.key]) }}
                </template>

                <template v-else-if="column.type === 'status'">
                    {{ formatters.status(props.data[column.key]) }}
                </template>

                <template v-else>
                    {{ props.data[column.key] }}
                </template>
            </p>
        </div>
    </div>
</template>
