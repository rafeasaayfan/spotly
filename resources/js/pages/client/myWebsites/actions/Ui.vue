<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Carousel } from '@/components/ui/carousel';
import {
    Dialog,
    DialogClose,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogScrollContent,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { InputError, Toggle } from '@/components/ui/fields';
import Delete from '@/components/ui/table/actions/Delete.vue';
import { TemplateBtn, TemplateColorsCard } from '@/components/ui/templatesBuilder';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { confirmDialog, toast } from '@/lib/sweetAlert';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle, PlusCircle } from 'lucide-vue-next';
import { ref, watchEffect } from 'vue';

const page = usePage<SharedData>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: page.props.lang === 'ar' ? 'مواقعي' : 'My Websites',
        href: '/dashboard/my-websites',
    },
    {
        title: page.props.lang === 'ar' ? 'واجهة المستخدم' : 'UI',
        href: '/dashboard/my-website/ui',
    },
];

const props = defineProps<{
    websiteTemplates: Record<string, any>;
    website: Record<string, any>;
    templateTemplateColors: Record<string, any>;
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info';
        message: string;
        success: boolean;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});

const form = useForm<{
    is_active: boolean;
    websiteTemplateId: number;
}>({
    is_active: false,
    websiteTemplateId: 0,
});

const activate = (is_active: boolean, websiteTemplateId: number) => {
    form.websiteTemplateId = websiteTemplateId;
    form.is_active = is_active;

    form.patch(route('client.myWebsite.ui.toggleActive', props.website.id));
};

const handleDialogDelete = async (websiteTemplateId: number) => {
    const result = await confirmDialog({ text: 'Delete template?' });
    if (!result.isConfirmed) return;

    form.websiteTemplateId = websiteTemplateId;

    form.delete(route('client.myWebsite.ui.destroy', props.website.id));
};

const createForm = useForm<{
    template_id: number;
    template_color_id: number;
    is_custom: boolean;
    template_images: any[];
    colors: Record<string, any>;
}>({
    template_id: 0,
    template_color_id: 0,
    is_custom: false,
    template_images: [],
    colors: [],
});

const selectedtemplateTemplateColors = ref<Record<string, any>>([]);
const animate = ref(false);

const filterTemplateTemplateColors = async (templateId: number) => {
    animate.value = false;

    selectedtemplateTemplateColors.value = [];
    selectedtemplateTemplateColors.value = props.templateTemplateColors.filter((item: any) => item.template_id == templateId);
    createForm.template_id = templateId;

    setTimeout(() => {
        animate.value = true;
    }, 10);
};

const selectingItem = (item: any, is_custom: boolean = false) => {
    if (!is_custom && createForm.template_color_id != item.template_color.id) {
        createForm.template_color_id = item.template_color.id;

        createForm.template_images = item.uiImages.map((img: any) => img.original_url);

        createForm.is_custom = false;
    } else {
        createForm.template_color_id = 0;
        createForm.is_custom = is_custom;
        createForm.template_images = [];
    }
};

const updateField = (field: string, value: any) => {
    (createForm as any)[field] = value;

    if (createForm.is_custom) {
        createForm.template_color_id = 0;
    }
};

const submitTemplate = () => {
    createForm.post(route('client.myWebsite.ui.create', props.website.id), {
        onSuccess: () => {
            if (props.flash?.success) {
                const closeButton = document.querySelector('[data-slot="dialog-close"]');
                (closeButton as HTMLElement)?.click();
            }
        },
    });
};
</script>

<template>
    <Head :title="$t('myWebsites.ui')" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="border-muted mx-2 my-4 flex flex-col gap-4 rounded-md border p-4 md:mx-4">
            <!-- Header -->
            <div class="border-muted flex w-full flex-wrap items-center justify-between gap-2 rounded-md border bg-black/1 p-2 dark:bg-white/1">
                <h1 class="text-active rounded-md bg-gradient-to-br from-blue-500/40 via-blue-500/30 to-blue-500/60 px-4 py-2 font-extrabold">
                    {{ props.website.name }}
                </h1>

                <Dialog>
                    <DialogTrigger as-child>
                        <Button>{{ $t('create') }}</Button>
                    </DialogTrigger>

                    <DialogScrollContent class="sm:max-w-[calc(100%-1rem)] md:max-w-[calc(100%-1rem)] lg:max-w-7xl">
                        <DialogHeader>
                            <DialogTitle>{{ $t('myWebsites.create_template') }}</DialogTitle>
                            <DialogDescription class="sr-only"> No description provided. </DialogDescription>
                        </DialogHeader>

                        <!--* Templates -->
                        <div class="flex w-full flex-col px-4 py-5">
                            <HeadingSmall
                                :title="$t('websiteBuilder.thirdStep.website_template_title')"
                                :description="$t('websiteBuilder.thirdStep.website_template_description')"
                            />

                            <div class="custom-scrollbar flex w-full items-center gap-3 overflow-x-auto p-2">
                                <TemplateBtn
                                    v-for="templateTemplateColor in props.templateTemplateColors"
                                    :key="templateTemplateColor.id"
                                    :templateId="templateTemplateColor.template.id"
                                    @click="filterTemplateTemplateColors(templateTemplateColor.template.id)"
                                    :animate="animate"
                                    :selectedTemplateId="createForm.template_id"
                                    :templateName="templateTemplateColor.template.name"
                                />
                            </div>

                            <div class="ps-2">
                                <InputError :message="createForm.errors?.template_id" v-if="createForm.errors?.template_id" />
                            </div>
                        </div>

                        <!--* Template Colors -->
                        <div
                            v-if="selectedtemplateTemplateColors.length > 0"
                            class="flex w-full flex-col px-4 pb-5 transition-all duration-300 ease-in-out"
                            :class="animate ? 'translate-y-0 scale-100 rotate-0 opacity-100' : 'translate-y-10 scale-75 rotate-10 opacity-0'"
                        >
                            <HeadingSmall
                                :title="$t('websiteBuilder.thirdStep.template_colors_title')"
                                :description="$t('websiteBuilder.thirdStep.template_colors_description')"
                            />

                            <div class="custom-scrollbar flex w-full items-center gap-3 overflow-x-auto p-2">
                                <!-- Customed Colors -->
                                <TemplateColorsCard
                                    :isDefault="false"
                                    :colors="createForm.colors"
                                    :templateTemplateColors="templateTemplateColors"
                                    :updateField="updateField"
                                    :type="props.website.website_type.type"
                                    :custom_template_color="createForm.is_custom"
                                    :selectingItem="selectingItem"
                                />

                                <!-- Default Colors -->
                                <TemplateColorsCard
                                    :isDefault="true"
                                    v-for="item in selectedtemplateTemplateColors"
                                    :key="item.id"
                                    :item="item"
                                    :selectedTemplateColorId="createForm.template_color_id"
                                    :type="props.website.website_type.type"
                                    :updateField="updateField"
                                    :custom_template_color="createForm.is_custom"
                                    :selectingItem="selectingItem"
                                />
                            </div>

                            <div class="ps-2">
                                <InputError :message="createForm.errors?.template_color_id" v-if="createForm.errors?.template_color_id" />
                            </div>
                        </div>

                        <DialogFooter class="px-4">
                            <DialogClose as-child>
                                <Button type="button" variant="secondary">{{ $t('myWebsites.cancel') }}</Button>
                            </DialogClose>

                            <Button type="button" @click="submitTemplate" :disabled="createForm.processing || form.processing">
                                <LoaderCircle v-if="createForm.processing" class="size-4 animate-spin" />
                                {{ $t('create') }}
                            </Button>
                        </DialogFooter>
                    </DialogScrollContent>
                </Dialog>
            </div>

            <!-- Website Templates -->
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                <div v-for="item in props.websiteTemplates" :key="item.id" class="bg-field border-muted flex flex-col gap-3 rounded-md border p-3">
                    <div class="flex w-full items-center justify-between gap-2">
                        <span
                            class="rounded-md px-3 py-1 font-medium"
                            :class="
                                item.is_active
                                    ? 'bg-[var(--success)]/20 text-[var(--success)]'
                                    : 'bg-[var(--destructive)]/20 text-[var(--destructive)]'
                            "
                        >
                            {{ item.is_active ? $t('active') : $t('inactive') }}
                        </span>

                        <div class="">
                            <Toggle
                                btnClass="w-12 h-6"
                                circleClass="size-4"
                                :modelValue="item.is_active === 1"
                                :class="form.processing ? 'pointer-events-none opacity-50' : ''"
                                @update:modelValue="(val) => activate(val, item.id)"
                            />
                        </div>
                    </div>
                    <div v-if="item.is_custom" class="relative flex h-60 w-full items-center justify-center sm:h-80 md:h-90 lg:h-80">
                        <div class="absolute top-0 left-0 h-full w-full bg-black/4 blur-[3px] dark:bg-white/4"></div>
                        <p class="text-sm font-bold sm:text-xl">{{ $t('myWebsites.custom_template_colors') }}</p>
                    </div>
                    <template v-else-if="!item.is_custom">
                        <Carousel :items="item.template_images" parentClass="w-full h-60 sm:h-80 md:h-90 lg:h-80" :showArrows="false" />
                    </template>

                    <div class="flex items-center justify-between gap-3">
                        <p class="text-active flex items-center gap-1 font-medium">
                            <span class="text-active bg-card border-muted rounded-md border px-3 py-1.5 text-sm">
                                {{ item.template.name }}
                            </span>
                            <PlusCircle class="size-3.5" />
                            <span class="text-active bg-card border-muted rounded-md border px-3 py-1.5 text-sm">
                                {{ item.template_color.name }}
                            </span>
                        </p>

                        <Delete @click="handleDialogDelete(item.id)" :class="form.processing ? 'pointer-events-none opacity-50' : ''" />
                    </div>
                </div>
            </div>

            <div class="border-muted flex w-full flex-wrap items-center justify-between gap-2 rounded-md border bg-black/1 p-2 dark:bg-white/1">
                <p class="text-body-muted text-sm">{{ $t('myWebsites.you_can_create_just_four_templates') }}</p>
                <p class="text-sm font-medium text-[var(--primary)]">{{ props.websiteTemplates.length }}/4</p>
            </div>
        </div>
    </DashboardLayout>
</template>
