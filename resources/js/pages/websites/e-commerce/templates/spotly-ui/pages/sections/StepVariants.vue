<script setup lang="ts">
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    selectedVariant: Record<string, any>;
    product: Record<string, any>;
    changeSelectedVariant: (selectedVariant: Record<string, any> | null) => void;
}>();
const page = usePage<SharedData>();

const attributeIndexSelected = ref(0);
const availableVariantIds = ref([]);
const valuesSelected = ref<string[]>([]);

const handleValueSelected = (attributeIndex: number, variant_ids: [], value: string) => {
    valuesSelected.value[attributeIndex] = value;
    valuesSelected.value = valuesSelected.value.slice(0, attributeIndex + 1);

    if (props.product.attributes_values_map.length === valuesSelected.value.length) {
        const selected = props.product.value.variants.find((variant: any) => {
            if (!variant.attributes) return false;
            const variantValues = variant.attributes.map((attr: any) => attr.color_code ?? attr.attribute_value_value);
            // Check if all values in valuesSelected are present in variantValues
            return valuesSelected.value.every((val) => variantValues.includes(val));
        });

        if (selected) {
            props.changeSelectedVariant(selected);
            return;
        } else {
            props.changeSelectedVariant(null);
        }
    }

    attributeIndexSelected.value = attributeIndex + 1;

    variant_ids.forEach((variant_id) => {
        if (!availableVariantIds.value.includes(variant_id)) {
            availableVariantIds.value.push(variant_id);
        }
    });
};

const handleAvailableValues = (values: []) => {
    type AttributeValue = {
        id: number | string;
        variant_ids: [];
        [key: string]: any;
    };

    if (!availableVariantIds.value.length) {
        return values;
    }

    const arrayValues: AttributeValue[] = Array.isArray(values) ? values : Object.values(values);

    return arrayValues.filter((value) => value.variant_ids.some((id) => (availableVariantIds.value as any[]).includes(id)));
};
</script>

<template>
    <div class="flex flex-col gap-3 pt-4">
        <div
            v-for="(attribute, index) in product.attributes_values_map"
            :key="attribute.id"
            class="flex-col gap-1"
            :class="Number(index) <= attributeIndexSelected ? 'flex' : 'hidden'"
        >
            <span class="web-text-body-muted text-xs uppercase">
                {{ page.props.lang === 'ar' ? attribute.attribute_name_ar : attribute.attribute_name }}:
            </span>

            <div class="flex items-center gap-2">
                <div
                    v-for="value in handleAvailableValues(attribute.values)"
                    :key="value.id"
                    class="flex cursor-pointer items-center gap-1 rounded border-2 border-[var(--border_color_light)] px-2.5 py-1.5 text-sm transition-all duration-200 ease-in-out hover:border-[var(--primary_light)] dark:border-[var(--border_color_dark)] dark:hover:border-[var(--primary_dark)]"
                    @click="handleValueSelected(Number(index), value.variant_ids, value.color_code ?? value.attribute_value_value)"
                    :class="
                        valuesSelected.includes(value.color_code ?? value.attribute_value_value)
                            ? 'border-[var(--primary_light)] dark:border-[var(--primary_dark)]'
                            : ''
                    "
                >
                    <template v-if="value.color_code">
                        <div class="web-border-color size-4 rounded-full border" :style="{ backgroundColor: value.color_code }"></div>
                        <span>{{ page.props.lang === 'ar' ? value.color_name_ar : value.color_name }}</span>
                    </template>
                    <template v-else>
                        {{ page.props.lang === 'ar' ? value.attribute_value_value_ar : value.attribute_value_value }}
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
