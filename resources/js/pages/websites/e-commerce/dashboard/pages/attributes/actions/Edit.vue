<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DialogClose, DialogFooter } from '@/components/ui/dialog';
import { Input, InputError, Select, Textarea } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { toast } from '@/lib/sweetAlert';
import { useForm } from '@inertiajs/vue3';
import { InfoIcon, LoaderCircle } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    data: Record<string, any>;
    attributes: Record<string, any>;
}>();

const mappedAttributes = props.attributes.map((item: Record<string, any>) => ({
    value: item.value,
    label: item.label,
    value_ar: item.value_ar,
}));

const form = useForm<{
    name: string;
    name_ar: string;
    values: Record<string, any>;
    description: string;
    is_active: string;
}>({
    name: props.data.name,
    name_ar: props.data.name_ar,
    values: props.data.values,
    description: props.data.description,
    is_active: props.data.is_active,
});

const name_ar = computed(() => {
    return mappedAttributes.find((attribute: Record<string, any>) => attribute.value === form.name)?.value_ar;
});
const selectedAttribute = computed(() => {
    return form.name;
});

function submit() {
    form.name_ar = name_ar.value;
    if (selectedAttribute.value === 'color') {
        form.values = [];
    }
    
    form.post(route('website.e-commerce.dashboard.attributes.update', { attribute: props.data.id }), {
        onSuccess: () => {
            const closeButton = document.querySelector('[data-slot="dialog-close"]');
            (closeButton as HTMLElement)?.click();
        },
        onError: (errors: any) => {
            Object.keys(errors).forEach((key) => {
                if (!(key in form)) {
                    toast.fire({ icon: 'error', title: errors[key] + ' ' });
                }
            });
        },
    });
}
</script>

<template>
    <form class="grid grid-cols-1 gap-6 px-4 pb-5 lg:grid-cols-2" @submit.prevent="submit" enctype="multipart/form-data">
        <!-- Name -->
        <div class="flex flex-col gap-1.5">
            <Label>Name</Label>
            <Select v-model="form.name" placeholder="Select the attribute name (e.g., Color, Size)" required>
                <option v-for="attribute in mappedAttributes" :key="attribute.value" :value="attribute.value">{{ attribute.label }}</option>
            </Select>
            <InputError :message="form.errors.name" />
        </div>

        <!-- Arabic Name -->
        <div class="flex flex-col gap-1.5">
            <Label>Arabic Name</Label>
            <Input v-model="name_ar" type="text" placeholder="The Arabic name of the attribute (e.g., لون, حجم)" required readonly />
            <InputError :message="form.errors.name_ar" />
        </div>

        <!-- Values -->
        <div v-if="selectedAttribute !== 'color'" class="mb-3 flex flex-col gap-2 lg:col-span-2">
            <Label>Possible Values (Optional — leave empty to allow any value)</Label>
            <div v-for="(item, index) in form.values" :key="index" class="border-muted mb-2 rounded border p-2">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <Label>Value {{ index + 1 }}</Label>
                        <Input v-model="item.value" type="text" placeholder="e.g. XL" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label>Value in Arabic</Label>
                        <Input v-model="item.value_ar" type="text" placeholder="e.g. XL, كبير" />
                    </div>
                    <div class="md:col-span-2 flex h-full items-end justify-end">
                        <Button
                            type="button"
                            variant="destructive"
                            size="sm"
                            class="rounded-none opacity-50 hover:opacity-100"
                            @click="form.values.splice(index, 1)"
                        >
                            Remove
                        </Button>
                    </div>
                </div>
            </div>
            <div class="">
                <Button
                    type="button"
                    size="sm"
                    class="rounded-none opacity-50 hover:opacity-100"
                    @click="form.values.push({ value: '', value_ar: '' })"
                >
                    Add Value
                </Button>
            </div>
            <InputError :message="form.errors.values" />
        </div>
        <div v-else class="mb-3 flex gap-2 items-center lg:col-span-2">
            <InfoIcon class="size-4 text-active-link" />
            <span class="font-bold">All colors are already available in our system.</span>
        </div>

        <!-- Is Active -->
        <div class="flex flex-col gap-1.5">
            <Label>Is Active</Label>
            <Select v-model="form.is_active" placeholder="Is this attribute active?" required>
                <option value="1">Yes, Active</option>
                <option value="0">No, Inactive</option>
            </Select>
            <InputError :message="form.errors.is_active" />
        </div>

        <!-- Description -->
        <div class="flex flex-col gap-1.5 lg:col-span-2">
            <Label>Description</Label>
            <Textarea v-model="form.description" placeholder="Describe what this attribute is used for (optional)" :maxlength="200" />
            <InputError :message="form.errors.description" />
        </div>
    </form>

    <DialogFooter>
        <DialogClose as-child>
            <Button variant="secondary">Cancel</Button>
        </DialogClose>
        <Button type="submit" :disabled="form.processing" @click="submit">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            <span>Create</span>
        </Button>
    </DialogFooter>
</template>
