<script setup lang="ts">
import { DialogDescription, DialogHeader, DialogScrollContent, DialogTitle } from '@/components/ui/dialog';
import { toast } from '@/lib/sweetAlert';
import axios from 'axios';
import { Menu } from 'lucide-vue-next';
import { computed, ref, defineAsyncComponent, DefineComponent, watch } from 'vue';
import HeadingSmall from '../headers/HeadingSmall.vue';
import { Button } from '../ui/button';
import { Color, Input, InputError, Textarea } from '../ui/fields';

const props = defineProps<{
    data: {
        colors: Record<string, any>;
        path: string;
        closeModals: () => void;
        custom_template_color: boolean;
    };
}>();

// Get the component path
const components = import.meta.glob('@/pages/preview/**/**/pages/home/Home.vue');
const refPath = ref(props.data.path);
watch(() => props.data.path, (newPath) => {
  refPath.value = newPath;
}, { immediate: true });
const matchingPath = computed(() => {
  return Object.keys(components).find(path => path.includes(refPath.value));
});
const actionCompo = computed(() => {
  const path = matchingPath.value;
  if (!path) throw new Error(`Component not found for path: ${refPath.value}`);
  return defineAsyncComponent(components[path] as () => Promise<DefineComponent>);
});

const emit = defineEmits<{
    (e: 'update', field: string, value: any | boolean): void;
    (e: 'close'): void;
}>();

const custom_template_color = computed({
    get: () => props.data.custom_template_color,
    set: (val) => {
        emit('update', 'custom_template_color', val);
    },
});

const colors = computed({
    get: () => props.data.colors,
    set: (val) => {
        emit('update', 'colors', val);

        custom_template_color.value = true;
    },
});

const colorKeywords = ['color', 'bg', 'primary', 'secondary', 'danger', 'foreground'];
const colorKeys = Object.keys(props.data.colors).filter(
    (key) =>
        typeof props.data.colors[key] === 'string' &&
        colorKeywords.some(keyword => key.includes(keyword))
);

// Local copy for editing
const formColors = ref<Record<string, any>>({ ...props.data.colors });
watch(() => props.data.colors, (newColors) => {
    formColors.value = { ...newColors };
}, { deep: true, immediate: true });

// Validation state
const colorErrors = ref<Record<string, string>>({});
const nameError = ref('');
const descriptionError = ref('');

const validateColors = () => {
    let valid = true;
    colorErrors.value = {};
    nameError.value = '';
    descriptionError.value = '';
    if (!formColors.value.name) {
        nameError.value = 'Name is required';
        valid = false;
    }
    if (!formColors.value.description) {
        descriptionError.value = 'Description is required';
        valid = false;
    }
    for (const key of colorKeys) {
        if (!formColors.value[key]) {
            colorErrors.value[key] = 'Required';
            valid = false;
        }
    }
    return valid;
};

const handleSubmit = async () => {
    if (validateColors()) {
        try {
            const response = await axios.post(route('websiteBuilder.customColors'), formColors.value);
            if (response.data?.validate) {
                colors.value = formColors.value;

                props.data.closeModals();
                toast.fire({ icon: 'success', title: response.data?.message });
            }
        } catch (error: any) {
            if (error.response && error.response.status === 422) {
                for (const [key, message] of Object.entries(error.response.data.errors)) {
                    if (key === 'name') {
                        nameError.value = Array.isArray(message) ? message[0] : message;
                    } else if (key === 'description') {
                        descriptionError.value = Array.isArray(message) ? message[0] : message;
                    } else {
                        colorErrors.value[key] = Array.isArray(message) ? message[0] : message;
                    }
                }
            }
        }
    }
};

const reset = () => {
    formColors.value = { ...props.data.colors };
};

const showFormRef = ref(false);
const showForm = () => {
    showFormRef.value = !showFormRef.value;
};
</script>

<template>
    <DialogScrollContent class="sm:max-w-[calc(100%-3rem)] md:max-w-[calc(100%-3rem)] lg:max-w-[calc(100%-5rem)]">
        <DialogHeader>
            <DialogTitle>Preview</DialogTitle>
            <DialogDescription class="sr-only">No description provided.</DialogDescription>
        </DialogHeader>

        <div class="relative grid w-full grid-cols-6 items-start">
            <div class="bg-card col-span-1 flex flex-col gap-2 p-4 xl:col-span-2">
                <div class="border-muted col-span-2 flex flex-wrap items-center justify-between gap-3 border-b pb-2 xl:justify-end">
                    <Button @click="showForm()" variant="ghost" size="icon" class="xl:hidden">
                        <Menu class="size-5" />
                    </Button>

                    <Button
                        @click="reset()"
                        variant="outline"
                        class="bg-blue-600/10 font-medium text-blue-600 hover:bg-blue-600/20"
                        :class="[showFormRef ? 'hidden' : 'flex']"
                    >
                        reset
                    </Button>
                </div>

                <div
                    class="absolute top-30 z-20 transition-all duration-300 ease-in-out lg:top-18 xl:relative xl:top-0"
                    :class="[showFormRef ? '-start-200' : 'bg-body border-muted start-0 rounded-md border p-3']"
                >
                    <div class="grid grid-cols-2 gap-x-3 gap-y-5">
                        <div class="col-span-2 flex flex-col gap-2">
                            <HeadingSmall title="Name" titleClass="text-sm" />
                            <div class="flex flex-col gap-1">
                                <Input v-model="formColors.name" />
                                <InputError :message="nameError" />
                            </div>
                        </div>

                        <div v-for="key in colorKeys" :key="key" class="flex flex-col gap-2">
                            <HeadingSmall :title="key" titleClass="text-sm" />
                            <div class="flex flex-col gap-1">
                                <Color v-model="formColors[key]" />
                                <InputError :message="colorErrors[key]" />
                            </div>
                        </div>

                        <div class="col-span-2 flex flex-col gap-2">
                            <HeadingSmall title="Description" titleClass="text-sm" />
                            <div class="flex flex-col gap-1">
                                <Textarea v-model="formColors.description" />
                                <InputError :message="descriptionError" />
                            </div>
                        </div>

                        <div class="col-span-2">
                            <Button @click="handleSubmit" class="w-full">Submit</Button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-5 xl:col-span-4">
                <component :is="actionCompo" :colors="formColors" />
            </div>
        </div>
    </DialogScrollContent>
</template>
