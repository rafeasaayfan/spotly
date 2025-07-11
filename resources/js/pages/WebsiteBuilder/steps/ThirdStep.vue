<script setup lang="ts">
// import HeadingSmall from '@/components/headers/HeadingSmall.vue';
// import Heading from '@/components/headers/Heading.vue';
// import { Input, InputError } from '@/components/ui/fields';
import { computed } from 'vue';

const props = defineProps<{
    form: {
        template_id: string;
        template_color_id: string;
        errors?: Record<string, string>;
    };
    templates: Record<string, any>;
}>();

const emit = defineEmits<{
    (e: 'update', field: string, value: string): void;
}>();

const template_id = computed({
    get: () => props.form.template_id,
    set: (val) => emit('update', 'template_id', val),
});

const template_color_id = computed({

    set: (val) => emit('update', 'template_color_id', val),
});
</script>

<template>
    <div key="step3" class="grid grid-cols-1 gap-x-6 gap-y-8 md:gap-y-10 md:grid-cols-3">
        <div class="border-muted col-span-1 w-full border-b pb-3 md:col-span-3">
            <h1 class="flex items-center gap-2 text-xl font-bold sm:text-2xl">
                Select Template
            </h1>
        </div>

        <div class="col-span-1">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div v-for="template in templates" :key="template.id" class="border-muted rounded-lg border p-4">
                    <h2 class="text-lg font-bold">{{ template.name }}</h2>
                </div>
            </div>
        </div>
    </div>
</template>
