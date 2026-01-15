<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { FileUploader, Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { toast } from '@/lib/sweetAlert';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Check, LoaderCircle, Trash2 } from 'lucide-vue-next';
import { reactive, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu Itmes',
        href: '/dashboard/menu-items',
    },
    {
        title: 'Create',
        href: '/dashboard/menu-items/create',
    },
];

const props = defineProps<{
    menuGroups: Record<string, any>;
    categories: Record<string, any>;
    websiteNameAndLogo?: Record<string, string>;
}>();

const mappedCategories = props.categories.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const handleOptions = (priceType: 'increase' | 'deacrease' | 'custom') => {
    let is_increase = null;
    let is_custom_price_type = false;

    switch (priceType) {
        case 'increase':
            is_increase = true;
            is_custom_price_type = false;
            break;
        case 'deacrease':
            is_increase = false;
            is_custom_price_type = false;
            break;
        case 'custom':
            is_custom_price_type = true;
            break;
        default:
            break;
    }

    const result = {
        name: '',
        name_ar: '',
        price_delta: '',
        is_increase: is_increase,
        is_custom_price_type: is_custom_price_type,
        option_explain: '',
        is_active: '1',
    };

    return result;
};

const createEmptyOptionGroup = (group: any) => {
    return {
        group_id: group.id,
        group_name: group.name,
        group_price_type: group.price_type,
        options: reactive([]),
    };
};

const form = useForm<{
    step: number;
    restaurant_item_images: Record<any, any>;
    category_id: string;
    name: string;
    price: number | string;
    discount_price: number | string;
    group_options: Record<string, any>[];
    is_active: string;
    is_discount: string;
    short_description: string;
    description: string;
}>({
    step: 1,
    restaurant_item_images: [],
    category_id: '',
    name: '',
    price: '',
    discount_price: '',
    group_options: reactive(props.menuGroups.map((g: any) => createEmptyOptionGroup(g))),
    is_active: '1',
    is_discount: '0',
    short_description: '',
    description: '',
});

const load = ref(false);

const nextStep = () => {
    load.value = true;

    form.post(route('website.restaurant.dashboard.menuItems.store'), {
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
        onFinish: () => {
            load.value = false;
        },
    });
};

const previousStep = () => {
    form.step = 1;
};

const removeOption = (groupId: number, index: number) => {
    const group = form.group_options.find((v: any) => v.group_id === groupId);
    group?.options.splice(index, 1);
};

const addOption = (groupId: number) => {
    const group = form.group_options.find((v: any) => v.group_id === groupId);

    group?.options.push(handleOptions(group.group_price_type));
};
</script>

<template>
    <Head title="Create Item" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="restaurant" :websiteNameAndLogo="props.websiteNameAndLogo">
        <div class="flex flex-col gap-4 p-2 md:p-4">
            <div class="grid gap-4 md:grid-cols-5 md:gap-3 lg:gap-4">
                <!-- Basic Data -->
                <div class="border-muted flex-col gap-4 rounded-md border p-4 md:col-span-4" :class="form.step < 2 ? 'flex' : 'hidden'">
                    <div class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <!-- Images -->
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <Label>Item Images*</Label>
                            <FileUploader v-model="form.restaurant_item_images" :multiple="true" id="restaurant_item_images" />
                            <InputError :message="form.errors.restaurant_item_images" />
                        </div>

                        <!-- Category -->
                        <div class="flex flex-col gap-1.5">
                            <Label>Category</Label>
                            <SelectWithSearch v-model="form.category_id" :options="mappedCategories" placeholder="e.g. Electronics" required />
                            <InputError :message="form.errors.category_id" />
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

                        <!-- Is Discount -->
                        <div class="flex flex-col gap-1.5">
                            <Label>Is Discount</Label>
                            <Select v-model="form.is_discount" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </Select>
                            <InputError :message="form.errors.is_discount" />
                        </div>

                        <!-- Is Active -->
                        <div class="flex flex-col gap-1.5">
                            <Label>Is Active*</Label>
                            <Select v-model="form.is_active" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </Select>
                            <InputError :message="form.errors.is_active" />
                        </div>

                        <!-- Short Description -->
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <Label>Short Description</Label>
                            <Textarea
                                v-model="form.short_description"
                                placeholder="e.g. Display: 6.7-inch OLED | Processor: A17 Pro | RAM: 8GB | Storage: 256GB | Camera: Triple 48MP | Battery: 4500mAh | OS: iOS 17"
                                :maxlength="255"
                            />
                            <InputError :message="form.errors.short_description" />
                        </div>

                        <!-- Description -->
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
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

                <!-- Options -->
                <div
                    class="border-muted grid-cols-1 gap-4 rounded-md border p-4 sm:grid-cols-2 md:col-span-4 lg:grid-cols-3"
                    :class="form.step === 2 ? 'grid' : 'hidden'"
                >
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <InputError :message="form.errors.group_options" />
                    </div>
                    <div v-for="grp in form.group_options" :key="grp.group_id" class="flex flex-col gap-3 rounded-md">
                        <p class="text-active-link text-sm font-medium">{{ grp.group_name }}:</p>

                        <div class="border-muted rounded-md border">
                            <div
                                v-for="(opt, index) in grp.options"
                                :key="opt.name"
                                :class="['flex flex-col gap-2 p-2', index !== grp.options.length - 1 ? 'border-muted border-b' : '']"
                            >
                                <div
                                    class="text-body-muted bg-nav border-muted m flex items-center justify-between rounded-md border px-3 py-2 text-sm font-medium"
                                >
                                    <span>Option {{ Number(index) + 1 }}:</span>
                                    <Button
                                        type="button"
                                        variant="destructive"
                                        size="icon"
                                        class="size-6 rounded-full opacity-50 hover:opacity-100"
                                        @click="removeOption(grp.group_id, Number(index))"
                                    >
                                        <Trash2 class="size-3.5" />
                                    </Button>
                                </div>

                                <div class="grid grid-cols-2 gap-1 lg:gap-2">
                                    <div class="flex flex-col gap-1">
                                        <Label class="text-xs">Name:</Label>
                                        <Input v-model="opt.name" class="h-8 text-xs" placeholder="e.g. cheese, large ..." required />
                                    </div>

                                    <div class="flex flex-col gap-1">
                                        <Label class="text-xs">Name (ar):</Label>
                                        <Input v-model="opt.name_ar" class="h-8 text-xs" placeholder="e.g. cheese, large ..." required />
                                    </div>

                                    <div class="flex flex-col gap-1" :class="opt.is_custom_price_type ? 'col-span-1' : 'col-span-2'">
                                        <Label class="text-xs" v-if="!opt.is_custom_price_type"
                                            >{{ opt.is_increase ? 'Increase' : 'Deacrease' }} price:</Label
                                        >
                                        <Label class="text-xs" v-else>Price:</Label>
                                        <Input
                                            v-model="opt.price_delta"
                                            class="h-8 text-xs"
                                            type="number"
                                            placeholder="e.g. cheese, large ..."
                                            required
                                        />
                                    </div>

                                    <div class="flex flex-col gap-1" v-if="opt.is_custom_price_type">
                                        <Label class="text-xs">Price type:</Label>
                                        <Select v-model="opt.is_increase" class="h-8 py-1 text-xs" :withReset="false" required>
                                            <option value="1">Increase</option>
                                            <option value="0">Deacrease</option>
                                        </Select>
                                    </div>

                                    <div class="flex flex-col gap-1">
                                        <Label class="text-xs">Explain it:</Label>
                                        <Input v-model="opt.option_explain" class="h-8 text-xs" placeholder="e.g. cheese, large ..." required />
                                    </div>

                                    <div class="flex flex-col gap-1">
                                        <Label class="text-xs">Is Active:</Label>
                                        <Select v-model="opt.is_active" class="h-8 py-1 text-xs" :withReset="false" required>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </Select>
                                    </div>
                                </div>
                            </div>

                            <div class="border-muted border-t p-2">
                                <Button type="button" size="sm" class="rounded-none opacity-50 hover:opacity-100" @click="addOption(grp.group_id)">
                                    Add Variant
                                </Button>
                            </div>
                        </div>
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
                            <span>Base Item Data</span>
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
                            <span>Item Options</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-1.5 md:gap-1 lg:gap-2">
                        <Button size="sm" :disabled="form.step < 2" @click="previousStep()" variant="secondary">Previous</Button>
                        <Button size="sm" :disabled="load" @click="nextStep()">
                            <span v-if="!load">{{ form.step < 2 ? 'Next' : 'Create' }}</span>
                            <LoaderCircle v-else class="size-5 animate-spin transition duration-200" />
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
