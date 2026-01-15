<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { FileUploader, Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { toast } from '@/lib/sweetAlert';
import { BreadcrumbItem, SharedData } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Check, LoaderCircle, MessageCircleWarning, Trash2 } from 'lucide-vue-next';
import { reactive } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/dashboard/products',
    },
    {
        title: 'Edit',
        href: '/dashboard/products/edit',
    },
];

const props = defineProps<{
    data: Record<string, any>;
    categories: Record<string, any>;
    brands: Record<string, any>;
    colors: Record<string, any>;
    attributes: Record<string, any>;
    websiteNameAndLogo: Record<string, any>;
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

const initializeAttributes = (variantAttributes?: any[]) => {
    return props.attributes.map((attr: any) => {
        // Find if this attribute has a value in the variant
        const variantAttr = variantAttributes?.find((va: any) => {
            if (attr.name === 'color') {
                return va.attribute_id === attr.id && va.color_id;
            }
            return va.attribute_id === attr.id && va.attribute_value_value;
        });

        let value = null;
        if (variantAttr) {
            if (attr.name === 'color') {
                value = variantAttr.color_id;
            } else {
                value = variantAttr.attribute_value_id ?? variantAttr.attribute_value_value;
            }
        }

        return {
            ...attr,
            value: value,
        };
    });
}

const createEmptyVariant = () => {
    return {
        price: null,
        stock_quantity: null,
        ecommerce_product_images: [],
        attributes: initializeAttributes(),
    };
}

const prepareVariants = (variants: any[]) => {
    if (!variants || variants.length === 0) {
        return [createEmptyVariant()];
    }

    return variants.map((variant: any) => ({
        id: variant.id,
        price: variant.price,
        stock_quantity: variant.stock_quantity,
        ecommerce_product_images: variant.ecommerce_product_images || [],
        attributes: initializeAttributes(variant.attributes),
    }));
}

const form = useForm<{
    step: number;
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
    step: 1,
    category_id: props.data.category_id,
    brand_id: props.data.brand_id,
    name: props.data.name,
    price: props.data.price,
    discount_price: props.data.discount_price,
    variants: reactive(prepareVariants(props.data.variants)),
    is_active: props.data.is_active,
    short_description: props.data.short_description,
    description: props.data.description,
});

const deleteVariant = (index: number) => {
    form.variants.splice(index, 1);
}

const nextStep = () => {
    form.post(route('website.e-commerce.dashboard.products.update', props.data.id), {
        onSuccess: () => {
            form.step = 2;
        },
        onError: (errors: any) => {
            Object.keys(errors).forEach((key) => {
                if (!(key in form)) {
                    toast.fire({ icon: 'error', title: errors[key] + ' ' });
                }
            });
        },
    });
};

const previousStep = () => {
    form.step = 1;
};
</script>

<template>
    <Head title="Edit Product" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="e-commerce" :websiteNameAndLogo="props.websiteNameAndLogo">
        <div class="flex flex-col gap-4 p-2 md:p-4">
            <div class="grid gap-4 md:grid-cols-5 md:gap-3 lg:gap-4">
                <!-- Basic Data -->
                <div class="border-muted flex-col gap-4 rounded-md border p-4 md:col-span-4" :class="form.step < 2 ? 'flex' : 'hidden'">
                    <div class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
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

                        <!-- Is Active -->
                        <div class="flex flex-col gap-1.5">
                            <Label>Is Active*</Label>
                            <Select v-model="form.is_active" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </Select>
                            <InputError :message="form.errors.is_active" />
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
                    </div>
                </div>

                <!-- Variants -->
                <div class="border-muted grid-cols-1 gap-4 rounded-md border p-4 md:col-span-4" :class="form.step === 2 ? 'grid' : 'hidden'">
                    <div class="mb-3 flex flex-col gap-2 lg:col-span-2">
                        <InputError :message="form.errors.variants" />

                        <Label>Variants</Label>
                        <div v-for="(variant, index) in form.variants" :key="variant.id || index" class="border-muted mb-2 rounded border p-2">
                            <div
                                class="mb-3 flex w-full items-center justify-between gap-2 rounded-md bg-[var(--primary)]/10 p-2 dark:bg-[var(--primary)]/5"
                            >
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
                                <div v-if="props.attributes.length > 0" class="flex flex-col gap-1.5">
                                    <Label>Price</Label>
                                    <Input v-model="variant.price" type="number" placeholder="e.g. 999" required />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <Label>Stock Quantity</Label>
                                    <Input v-model="variant.stock_quantity" type="number" min="0" placeholder="e.g. 10" />
                                </div>
                                <template v-if="props.attributes.length > 0">
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
                                            :options="
                                                attribute.name === 'color'
                                                    ? mappedColors
                                                    : attribute.values.map((value: any) => ({
                                                          value: value.id,
                                                          label: value.value,
                                                      }))
                                            "
                                            :placeholder="`Select ${attribute.name}`"
                                        />
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="" v-if="props.attributes.length > 0">
                            <Button
                                type="button"
                                size="sm"
                                class="rounded-none opacity-50 hover:opacity-100"
                                @click="form.variants.push(createEmptyVariant())"
                            >
                                Add Variant
                            </Button>
                        </div>
                        <p v-else class="flex items-center gap-1 text-sm text-yellow-600 dark:text-yellow-400">
                            <MessageCircleWarning class="size-5" />
                            <span>To create multiple variants, please add at least one attribute first.</span>
                        </p>
                    </div>
                </div>

                <!-- Side -->
                <div class="flex flex-col gap-5 md:col-span-1">
                    <div class="border-muted flex flex-col gap-3 border-b pb-4">
                        <div
                            class="flex items-center gap-1 rounded-md text-sm lg:gap-2"
                            :class="form.step < 2 ? 'text-active font-medium opacity-100' : 'text-green-600 opacity-70'"
                        >
                            <span class="bg-nav border-muted flex size-8 items-center justify-center rounded-full border text-sm">
                                <template v-if="form.step === 1">1</template>
                                <Check v-else-if="form.step === 2" class="size-4" />
                            </span>
                            <span>Product Data</span>
                        </div>

                        <div
                            class="flex items-center gap-1 rounded-md text-sm lg:gap-2"
                            :class="
                                form.step < 2
                                    ? 'text-active opacity-70'
                                    : form.step === 2
                                      ? 'text-active font-medium opacity-100'
                                      : 'text-green-600 opacity-70'
                            "
                        >
                            <span class="bg-nav border-muted flex size-8 items-center justify-center rounded-full border text-sm">
                                <span class="bg-nav border-muted flex size-8 items-center justify-center rounded-full border text-sm">
                                    <Check v-if="form.step > 2" class="size-4" />
                                    <template v-else>2</template>
                                </span>
                            </span>
                            <span>Product variants</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-1.5 md:gap-1 lg:gap-2">
                        <Button size="sm" :disabled="form.step < 2" @click="previousStep()" variant="secondary">Previous</Button>
                        <Button size="sm" :disabled="form.processing" @click="nextStep()">
                            <span v-if="!form.processing">{{ form.step < 2 ? 'Next' : 'Update' }}</span>
                            <LoaderCircle v-else class="size-5 animate-spin transition duration-200" />
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
