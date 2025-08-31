<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import Create from '@/components/preview/create.vue';
import View from '@/components/preview/View.vue';
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
import Edit from '@/components/ui/table/actions/Edit.vue';
import ViewBtn from '@/components/ui/table/actions/View.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { confirmDialog, toast } from '@/lib/sweetAlert';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { CheckCircle, LoaderCircle, PlusCircle } from 'lucide-vue-next';
import { ref, watchEffect } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Websites',
        href: '/dashboard/my-websites',
    },
    {
        title: 'UI',
        href: '/dashboard/my-website/ui',
    },
];

const props = defineProps<{
    websiteTemplates: Record<string, any>;
    website: Record<string, any>;
    templates: Record<string, any>;
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

const templateTemplateColors = ref<Record<string, any>>([]);
const animate = ref(false);

const fetchTemplateTemplateColors = async (templateId: number) => {
    try {
        animate.value = false;

        const response = await axios.post(route('websiteBuilder.getTemplateTemplateColors'), { templateId: templateId });

        if (response.data?.props?.templateTemplateColors) {
            templateTemplateColors.value = response.data.props.templateTemplateColors;
            createForm.template_id = templateId;

            setTimeout(() => {
                animate.value = true;
            }, 10);
        }
    } catch (error: any) {
        console.error(error);
    }
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

const modalType = ref<'view' | 'create' | null>(null);
const previewData = ref<any>(null);

const preview = (action: 'create' | 'view', templateName: string, colors: Record<string, string>) => {
    const path = `preview/${props.website.website_type.type}/${templateName}/pages/home/Home`;
    previewData.value = { path, colors, closeModals, custom_template_color: createForm.is_custom } as any;
    modalType.value = action;
};

const closeModals = () => {
    modalType.value = null;
    previewData.value = null;
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
    <Head title="Website-UI" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="border-muted mx-2 md:mx-4 my-4 flex flex-col gap-4 rounded-md border p-4">
            <!-- Header -->
            <div class="border-muted flex w-full flex-wrap items-center justify-between gap-2 rounded-md border bg-black/1 p-2 dark:bg-white/1">
                <h1 class="text-active rounded-md bg-gradient-to-br from-blue-500/40 via-blue-500/30 to-blue-500/60 px-4 py-2 font-extrabold">
                    {{ props.website.name }}
                </h1>

                <Dialog>
                    <DialogTrigger as-child>
                        <Button>Create</Button>
                    </DialogTrigger>

                    <DialogScrollContent class="sm:max-w-[calc(100%-1rem)] md:max-w-[calc(100%-1rem)] lg:max-w-7xl">
                        <DialogHeader>
                            <DialogTitle>Create Template</DialogTitle>
                            <DialogDescription class="sr-only"> No description provided. </DialogDescription>
                        </DialogHeader>

                        <!--* Templates -->
                        <div class="flex w-full flex-col px-4 py-5">
                            <HeadingSmall title="Website Template*" description="Select the template design for your business." />

                            <div class="custom-scrollbar flex w-full items-center gap-3 overflow-x-auto p-2">
                                <button
                                    type="button"
                                    v-for="template in props.templates"
                                    :key="template.id"
                                    class="border-muted text-body relative flex min-h-26 min-w-40 cursor-pointer items-center justify-center rounded-lg border bg-black/3 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:scale-102 hover:bg-black/4 active:scale-98 dark:bg-white/3 dark:hover:bg-white/4"
                                    :class="
                                        createForm.template_id === template.id
                                            ? 'text-active -translate-y-1 scale-102 bg-black/4 font-bold dark:bg-white/4'
                                            : ''
                                    "
                                    @click="fetchTemplateTemplateColors(template.id)"
                                >
                                    <div
                                        v-if="createForm.template_id === template.id"
                                        class="absolute top-0 left-0 m-5 h-1/2 w-1/2 bg-[var(--success)]/10 blur-xl"
                                    ></div>

                                    <h2 class="text-lg font-bold">{{ template.name }}</h2>

                                    <div
                                        v-if="createForm.template_id === template.id"
                                        class="translate-all absolute end-1 top-1 z-10 flex size-6 items-center justify-center rounded-lg bg-gradient-to-br from-[var(--primary)] to-[var(--destructive)] transition-all duration-300 ease-in-out"
                                        :class="
                                            animate ? 'translate-x-0 scale-100 rotate-0 opacity-100' : 'translate-x-1 scale-75 rotate-90 opacity-0'
                                        "
                                    >
                                        <CheckCircle class="size-4 text-white" />
                                    </div>
                                </button>
                            </div>

                            <div class="ps-2">
                                <InputError :message="createForm.errors?.template_id" v-if="createForm.errors?.template_id" />
                            </div>
                        </div>

                        <!--* Template Colors -->
                        <div
                            v-if="templateTemplateColors.length > 0"
                            class="flex w-full flex-col px-4 pb-5 transition-all duration-300 ease-in-out"
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
                                    :class="createForm.is_custom || Object.keys(createForm.colors).length > 0 ? '' : 'hidden'"
                                >
                                    <div
                                        v-if="createForm.is_custom"
                                        class="absolute inset-0 top-0 left-0 h-full w-full bg-[var(--success)]/10 blur-xl"
                                    ></div>

                                    <div class="z-20 flex items-center justify-between rounded-md p-2">
                                        <span class="text-active text-lg">
                                            {{ createForm.colors.name }}
                                        </span>

                                        <div class="flex items-center gap-1">
                                            <Dialog>
                                                <DialogTrigger
                                                    as-child
                                                    @click="preview('create', templateTemplateColors[0].template.name, createForm.colors)"
                                                >
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
                                                <DialogTrigger
                                                    as-child
                                                    @click="preview('view', templateTemplateColors[0].template.name, createForm.colors)"
                                                >
                                                    <ViewBtn class="size-7 rounded-full" />
                                                </DialogTrigger>
                                                <View v-if="modalType === 'view' && previewData" :data="previewData" @close="closeModals" />
                                            </Dialog>

                                            <button
                                                type="button"
                                                class="bg-content flex cursor-pointer items-center justify-center rounded-md text-xs backdrop-blur-3xl"
                                                @click="selectingItem(0, true)"
                                            >
                                                <div
                                                    v-if="createForm.is_custom"
                                                    class="h-full w-full rounded-md bg-gradient-to-r from-[var(--primary)] to-[var(--destructive)] px-3 py-2 font-bold text-white"
                                                >
                                                    Selected
                                                </div>
                                                <div v-else class="bg-content h-full w-full rounded-md px-3 py-2">Select</div>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="relative flex min-h-[200px] w-full items-center justify-center">
                                        <div class="bg-black-4 absolute inset-0 top-0 left-0 h-full w-full blur-[3px] dark:bg-white/4"></div>
                                        Your Custom Colors
                                    </div>
                                </div>

                                <!-- Default Colors -->
                                <div
                                    v-for="item in templateTemplateColors"
                                    :key="item.id"
                                    class="border-muted relative flex max-h-[280px] max-w-[450px] flex-col rounded-lg border bg-black/3 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:bg-black/4 dark:bg-white/3 dark:hover:bg-white/4"
                                    :class="
                                        createForm.template_color_id === item.template_color.id ? '-translate-y-1 bg-black/4 dark:bg-white/4' : ''
                                    "
                                >
                                    <div
                                        v-if="createForm.template_color_id === item.template_color.id"
                                        class="absolute inset-0 top-0 left-0 bg-[var(--success)]/10 blur-xl"
                                    ></div>

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
                                                    v-if="createForm.template_color_id === item.template_color.id"
                                                    class="h-full w-full rounded-md bg-gradient-to-r from-[var(--primary)] to-[var(--destructive)] px-3 py-2 font-bold text-white"
                                                >
                                                    Selected
                                                </div>
                                                <div v-else class="bg-content h-full w-full rounded-md px-3 py-2">Select</div>
                                            </button>
                                        </div>
                                    </div>

                                    <Carousel :items="item.uiImages" class="h-full w-full" :showArrows="false" />
                                </div>
                            </div>

                            <div class="ps-2">
                                <InputError :message="createForm.errors?.template_color_id" v-if="createForm.errors?.template_color_id" />
                            </div>
                        </div>

                        <DialogFooter class="px-4">
                            <DialogClose as-child>
                                <Button type="button" variant="secondary">Cancel</Button>
                            </DialogClose>

                            <Button type="button" @click="submitTemplate" :disabled="createForm.processing || form.processing">
                                <LoaderCircle v-if="createForm.processing" class="size-4 animate-spin" />
                                Create
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
                            {{ item.is_active ? 'Active' : 'Inactive' }}
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
                        <p class="text-sm font-bold sm:text-xl">Custom Template Colors</p>
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
                <p class="text-body-muted text-sm">You can create just four templates</p>
                <p class="text-sm font-medium text-[var(--primary)]">{{ props.websiteTemplates.length }}/4</p>
            </div>
        </div>
    </DashboardLayout>
</template>
