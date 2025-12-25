<script setup lang="ts">
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    selectedVariant: Record<string, any> | null;
    product: Record<string, any>;
}>();

const page = usePage<SharedData>();

const emit = defineEmits<{
    (e: 'changeSelectedVariant', selectedVariant: Record<string, any> | null): void;
}>();
</script>

<template>
    <div class="custom-scrollbar flex pt-4 gap-3 overflow-x-auto max-w-full">
        <div
            v-for="variant in props.product.variants"
            :key="variant.id"
            @click="emit('changeSelectedVariant', variant)"
            class="web-text-body flex min-w-45 cursor-pointer flex-col gap-2 rounded-md border-2 border-[var(--border_color_light)] bg-black/2 px-2 py-2 transition-all duration-300 hover:-translate-y-0.5 hover:border-[var(--primary_light)] dark:border-[var(--border_color_dark)] dark:bg-white/2 dark:hover:border-[var(--primary_dark)]"
            :class="[
                selectedVariant?.id === variant.id
                    ? '-translate-y-1 border-[var(--primary_light)] hover:-translate-y-1 dark:border-[var(--primary_dark)]'
                    : '',
                variant.display_quantity === 0 ? 'pointer-events-none relative' : '',
            ]"
        >
            <div v-if="variant.display_quantity === 0" class="absolute inset-0 z-10 flex h-full w-full items-center justify-center">
                <div class="flex -rotate-18 items-center gap-1 rounded-md bg-red-500/10 p-2 text-sm backdrop-blur-lg dark:bg-red-500/10">
                    <Ban class="web-text-danger size-3.5" />
                    <span class="web-text-danger font-bold">{{ $t('out.of.stock') }}</span>
                </div>
            </div>

            <div
                class="web-border-color flex flex-col gap-2 border-b pb-1"
                v-if="variant.display_quantity !== null || variant.price !== null"
                :class="variant.display_quantity === 0 ? 'pointer-events-none opacity-50 blur-[1px]' : ''"
            >
                <div class="flex w-full items-center justify-between" v-if="variant.display_quantity !== null">
                    <span class="web-text-body-muted text-[10px] uppercase"> {{ $t('quantity') }}: </span>
                    <span class="web-text-active text-sm font-bold">
                        {{ variant.display_quantity }}
                    </span>
                </div>
                <div class="flex w-full items-center justify-between" v-if="variant.price !== null">
                    <span class="web-text-body-muted text-[10px] uppercase">
                        {{ $t('unit.price') }}
                    </span>
                    <span class="web-text-active text-sm font-bold"> {{ variant.price }}$ </span>
                </div>
            </div>

            <div
                v-for="attribute in variant.attributes"
                :key="attribute.id"
                class="flex items-center justify-between"
                :class="variant.display_quantity === 0 ? 'pointer-events-none opacity-50 blur-[1px]' : ''"
            >
                <span class="web-text-body-muted text-[10px] uppercase"
                    >{{ page.props.lang === 'ar' ? attribute.attribute_name_ar : attribute.attribute_name }}:</span
                >
                <div class="flex flex-wrap items-center gap-2 text-sm font-medium">
                    <span v-if="attribute.attribute_value_id">{{
                        page.props.lang === 'ar' ? attribute.attribute_value_value_ar : attribute.attribute_value_value
                    }}</span>
                    <span v-else-if="attribute.color_id" class="flex items-center gap-1">
                        <div class="web-border-color size-4 rounded-full border" :style="{ backgroundColor: attribute.color_code }"></div>
                        {{ page.props.lang === 'ar' ? attribute.color_name_ar : attribute.color_name }}
                    </span>
                    <span v-else>{{ page.props.lang === 'ar' ? attribute.attribute_value_value_ar : attribute.attribute_value_value }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 1px;
    height: 1px;
    scrollbar-width: thin;
    background: transparent !important;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent !important;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: transparent !important;
}
</style>
