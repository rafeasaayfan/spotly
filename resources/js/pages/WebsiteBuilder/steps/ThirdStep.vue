<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import Create from '@/components/preview/create.vue';
import View from '@/components/preview/View.vue';
import { Carousel } from '@/components/ui/carousel';
import { Dialog, DialogTrigger } from '@/components/ui/dialog';
import { File, InputError } from '@/components/ui/fields';
import Edit from '@/components/ui/table/actions/Edit.vue';
import ViewBtn from '@/components/ui/table/actions/View.vue';
import axios from 'axios';
import { CheckCircle, LayoutTemplate } from 'lucide-vue-next';
import { computed, defineProps, ref } from 'vue';

const props = defineProps<{
    form: {
        template_id: string;
        template_color_id: string;
        errors?: Record<string, string>;
    };
    type: string;
    templates: Record<string, any>;
    // templateTemplateColors: Record<string, any>;
}>();

const emit = defineEmits<{
    (e: 'update', field: string, value: string): void;
}>();

const templateTemplateColors = ref<Record<string, any>>({});
const animate = ref(false);

const template_id = computed({
    get: () => props.form.template_id,
    set: async (val) => {
        emit('update', 'template_id', val);

        try {
            animate.value = false;

            const response = await axios.post(route('websiteBuilder.getTemplateTemplateColors'), { templateId: val });

            if (response.data?.templateTemplateColors) {
                templateTemplateColors.value = response.data?.templateTemplateColors;

                setTimeout(() => {
                    animate.value = true;
                }, 100)
            }
        } catch (error: any) {
            console.error(error);
        }
    },
});

// const template_color_id = computed({
//     set: (val) => emit('update', 'template_color_id', val),
// });

const modalType = ref<'view' | 'create' | null>(null);
const previewData = ref<any>(null);

const preview = (action: 'create' | 'view', templateName: string, colors: Record<string, string>) => {
    const path = `preview/${props.type}/${templateName}/pages/home/Home`;
    previewData.value = { path, colors, closeModals } as any;
    modalType.value = action;
};

const closeModals = () => {
    modalType.value = null;
    previewData.value = null;
};
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
        <div class="col-span-1 flex flex-col gap-3 md:col-span-2">
            <!-- Templates -->
            <div class="flex w-full flex-col">
                <HeadingSmall title="Website Template" description="Select the template design for your business." />

                <div class="custom-scrollbar flex w-full items-center gap-3 overflow-x-auto p-2">
                    <div
                        v-for="template in props.templates"
                        :key="template.id"
                        class="border-muted text-body relative flex min-h-26 min-w-40 cursor-pointer items-center justify-center rounded-lg border 
                        bg-black/3 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:scale-102 hover:bg-black/4 active:scale-98 dark:bg-white/3 
                        dark:hover:bg-white/4"
                        :class="template_id === template.id ? '-translate-y-1 scale-102 bg-black/4 dark:bg-white/4' : ''"
                        @click="template_id = template.id"
                    >
                        <h2 class="text-lg font-bold">{{ template.name }}</h2>

                        <div v-if="template_id === template.id"
                            class="absolute end-1 top-1 z-10 flex size-6 items-center justify-center rounded-lg bg-gradient-to-br 
                            from-[var(--primary)] to-[var(--destructive)] transition-all duration-300"
                        >
                            <CheckCircle class="size-4 text-white" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template Colors -->
            <div v-if="templateTemplateColors.length > 0" class="flex w-full flex-col transition-all duration-300 ease-in-out"
                :class="animate ? 'scale-100 translate-y-0 opacity-100' : 'scale-75 translate-y-10 opacity-0'">
                <HeadingSmall
                    title="Template Colors"
                    description="Choose your template colors (You can also customize them by editing your selection)."
                />

                <div class="custom-scrollbar flex w-full items-center gap-3 overflow-x-auto p-2">
                    <div
                        v-for="item in templateTemplateColors"
                        :key="item.id"
                        class="border-muted relative flex flex-col rounded-lg border bg-black/3 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:bg-black/4 dark:bg-white/3 dark:hover:bg-white/4"
                    >
                        <div class="z-20 flex items-center justify-between rounded-md bg-black/1 p-2">
                            <span class="text-active text-lg">
                                {{ item.template_color.name }}
                            </span>

                            <div class="flex items-center gap-1">
                                <Dialog>
                                    <DialogTrigger as-child>
                                        <div @click="preview('create', item.template.name, item.template_color)">
                                            <Edit class="size-7 rounded-full" />
                                        </div>
                                    </DialogTrigger>
                                    <Create v-if="modalType === 'create' && previewData" :data="previewData" @close="closeModals" />
                                </Dialog>

                                <Dialog>
                                    <DialogTrigger as-child>
                                        <div @click="preview('view', item.template.name, item.template_color)">
                                            <ViewBtn class="size-7 rounded-full" />
                                        </div>
                                    </DialogTrigger>
                                    <View v-if="modalType === 'view' && previewData" :data="previewData" @close="closeModals" />
                                </Dialog>

                                <div
                                    class="bg-content flex cursor-pointer items-center justify-center rounded-md px-3 py-2 text-xs backdrop-blur-3xl"
                                >
                                    Select
                                </div>
                            </div>
                        </div>

                        <Carousel :items="item.images" height="250px" width="450px" :showArrows="false" />
                    </div>
                </div>
            </div>

            <div :class="animate ? '' : 'min-h-32 animate-pulse bg-black/20'">
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
