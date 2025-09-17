<script setup lang="ts">
import { Image } from '@/components/ui/image';
import { type Column } from '@/composables/dataTable/useDataTable';
import { formatters } from '@/lib/dataTable';

const props = defineProps<{
    data: Record<string, any>;
    columns: Column[];
}>();
</script>

<template>
    <div class="grid grid-cols-1 gap-3 rounded-md lg:grid-cols-2 px-4 pb-3">
        <div
            v-for="column in props.columns"
            :key="column.key"
            :class="['textarea', 'image'].includes(column.type ?? '') ? 'lg:col-span-2' : ''"
            class="border-muted relative flex flex-col gap-3 rounded-md border p-4 shadow-sm"
        >
            <div class="absolute inset-0 w-full h-full bg-gradient-to-br 
            from-[var(--primary)]/5 via-[var(--success)]/5 to-[var(--destructive)]/5 blur-xl"></div>

            <div class="flex items-center gap-2">
                <p class="text-body-muted">{{ column.label.charAt(0).toUpperCase() + column.label.slice(1) }}</p>
            </div>

            <p class="text-base font-medium">
                <template v-if="column.type === 'date'">
                    {{ formatters.date(props.data[column.key], 'long') }}
                </template>

                <template v-else-if="column.type === 'image'">
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
                        <Image v-if="props.data[column.key]" :src="props.data[column.key]" alt="Image" class="h-12 w-12 !rounded-full object-cover" />
                    </template>
                </template>

                <template v-else-if="column.key === 'email_verified_at'">
                    <span v-html="formatters.emailVerified(props.data[column.key])"></span>
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
                    <span v-html="formatters.boolean(props.data[column.key])"></span>
                </template>

                <template v-else-if="column.type === 'active'">
                    <span v-html="formatters.active(props.data[column.key])"></span>
                </template>

                <template v-else-if="column.type === 'status'">
                    <span v-html="formatters.status(props.data[column.key])"></span>
                </template>

                <template v-else-if="column.type === 'email'">
                    <a :href="`mailto:${props.data[column.key]}`">{{ props.data[column.key] }}</a>
                </template>

                <template v-else-if="column.type === 'phone_number'">
                    <a :href="`tel:${props.data[column.key]}`">{{ props.data[column.key] }}</a>
                </template>

                <template v-else-if="column.type === 'url'">
                    <a :href="`${props.data[column.key]}`" target="_blank">{{ column.key }}</a>
                </template>

                <template v-else-if="column.type === 'color'">
                    <span v-html="formatters.color(props.data[column.key])"></span>
                </template>

                <template v-else>
                    {{ props.data[column.key] }}
                </template>
            </p>
        </div>
    </div>
</template>
