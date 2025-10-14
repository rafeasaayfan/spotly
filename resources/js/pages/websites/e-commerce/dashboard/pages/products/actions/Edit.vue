<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DialogClose, DialogFooter } from '@/components/ui/dialog';
import { Color, File, ImageFile, Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';

import { toast } from '@/lib/sweetAlert';

import { useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
    categories: Record<string, any>;
    brands: Record<string, any>;
}>();

const mappedCategories = props.categories.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const mappedBrands = props.brands.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const form = useForm<{
    category_id: string;
    brand_id: string;
    name: string;
    price: number | string;
    sale_price: number | string;
    variants: Record<string, any>[];
    is_active: string;
    short_description: string;
    description: string;
}>({
    category_id: props.data.category_id,
    brand_id: props.data.brand_id,
    name: props.data.name,
    price: props.data.price,
    sale_price: props.data.sale_price,
    variants: props.data.variants,
    is_active: props.data.is_active,
    short_description: props.data.short_description,
    description: props.data.description,
});

function submit() {
    form.post(route('website.e-commerce.dashboard.products.update', props.data.id), {
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

const variants = props.data.variants;
</script>

<template>
    <form class="grid grid-cols-1 gap-6 px-4 pb-5 lg:grid-cols-2" @submit.prevent="submit" enctype="multipart/form-data">
        <!-- Variants -->
        <div class="mb-3 flex flex-col gap-2 lg:col-span-2">
            <Label>Variants</Label>
            <div v-for="(variant, index) in form.variants" :key="index" class="border-muted mb-2 rounded border p-2">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <Label>Color {{ index + 1 }}</Label>
                        <Color v-model="variant.color" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label>Stock Quantity</Label>
                        <Input v-model="variant.stock_quantity" type="number" min="0" placeholder="e.g. 10" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label>Image</Label>
                        <File v-if="typeof variants[index] === 'undefined'" v-model="variant.ecommerce_product_image" />
                        <File v-else-if="!variants[index].ecommerce_product_image" v-model="variant.ecommerce_product_image" />
                        <ImageFile v-else v-model="variant.ecommerce_product_image" :src="variant.ecommerce_product_image" />
                    </div>
                    <div class="flex h-full items-end justify-end">
                        <Button
                            type="button"
                            variant="destructive"
                            size="sm"
                            class="rounded-none opacity-50 hover:opacity-100"
                            @click="form.variants.splice(index, 1) && variants.splice(index, 1)"
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
                    @click="form.variants.push({ color: '#000000', stock_quantity: 1, image: null })"
                >
                    Add Variant
                </Button>
            </div>
            <InputError :message="form.errors.variants" />
        </div>

        <!-- Category -->
        <div class="flex flex-col gap-1.5">
            <Label>Category</Label>
            <SelectWithSearch v-model="form.category_id" :options="mappedCategories" placeholder="e.g. Electronics" required />
            <InputError :message="form.errors.category_id" />
        </div>

        <!-- Brand -->
        <div class="flex flex-col gap-1.5">
            <Label>Brand</Label>
            <SelectWithSearch v-model="form.brand_id" :options="mappedBrands" placeholder="e.g. Apple" required />
            <InputError :message="form.errors.brand_id" />
        </div>

        <!-- Name -->
        <div class="flex flex-col gap-1.5">
            <Label>Name</Label>
            <Input v-model="form.name" type="text" placeholder="e.g. iPhone 15 Pro Max" required />
            <InputError :message="form.errors.name" />
        </div>

        <!-- Price -->
        <div class="flex flex-col gap-1.5">
            <Label>Price</Label>
            <Input v-model="form.price" type="number" placeholder="e.g. 999" required />
            <InputError :message="form.errors.price" />
        </div>

        <!-- Sale Price -->
        <div class="flex flex-col gap-1.5">
            <Label>Sale Price</Label>
            <Input v-model="form.sale_price" type="number" placeholder="e.g. 100" />
            <InputError :message="form.errors.sale_price" />
        </div>

        <!-- Flags -->
        <div class="flex flex-col gap-1.5">
            <Label>Is Active</Label>
            <Select v-model="form.is_active" required>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </Select>
            <InputError :message="form.errors.is_active" />
        </div>

        <div class="flex flex-col gap-1.5 lg:col-span-2">
            <Label>Short Description</Label>
            <Textarea
                v-model="form.short_description"
                placeholder="e.g. Display: 6.7-inch OLED | Processor: A17 Pro | RAM: 8GB | Storage: 256GB | Camera: Triple 48MP | Battery: 4500mAh | OS: iOS 17"
                :maxlength="255"
            />
            <InputError :message="form.errors.short_description" />
        </div>

        <!-- Descriptions -->
        <div class="flex flex-col gap-1.5 lg:col-span-2">
            <Label>Description</Label>
            <Textarea
                v-model="form.description"
                placeholder="e.g. The iPhone 15 Pro Max features a 6.7-inch display, A17 chip, and advanced camera system."
                :maxlength="500"
            />
            <InputError :message="form.errors.description" />
        </div>
    </form>

    <DialogFooter>
        <DialogClose as-child>
            <Button variant="secondary">Cancel</Button>
        </DialogClose>

        <Button type="submit" :disabled="form.processing" @click="submit()">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            <span>Update</span>
        </Button>
    </DialogFooter>
</template>
