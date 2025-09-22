<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { File, InputError } from '@/components/ui/fields';
import { TemplateBtn, TemplateColorsCard } from '@/components/ui/templatesBuilder';
import axios from 'axios';
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

            if (!fromOnMounted) {
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

const selectingItem = (item: any, is_custom: boolean = false) => {
    if (!is_custom) {
        if (template_color_id.value != item.template_color.id) {
            template_color_id.value = item.template_color.id;

            template_images.value = item.uiImages.map((img: any) => img.original_url);
        }
    } else {
        custom_template_color.value = true
    }
};

const light_logo = computed({
    get: () => props.form.light_logo,
    set: (val: File) => emit('update', 'light_logo', val),
});

const dark_logo = computed({
    get: () => props.form.dark_logo,
    set: (val: File) => emit('update', 'dark_logo', val),
});

const updateField = (field: string, value: any) => {
    (props.form as any)[field] = value;

    if (props.form.custom_template_color) {
        template_color_id.value = '';
    }
};
</script>

<template>
    <!-- Logo -->
    <div class="grid grid-cols-1 gap-4 md:col-span-3 lg:grid-cols-2">
        <div class="flex flex-col gap-2">
            <HeadingSmall
                :title="$t('websiteBuilder.thirdStep.light_logo_title')"
                titleClass="text-body"
                :description="$t('websiteBuilder.thirdStep.light_logo_description')"
            />
            <div class="flex flex-col gap-1 ps-2">
                <File
                    v-model="light_logo"
                    fileClass="h-10"
                    fileInputClass="file:h-10 file:bg-black/8 hover:file:bg-black/10
                dark:file:bg-white/8 dark:hover:file:bg-white/10"
                />
                <InputError v-if="props.form.errors?.light_logo" :message="props.form.errors.light_logo" />
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <HeadingSmall
                :title="$t('websiteBuilder.thirdStep.dark_logo_title')"
                titleClass="text-body"
                :description="$t('websiteBuilder.thirdStep.dark_logo_description')"
            />
            <div class="flex flex-col gap-1 ps-2">
                <File
                    v-model="dark_logo"
                    fileClass="h-10"
                    fileInputClass="file:h-10 file:bg-black/8 hover:file:bg-black/10
                dark:file:bg-white/8 dark:hover:file:bg-white/10"
                />
                <InputError v-if="props.form.errors?.dark_logo" :message="props.form.errors.dark_logo" />
            </div>
        </div>
    </div>

    <!--* UI -->
    <div class="col-span-1 flex w-full flex-col gap-3 md:col-span-3">
        <!--* Templates -->
        <div class="flex w-full flex-col">
            <HeadingSmall
                :title="$t('websiteBuilder.thirdStep.website_template_title')"
                titleClass="text-body"
                :description="$t('websiteBuilder.thirdStep.website_template_description')"
            />

            <div class="custom-scrollbar flex w-full items-center gap-3 overflow-x-auto p-2">
                <TemplateBtn
                    v-for="template in props.templates"
                    :key="template.id"
                    :templateId="template.id"
                    :selectedTemplateId="template_id"
                    @click="template_id = template.id"
                    :templateName="template.name"
                    :animate="animate"
                />
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
                :title="$t('websiteBuilder.thirdStep.template_colors_title')"
                titleClass="text-body"
                :description="$t('websiteBuilder.thirdStep.template_colors_description')"
            />

            <div class="custom-scrollbar flex w-full items-center gap-3 overflow-x-auto p-2">
                <!-- Customed Colors -->
                <TemplateColorsCard
                    :isDefault="false"
                    :colors="props.form.colors"
                    :templateTemplateColors="templateTemplateColors"
                    :type="props.type"
                    :updateField="updateField"
                    :custom_template_color="custom_template_color"
                    :selectingItem="selectingItem"
                />

                <!-- Default Colors -->
                <TemplateColorsCard
                    :isDefault="true"
                    v-for="item in templateTemplateColors"
                    :key="item.id"
                    :item="item"
                    :selectedTemplateColorId="template_color_id"
                    :type="props.type"
                    :updateField="updateField"
                    :custom_template_color="custom_template_color"
                    :selectingItem="selectingItem"
                />
            </div>

            <div class="ps-2">
                <InputError :message="form.errors?.template_color_id" v-if="form.errors?.template_color_id" />
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
