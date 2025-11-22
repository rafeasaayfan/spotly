<script setup lang="ts">
import { Image } from '@/components/ui/image';
import { type Column } from '@/composables/dataTable/useDataTable';
import { formatters } from '@/lib/dataTable';
import { computed } from 'vue';

const props = defineProps<{
    data: Record<string, any>;
    columns: Column[];
}>();

// Separate base fields from other fields
const baseFields = computed(() => {
    return props.columns.filter(
        (column) => column.type === 'baseName' || column.type === 'baseStatus' || column.type === 'baseActive' || column.key === 'email_verified_at',
    );
});

const otherFields = computed(() => {
    return props.columns.filter(
        (column) => column.type !== 'baseName' && column.type !== 'baseStatus' && column.type !== 'baseActive' && column.key !== 'email_verified_at',
    );
});
</script>

<template>
    <div class="grid grid-cols-1 gap-5 rounded-md px-4 pb-3">
        <!-- Base Fields Section -->
        <div v-if="baseFields.length > 0" class="border-muted flex flex-col border-b pb-4 gap-1">
            <template v-for="column in baseFields" :key="column.key">
                <div v-if="column.type === 'baseName'" class="text-active-link text-2xl font-bold">
                    {{ props.data[column.key] }}
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span v-if="column.type === 'baseActive'" v-html="formatters.active(props.data[column.key])"></span>
                    <span v-else-if="column.type === 'baseStatus'" v-html="formatters.status(props.data[column.key])"></span>
                    <span v-else-if="column.key === 'email_verified_at'" v-html="formatters.emailVerified(props.data[column.key])"></span>
                </div>
            </template>
        </div>

        <!-- Other Fields Loop -->
        <div
            v-for="column in otherFields"
            :key="column.key"
            :class="[
                ['textarea', 'image', 'array'].includes(column.type ?? '') ? 'flex-col gap-1' : 'flex-row items-center justify-between gap-3',
                column.label === 'Created At' ? 'border-muted border-t pt-4' : '',
            ]"
            class="relative flex"
        >
            <p class="text-body-muted text-sm">
                {{ column.label.charAt(0).toUpperCase() + column.label.slice(1) }}
            </p>

            <div v-if="column.type === 'image'" class="border-muted border-b pb-4">
                <template v-if="typeof props.data[column.key] === 'object'">
                    <div class="flex flex-wrap gap-2">
                        <Image
                            v-for="image in props.data[column.key]"
                            :key="image.id"
                            :src="image.original_url"
                            alt="Image"
                            class="h-36 w-36 !rounded-full object-cover"
                        />
                    </div>
                </template>
                <template v-else>
                    <Image v-if="props.data[column.key]" :src="props.data[column.key]" alt="Image" class="h-36 w-36 !rounded-full object-cover" />
                </template>
            </div>

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

            <template v-else-if="column.type === 'email'">
                <a :href="`mailto:${props.data[column.key]}`">{{ props.data[column.key] }}</a>
            </template>

            <template v-else-if="column.type === 'phone_number'">
                <a :href="`tel:${props.data[column.key]}`">{{ props.data[column.key] }}</a>
            </template>

            <template v-else-if="column.type === 'boolean'">
                <span v-html="formatters.boolean(props.data[column.key])"></span>
            </template>

            <template v-else-if="column.type === 'active'">
                <span v-html="formatters.active(props.data[column.key])"></span>
            </template>

            <template v-else-if="column.type === 'status'">
                <span v-html="formatters.status(props.data[column.key])"></span>
            </template>

            <template v-else-if="column.type === 'url'">
                <a :href="`${props.data[column.key]}`" target="_blank">{{ column.key }}</a>
            </template>

            <template v-else-if="column.type === 'color'">
                <span v-html="formatters.color(props.data[column.key])"></span>
            </template>

            <template v-else-if="column.type === 'array'">
                <ul class="list-disc space-y-1 pl-5">
                    <li v-for="(feature, index) in props.data[column.key]" :key="index" class="text-sm">
                        {{ feature }}
                    </li>
                </ul>
            </template>

            <template v-else-if="column.type === 'date'">
                {{ formatters.date(props.data[column.key], 'long') }}
            </template>

            <template v-else>
                {{ props.data[column.key] }}
            </template>
        </div>
    </div>
</template>
