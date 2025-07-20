<script setup lang="ts">
import { DialogDescription, DialogHeader, DialogScrollContent, DialogTitle } from '@/components/ui/dialog';
import Home from '@/pages/preview/e-commerce/spotly-ui/pages/home/Home.vue';
import axios from 'axios';
import { reactive, ref } from 'vue';
import HeadingSmall from '../headers/HeadingSmall.vue';
import { Button } from '../ui/button';
import { Color, Input, InputError, Textarea } from '../ui/fields';
import { toast } from '@/lib/sweetAlert';

const props = defineProps<{
    data: {
        colors: Record<string, any>;
        path: string;
        closeModals: any
    };
}>();

const colorKeys = Object.keys(props.data.colors).filter(
    (key) =>
        key !== 'name' &&
        key !== 'description' &&
        !['id', 'created_by', 'created_at', 'updated_at', 'is_active', 'is_custom'].includes(key) &&
        typeof props.data.colors[key] === 'string' &&
        (key.includes('color') ||
            key.includes('bg') ||
            key.includes('primary') ||
            key.includes('secondary') ||
            key.includes('danger') ||
            key.includes('foreground')),
);

// Local copy for editing
const formColors = reactive({ ...props.data.colors });

// Validation state
const colorErrors = ref<Record<string, string>>({});
const nameError = ref('');
const descriptionError = ref('');

const validateColors = () => {
    let valid = true;
    colorErrors.value = {};
    nameError.value = '';
    descriptionError.value = '';
    if (!formColors.name) {
        nameError.value = 'Name is required';
        valid = false;
    }
    if (!formColors.description) {
        descriptionError.value = 'Description is required';
        valid = false;
    }
    for (const key of colorKeys) {
        if (!formColors[key]) {
            colorErrors.value[key] = 'Required';
            valid = false;
        }
    }
    return valid;
};

const handleSubmit = async () => {
    if (validateColors()) {
        try {            
            const response = await axios.post(route('websiteBuilder.customColors'), formColors);
            if (response.data?.validate) {
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
    // Remove all keys from formColors
    Object.keys(formColors).forEach((key) => {
        delete formColors[key];
    });
    Object.assign(formColors, props.data.colors);
};
</script>

<template>
    <DialogScrollContent class="sm:max-w-[calc(100%-3rem)] md:max-w-[calc(100%-3rem)] lg:max-w-[calc(100%-5rem)]">
        <DialogHeader>
            <DialogTitle>Preview</DialogTitle>
            <DialogDescription class="sr-only">No description provided.</DialogDescription>
        </DialogHeader>

        <div class="grid w-full grid-cols-6 items-start">
            <div class="bg-card col-span-2 grid grid-cols-2 gap-x-3 gap-y-5 p-4">
                <div class="col-span-2 flex justify-end">
                    <Button @click="reset()" variant="outline" class="bg-blue-600/10 font-medium text-blue-600 hover:bg-blue-600/20"> reset </Button>
                </div>

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
                    <Button @click="handleSubmit"> Submit </Button>
                </div>
            </div>

            <div class="col-span-4">
                <Home :colors="formColors" />
            </div>
        </div>
    </DialogScrollContent>
</template>
