<script setup lang="ts">
// import HeadingSmall from '@/components/headers/HeadingSmall.vue';
// import Heading from '@/components/headers/Heading.vue';
import { File, InputError } from '@/components/ui/fields';
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { Carousel } from '@/components/ui/carousel';
import { Eye, LayoutTemplate } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    form: {
        template_id: string;
        template_color_id: string;
        errors?: Record<string, string>;
    };
    templates: Record<string, any>;
    templateTemplateColors: Record<string, any>;
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
    <div key="step3" class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2 md:gap-y-10">
        <div class="border-muted col-span-1 w-full border-b pb-3 md:col-span-3">
            <h1 class="flex items-center gap-2 text-xl font-bold sm:text-2xl">
                <LayoutTemplate class="text-active-link size-5 sm:size-6" />
                <span class="gradient-text">Templates UI</span>
            </h1>
        </div>
        <!-- Logo -->
        <div class="flex flex-col gap-2">
            <HeadingSmall title="Website Light Logo" description="If you don't have logo will make for you a default one." />
            <div class="flex flex-col gap-1 ps-2">
                <File />
                <InputError v-if="props.form.errors?.logoLight" :message="props.form.errors.logoLight" />
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <HeadingSmall title="Website Dark Logo" description="If you don't have logo will make for you a default one." />
            <div class="flex flex-col gap-1 ps-2">
                <File />
                <InputError v-if="props.form.errors?.darkLogo" :message="props.form.errors.darkLogo" />
            </div>
        </div>

        <!-- UI -->
        <div class="col-span-1 flex flex-col gap-1 md:col-span-2">
            <!-- Templates -->
            <div class="flex w-full flex-col">
                <HeadingSmall title="Website Template" description="Select the template design for your business." />

                <div class="custom-scrollbar flex w-full items-center gap-3 overflow-x-auto p-2">
                    <div
                        v-for="template in props.templates"
                        :key="template.id"
                        class="border-muted flex min-h-26 min-w-40 cursor-pointer items-center justify-center rounded-lg 
                        border bg-black/3 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:scale-102 hover:bg-black/4 
                        active:scale-98 dark:bg-white/3 dark:hover:bg-white/4 text-body"
                    >
                        <h2 class="text-lg font-bold">{{ template.name }}</h2>
                    </div>
                </div>
            </div>

            <!-- Template Colors -->
            <div class="flex w-full flex-col">
                <HeadingSmall title="Template Colors" description="Choose your template colors." />

                <div class="custom-scrollbar flex w-full items-center gap-3 overflow-x-auto p-2">
                    <div
                        v-for="template in props.templateTemplateColors"
                        :key="template.id"
                        class="border-muted relative flex flex-col rounded-lg border bg-black/3 transition-all 
                        duration-200 ease-in-out hover:-translate-y-1 hover:bg-black/4 dark:bg-white/3 dark:hover:bg-white/4"
                    >
                        <div class="z-20 flex items-center justify-between rounded-md bg-black/1 p-2">
                            <span class="text-active text-lg">
                                {{ template.template_color.name }}
                            </span>

                            <div class="flex items-center gap-1">
                                <div class="flex size-8 items-center justify-center rounded-full bg-green-800 backdrop-blur-xl hover:bg-green-700
                                    cursor-pointer">
                                    <Eye class="size-4" />
                                </div>

                                <div class="bg-content flex items-center justify-center rounded-md px-3 py-2 text-xs backdrop-blur-3xl
                                cursor-pointer">
                                    Select
                                </div>
                            </div>
                        </div>

                        <Carousel :items="template.images" height="250px" width="450px" :showArrows="false" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 2px;
    height: 2px;
    scrollbar-width: thin;
}
</style>
