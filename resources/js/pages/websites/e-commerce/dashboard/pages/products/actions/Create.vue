<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DialogClose, DialogFooter } from '@/components/ui/dialog';
import { FileUploader, Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { toast } from '@/lib/sweetAlert';
import { SharedData } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle, Trash2 } from 'lucide-vue-next';
import { reactive } from 'vue';

const props = defineProps<{
    categories: Record<string, any>;
    brands: Record<string, any>;
    colors: Record<string, any>;
    attributes: Record<string, any>;
}>();

const page = usePage<SharedData>();

const mappedCategories = props.categories.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const mappedBrands = props.brands.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const mappedColors = props.colors.map((item: any) => ({
    value: item.id,
    label: page.props.lang === 'ar' ? item.ar_name : item.name,
    color: item.code,
}));

function initializeAttributes() {
    return props.attributes.map((attr: any) => ({
        ...attr,
        value: null, // Initialize with null instead of undefined
    }));
}

function createEmptyVariant(id?: number) {
    return {
        id: id ?? 1,
        price: null,
        stock_quantity: null,
        ecommerce_product_images: [],
        attributes: initializeAttributes(),
    };
}

const form = useForm<{
    category_id: string;
    brand_id: string;
    name: string;
    price: number | string;
    discount_price: number | string;
    variants: Record<string, any>[];
    is_active: string;
    short_description: string;
    description: string;
}>({
    category_id: '',
    brand_id: '',
    name: '',
    price: '',
    discount_price: '',
    variants: reactive([createEmptyVariant()]),
    is_active: '',
    short_description: '',
    description: '',
});

function deleteVariant(index: number) {
    form.variants.splice(index, 1);
}

function submit() {
    form.post(route('website.e-commerce.dashboard.products.store'), {
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
            <Label>Name*</Label>
            <Input v-model="form.name" type="text" placeholder="e.g. iPhone 15 Pro Max" required />
            <InputError :message="form.errors.name" />
        </div>

        <!-- Price -->
        <div class="flex flex-col gap-1.5">
            <Label>Base Price*</Label>
            <Input v-model="form.price" type="number" placeholder="e.g. 999" required />
            <InputError :message="form.errors.price" />
        </div>

        <!-- Discount Price -->
        <div class="flex flex-col gap-1.5">
            <Label>Discount Price</Label>
            <Input v-model="form.discount_price" type="number" placeholder="e.g. 100" />
            <InputError :message="form.errors.discount_price" />
        </div>

        <!-- Flags -->
        <div class="flex flex-col gap-1.5">
            <Label>Is Active*</Label>
            <Select v-model="form.is_active" required>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </Select>
            <InputError :message="form.errors.is_active" />
        </div>

        <!-- Variants -->
        <div class="mb-3 flex flex-col gap-2 lg:col-span-2">
            <Label>Variants</Label>
            <div v-for="(variant, index) in form.variants" :key="variant.id || index" class="border-muted mb-2 rounded border p-2">
                <div class="bg-[var(--primary)]/10 dark:bg-[var(--primary)]/5 mb-3 flex w-full items-center justify-between gap-2 rounded-md p-2">
                    <span class="text-body-muted text-sm">Variant {{ index + 1 }}</span>
                    <Button
                        type="button"
                        variant="destructive"
                        size="icon"
                        class="size-6 rounded-full opacity-50 hover:opacity-100"
                        v-if="form.variants.length > 1"
                        @click="deleteVariant(index)"
                    >
                        <Trash2 class="size-3.5" />
                    </Button>
                </div>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="flex flex-col gap-1 md:col-span-2">
                        <Label>Images*</Label>
                        <FileUploader
                            v-model="variant.ecommerce_product_images"
                            :multiple="true"
                            :id="`ecommerce_product_images_${index}`"
                         />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <Label>Price</Label>
                        <Input v-model="variant.price" type="number" placeholder="e.g. 999" required />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label>Stock Quantity</Label>
                        <Input v-model="variant.stock_quantity" type="number" min="0" placeholder="e.g. 10" />
                    </div>
                    <div v-for="attribute in variant.attributes" :key="attribute.id" class="flex flex-col gap-1">
                        <Label class="capitalize">{{ attribute.name }}</Label>
                        <Input 
                            v-if="attribute.type === 'text'" 
                            v-model="attribute.value" 
                            type="text" 
                            :placeholder="`Enter ${attribute.name}`" 
                        />
                        <SelectWithSearch 
                            v-else-if="attribute.type === 'select'" 
                            v-model="attribute.value" 
                            :options="attribute.name === 'color' ? mappedColors : attribute.values.map((value: any) => ({
                                value: value.id,
                                label: value.value,
                            }))" 
                            :placeholder="`Select ${attribute.name}`"
                        />
                    </div>
                </div>
            </div>
            <div class="">
                <Button
                    type="button"
                    size="sm"
                    class="rounded-none opacity-50 hover:opacity-100"
                    @click="form.variants.push(createEmptyVariant(form.variants.length + 1))"
                >
                    Add Variant
                </Button>
            </div>
            <InputError :message="form.errors.variants" />
        </div>

        <!-- Short Description -->
        <div class="flex flex-col gap-1.5 lg:col-span-2">
            <Label>Short Description</Label>
            <Textarea
                v-model="form.short_description"
                placeholder="e.g. Display: 6.7-inch OLED | Processor: A17 Pro | RAM: 8GB | Storage: 256GB | Camera: Triple 48MP | Battery: 4500mAh | OS: iOS 17"
                :maxlength="255"
            />
            <InputError :message="form.errors.short_description" />
        </div>

        <!-- Description -->
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
        <Button type="submit" :disabled="form.processing" @click="submit">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            <span>Create</span>
        </Button>
    </DialogFooter>
</template>
