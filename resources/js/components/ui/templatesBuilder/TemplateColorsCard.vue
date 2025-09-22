<script setup lang="ts">
import { Ref, ref } from 'vue'
import Create from '@/components/preview/create.vue';
import View from '@/components/preview/View.vue';
import { Carousel } from '@/components/ui/carousel';
import { Dialog, DialogTrigger } from '@/components/ui/dialog';
import Edit from '@/components/ui/table/actions/Edit.vue';
import ViewBtn from '@/components/ui/table/actions/View.vue';

interface Props {
    item?: Record<string, any>
    selectedTemplateColorId?: string | number
    type: string
    updateField: (field: string, value: any) => any
    custom_template_color: boolean
    selectingItem: (item: any, is_custom: boolean) => any

    isDefault: boolean

    colors?: Record<string, any>
    templateTemplateColors?: Record<string, any>
}

const props = defineProps<Props>();

const modalType = ref<'view' | 'create' | null>(null);
const previewData = ref<any>(null);

const closeModals = () => {
    modalType.value = null;
    previewData.value = null;
};

const preview = (action: 'create' | 'view', templateName: string, colors: Record<string, string>) => {
    const path = `preview/${props.type}/${templateName}/pages/home/Home`;
    previewData.value = { path, colors, closeModals, custom_template_color: props.custom_template_color } as any;
    modalType.value = action;
};
</script>

<template>
    <div v-if="props.isDefault && props.item !== undefined && props.selectedTemplateColorId !== undefined"
        class="backdrop-blur border-muted relative flex min-h-[280px] min-w-[450px] max-h-[280px] max-w-[450px] flex-col rounded-lg border bg-black/3 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:bg-black/4 dark:bg-white/3 dark:hover:bg-white/4"
        :class="props.selectedTemplateColorId === props.item.template_color.id ? '-translate-y-1 bg-black/6 dark:bg-white/6' : ''
            ">
        <div v-if="props.selectedTemplateColorId === props.item.template_color.id"
            class="absolute inset-0 top-0 left-0 bg-[var(--primary)]/10 blur-xl"></div>

        <div class="z-20 flex items-center justify-between rounded-md bg-black/1 p-2">
            <span class="text-active text-lg">
                {{ props.item.template_color.name }}
            </span>

            <div class="flex items-center gap-1">
                <Dialog>
                    <DialogTrigger as-child
                        @click="preview('create', props.item.template.name, props.item.template_color)">
                        <Edit class="size-7 rounded-full" />
                    </DialogTrigger>
                    <Create v-if="modalType === 'create' && previewData" :data="previewData" @close="closeModals"
                        @update="updateField" />
                </Dialog>

                <Dialog>
                    <DialogTrigger as-child
                        @click="preview('view', props.item.template.name, props.item.template_color)">
                        <ViewBtn class="size-7 rounded-full" />
                    </DialogTrigger>
                    <View v-if="modalType === 'view' && previewData" :data="previewData" @close="closeModals" />
                </Dialog>

                <button type="button"
                    class="bg-content flex cursor-pointer items-center justify-center rounded-md text-xs backdrop-blur-3xl"
                    @click="selectingItem(item, false)">
                    <div v-if="selectedTemplateColorId === props.item.template_color.id"
                        class="h-full w-full rounded-md bg-gradient-to-r from-[var(--primary)] via-[var(--primary-hover)] to-[var(--primary-active)] px-3 py-2 font-bold text-white">
                        {{ $t('template.btn.selected') }}
                    </div>
                    <div v-else class="bg-content h-full w-full rounded-md px-3 py-2">{{ $t('template.btn.select') }}</div>
                </button>
            </div>
        </div>

        <Carousel :items="props.item.uiImages" class="h-full w-full" :showArrows="false" />
    </div>

    <div v-else-if="!props.isDefault && props.colors !== undefined && props.templateTemplateColors !== undefined"
        class="backdrop-blur border-muted relative flex min-h-[280px] min-w-[450px] max-h-[280px] max-w-[450px] flex-col rounded-lg border bg-black/3 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:bg-black/4 dark:bg-white/3 dark:hover:bg-white/4"
        :class="props.custom_template_color || Object.keys(props.colors).length > 0 ? '' : 'hidden'">
        <div v-if="props.custom_template_color"
            class="absolute inset-0 top-0 left-0 h-full w-full bg-[var(--primary)]/10 blur-xl"></div>

        <div class="z-20 flex items-center justify-between rounded-md p-2">
            <span class="text-active text-lg">
                {{ props.colors.name }}
            </span>

            <div class="flex items-center gap-1">
                <Dialog>
                    <DialogTrigger as-child
                        @click="preview('create', (templateTemplateColors && templateTemplateColors[0]?.template?.name) || '', props.colors)">
                        <Edit class="size-7 rounded-full" />
                    </DialogTrigger>
                    <Create v-if="modalType === 'create' && previewData" :data="previewData" @close="closeModals"
                        @update="updateField" />
                </Dialog>

                <Dialog>
                    <DialogTrigger as-child
                        @click="preview('view', (templateTemplateColors && templateTemplateColors[0]?.template?.name) || '', props.colors)">
                        <ViewBtn class="size-7 rounded-full" />
                    </DialogTrigger>
                    <View v-if="modalType === 'view' && previewData" :data="previewData" @close="closeModals" />
                </Dialog>

                <button type="button"
                    class="bg-content flex cursor-pointer items-center justify-center rounded-md text-xs backdrop-blur-3xl"
                    @click="selectingItem(0, true)">
                    <div v-if="props.custom_template_color"
                        class="h-full w-full rounded-md bg-gradient-to-r from-[var(--primary)] via-[var(--primary-hover)] to-[var(--primary-active)] px-3 py-2 font-bold text-white">
                        {{ $t('template.btn.selected') }}
                    </div>
                    <div v-else class="bg-content h-full w-full rounded-md px-3 py-2">{{ $t('template.btn.select') }}</div>
                </button>
            </div>
        </div>

        <div class="relative flex min-h-[200px] w-full items-center justify-center">
            <div class="bg-black-4 absolute inset-0 top-0 left-0 h-full w-full blur-[3px] dark:bg-white/4"></div>
            {{ $t('template.btn.custom') }}
        </div>
    </div>
</template>