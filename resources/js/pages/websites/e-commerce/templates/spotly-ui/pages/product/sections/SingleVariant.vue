<script setup lang="ts">
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Ban } from 'lucide-vue-next';

const props = defineProps<{
    product: Record<string, any>;
}>();

const page = usePage<SharedData>();
</script>

<template>
    <div v-if="props.product.variants[0].display_quantity === 0" class="flex w-full items-center justify-center px-3 py-20">
        <div class="flex -rotate-18 items-center gap-1.5 rounded-md bg-red-500/10 p-2 text-base backdrop-blur-lg dark:bg-red-500/10">
            <Ban class="web-text-danger size-4.5" />
            <span class="web-text-danger font-bold">{{ $t('out.of.stock') }}</span>
        </div>
    </div>
    <template v-else>
        <div v-for="attr in props.product.variants[0].attributes" :key="attr.id" class="flex items-center gap-2">
            <span class="web-text-body-muted text-xs uppercase">
                {{ page.props.lang === 'ar' ? attr.attribute_name_ar : attr.attribute_name }}:
            </span>

            <template v-if="attr.color_code">
                <div class="web-border-color size-4 rounded-full border" :style="{ backgroundColor: attr.color_code }"></div>
                <span>{{ page.props.lang === 'ar' ? attr.color_name_ar : attr.color_name }}</span>
            </template>
            <template v-else>
                {{ page.props.lang === 'ar' ? attr.attribute_value_value_ar : attr.attribute_value_value }}
            </template>
        </div>
    </template>
</template>
