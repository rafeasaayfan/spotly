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
import { computed, onMounted, ref } from 'vue';

const props = defineProps<{
    form: {
        template_id: string;
        template_color_id: string;
        custom_template_color: boolean;
        colors: Record<string, any>;
        light_logo: File | null;
        dark_logo: File | null;
        template_images: string[];
        errors?: Record<string, string>;
    };
    type: string;
    templates: Record<string, any>;
}>();

const emit = defineEmits<{
    (e: 'update', field: string, value: string | boolean | Record<string, any> | File): void;
}>();

const templateTemplateColors = ref<Record<string, any>>([]);
const animate = ref(false);

const template_id = computed({
    get: () => props.form.template_id,
    set: (val) => {
        emit('update', 'template_id', val);

        fetchTemplateTemplateColors(val);
    },
});

onMounted(() => {
    if (props.form.template_id) {
        fetchTemplateTemplateColors(props.form.template_id, true);
    }
});

const fetchTemplateTemplateColors = async (val: string, fromOnMounted: boolean = false) => {
    try {
        animate.value = false;

        const response = await axios.post(route('websiteBuilder.getTemplateTemplateColors'), { templateId: val });

        if (response.data?.props?.templateTemplateColors) {
            templateTemplateColors.value = response.data.props.templateTemplateColors;

            if(!fromOnMounted) {
                template_color_id.value = '';
            }

            setTimeout(() => {
                animate.value = true;
            }, 10);
        }
    } catch (error: any) {
        console.error(error);
    }
};

const template_color_id = computed({
    get: () => props.form.template_color_id,
    set: (val) => {
        emit('update', 'template_color_id', val);

        if (val) {
            custom_template_color.value = false;
        }
    },
});

const template_images = computed({
    get: () => props.form.template_images,
    set: (val: ['']) => emit('update', 'template_images', val),
});

const custom_template_color = computed({
    get: () => props.form.custom_template_color,
    set: (val) => {
        emit('update', 'custom_template_color', val);

        if (val) {
            template_color_id.value = '';
            template_images.value = [''];
        }
    },
});

const selectingItem = (item: any) => {
    if(template_color_id.value != item.template_color.id) {
        template_color_id.value = item.template_color.id;

        template_images.value = item.uiImages.map((img: any) => img.original_url);
    }
}

const light_logo = computed({
    get: () => props.form.light_logo,
    set: (val: File) => emit('update', 'light_logo', val),
});

const dark_logo = computed({
    get: () => props.form.dark_logo,
    set: (val: File) => emit('update', 'dark_logo', val),
});

const modalType = ref<'view' | 'create' | null>(null);
const previewData = ref<any>(null);

const preview = (action: 'create' | 'view', templateName: string, colors: Record<string, string>) => {
    const path = `preview/${props.type}/${templateName}/pages/home/Home`;
    previewData.value = { path, colors, closeModals, custom_template_color: custom_template_color.value } as any;
    modalType.value = action;
};

const closeModals = () => {
    modalType.value = null;
    previewData.value = null;
};

const updateField = (field: string, value: any) => {
    (props.form as any)[field] = value;

    if (props.form.custom_template_color) {
        template_color_id.value = '';
    }
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
                <File v-model="light_logo" />
                <InputError v-if="props.form.errors?.light_logo" :message="props.form.errors.light_logo" />
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <HeadingSmall title="Website Dark Logo" description="If you don't have logo will make for you a default one." />
            <div class="flex flex-col gap-1 ps-2">
                <File v-model="dark_logo" />
                <InputError v-if="props.form.errors?.dark_logo" :message="props.form.errors.dark_logo" />
            </div>
        </div>

        <!--* UI -->
        <div class="col-span-1 flex flex-col gap-3 md:col-span-2">
            <!--* Templates -->
            <div class="flex w-full flex-col">
                <HeadingSmall title="Website Template*" description="Select the template design for your business." />

                <div class="custom-scrollbar flex w-full items-center gap-3 overflow-x-auto p-2">
                    <button
                        type="button"
                        v-for="template in props.templates"
                        :key="template.id"
                        class="border-muted text-body relative flex min-h-26 min-w-40 cursor-pointer items-center justify-center rounded-lg border bg-black/3 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:scale-102 hover:bg-black/4 active:scale-98 dark:bg-white/3 dark:hover:bg-white/4"
                        :class="template_id === template.id ? 'text-active -translate-y-1 scale-102 bg-black/4 font-bold dark:bg-white/4' : ''"
                        @click="template_id = template.id"
                    >
                        <h2 class="text-lg font-bold">{{ template.name }}</h2>

                        <div
                            v-if="template_id === template.id"
                            class="translate-all absolute end-1 top-1 z-10 flex size-6 items-center justify-center rounded-lg bg-gradient-to-br from-[var(--primary)] to-[var(--destructive)] transition-all duration-300 ease-in-out"
                            :class="animate ? 'translate-x-0 scale-100 rotate-0 opacity-100' : 'translate-x-1 scale-75 rotate-90 opacity-0'"
                        >
                            <CheckCircle class="size-4 text-white" />
                        </div>
                    </button>
                </div>

                <div class="ps-2">
                    <InputError :message="form.errors?.template_id" v-if="form.errors?.template_id" />
                </div>
            </div>

            <!--* Template Colors -->
            <div
                v-if="templateTemplateColors.length > 0"
                class="flex w-full flex-col transition-all duration-300 ease-in-out"
                :class="animate ? 'translate-y-0 scale-100 rotate-0 opacity-100' : 'translate-y-10 scale-75 rotate-10 opacity-0'"
            >
                <HeadingSmall
                    title="Template Colors*"
                    description="Choose your template colors (You can also customize them by editing your selection)."
                />

                <div class="custom-scrollbar flex w-full items-center gap-3 overflow-x-auto p-2">
                    <!-- Customed Colors -->
                    <div
                        class="border-muted relative flex min-h-[280px] min-w-[450px] flex-col rounded-lg border bg-black/3 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:bg-black/4 dark:bg-white/3 dark:hover:bg-white/4"
                        :class="custom_template_color || Object.keys(props.form.colors).length > 0 ? '' : 'hidden'"
                    >
                        <div class="z-20 flex items-center justify-between rounded-md bg-black/1 p-2">
                            <span class="text-active text-lg">
                                {{ props.form.colors.name }}
                            </span>

                            <div class="flex items-center gap-1">
                                <Dialog>
                                    <DialogTrigger as-child @click="preview('create', templateTemplateColors[0].template.name, props.form.colors)">
                                        <Edit class="size-7 rounded-full" />
                                    </DialogTrigger>
                                    <Create
                                        v-if="modalType === 'create' && previewData"
                                        :data="previewData"
                                        @close="closeModals"
                                        @update="updateField"
                                    />
                                </Dialog>

                                <Dialog>
                                    <DialogTrigger as-child @click="preview('view', templateTemplateColors[0].template.name, props.form.colors)">
                                        <ViewBtn class="size-7 rounded-full" />
                                    </DialogTrigger>
                                    <View v-if="modalType === 'view' && previewData" :data="previewData" @close="closeModals" />
                                </Dialog>

                                <button
                                    type="button"
                                    class="bg-content flex cursor-pointer items-center justify-center rounded-md text-xs backdrop-blur-3xl"
                                    @click="custom_template_color = true"
                                >
                                    <div
                                        v-if="custom_template_color"
                                        class="h-full w-full rounded-md bg-gradient-to-r from-[var(--primary)] to-[var(--destructive)] px-3 py-2 font-bold text-white"
                                    >
                                        Selected
                                    </div>
                                    <div v-else class="bg-content h-full w-full rounded-md px-3 py-2">Select</div>
                                </button>
                            </div>
                        </div>

                        <div class="flex h-full w-full items-center justify-center">Your Custom Colors</div>
                    </div>

                    <!-- Default Colors -->
                    <div
                        v-for="item in templateTemplateColors"
                        :key="item.id"
                        class="border-muted relative flex min-h-[280px] min-w-[450px] flex-col rounded-lg border bg-black/3 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:bg-black/4 dark:bg-white/3 dark:hover:bg-white/4"
                        :class="template_color_id === item.template_color.id ? '-translate-y-1 bg-black/4 dark:bg-white/4' : ''"
                    >
                        <div class="z-20 flex items-center justify-between rounded-md bg-black/1 p-2">
                            <span class="text-active text-lg">
                                {{ item.template_color.name }}
                            </span>

                            <div class="flex items-center gap-1">
                                <Dialog>
                                    <DialogTrigger as-child @click="preview('create', item.template.name, item.template_color)">
                                        <Edit class="size-7 rounded-full" />
                                    </DialogTrigger>
                                    <Create
                                        v-if="modalType === 'create' && previewData"
                                        :data="previewData"
                                        @close="closeModals"
                                        @update="updateField"
                                    />
                                </Dialog>

                                <Dialog>
                                    <DialogTrigger as-child @click="preview('view', item.template.name, item.template_color)">
                                        <ViewBtn class="size-7 rounded-full" />
                                    </DialogTrigger>
                                    <View v-if="modalType === 'view' && previewData" :data="previewData" @close="closeModals" />
                                </Dialog>

                                <button
                                    type="button"
                                    class="bg-content flex cursor-pointer items-center justify-center rounded-md text-xs backdrop-blur-3xl"
                                    @click="selectingItem(item)"
                                >
                                    <div
                                        v-if="template_color_id === item.template_color.id"
                                        class="h-full w-full rounded-md bg-gradient-to-r from-[var(--primary)] to-[var(--destructive)] px-3 py-2 font-bold text-white"
                                    >
                                        Selected
                                    </div>
                                    <div v-else class="bg-content h-full w-full rounded-md px-3 py-2">Select</div>
                                </button>
                            </div>
                        </div>

                        <Carousel :items="item.uiImages" height="250px" width="450px" :showArrows="false" />
                    </div>
                </div>

                <div class="ps-2">
                    <InputError :message="form.errors?.template_color_id" v-if="form.errors?.template_color_id" />
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
