<script setup lang="ts">
import { Image } from '@/components/ui/image';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { formatters } from '@/lib/dataTable';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu Itmes',
        href: '/dashboard/menu-items',
    },
    {
        title: 'View',
        href: '/dashboard/menu-items/view',
    },
];

const props = defineProps<{
    data: Record<string, any>;
    menuGroups: Record<string, any>;
    websiteNameAndLogo?: Record<string, string>;
}>();

const options = props.data.options;

const formattedGrpOptions = props.menuGroups.map((grp: any) => {
    return {
        group_id: grp.id,
        group_name: grp.name,
        options: options.filter((opt: any) => opt.option_group_id === grp.id),
    };
});
</script>

<template>
    <Head title="View item" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="restaurant" :websiteNameAndLogo="props.websiteNameAndLogo">
        <div class="relative p-2 md:p-4">
            <div class="border-muted relative z-10 grid grid-cols-1 gap-5 border-b px-4">
                <!-- Menu Item Details -->
                <div class="grid grid-cols-1 gap-4">
                    <!-- General Information -->
                    <div class="border-muted flex flex-col gap-3 border-b pb-4">
                        <h3 class="text-active-link text-2xl font-bold">{{ props.data.name }}</h3>
                        <div class="flex items-center gap-2" v-if="props.data.category_name || props.data.brand_name">
                            <div
                                v-if="props.data.category_name"
                                class="rounded bg-gradient-to-br from-lime-600 to-lime-500 px-3 py-1.5 text-sm text-white dark:from-lime-700 dark:to-lime-800"
                            >
                                {{ props.data.category_name }}
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="custom-scrollbar border-muted col-span-1 flex max-w-full items-center overflow-x-auto border-b pt-2 pb-4">
                        <Image
                            v-for="image in props.data.restaurant_item_images"
                            :key="image.uid"
                            :src="image.original_url"
                            alt="Item Image"
                            :withTeleport="true"
                            class="max-h-18 min-h-18 max-w-18 min-w-18 flex-shrink-0 rounded-full object-cover shadow"
                        />
                    </div>

                    <div class="grid grid-cols-1 gap-y-4 lg:grid-cols-2">
                        <!-- Pricing -->
                        <div class="border-muted border-muted flex flex-col gap-4 border-b pb-4 lg:border-e lg:pe-3">
                            <div class="text-body-muted flex w-full items-center justify-between text-sm">
                                Base Price:
                                <div class="text-active flex items-center gap-1 text-base font-medium md:gap-2">
                                    <span> {{ props.data.is_discount ? props.data.price - props.data.discount_price : props.data.price }}$ </span>
                                    <span v-if="props.data.is_discount" class="text-body-muted text-sm line-through"> {{ props.data.price }}$ </span>
                                </div>
                            </div>
                            <p v-if="props.data.discount_price" class="text-body-muted flex w-full items-center justify-between text-sm">
                                Discount Price: <span class="text-active text-base font-medium">{{ props.data.discount_price }}$</span>
                            </p>
                            <p class="text-body-muted flex w-full items-center justify-between text-sm">
                                Views Count: <span class="text-active text-base font-medium">{{ props.data.views_count }}</span>
                            </p>
                            <!-- <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Sales Count: <span class="text-active text-base font-medium">{{ props.data.sales_count }}</span>
                        </p> -->
                        </div>

                        <!-- Status -->
                        <div class="border-muted flex flex-col gap-5 border-b pb-4 lg:ps-3">
                            <p class="text-body-muted flex w-full items-center justify-between text-sm">
                                Is Discount: <span v-html="formatters.boolean(props.data.is_discount)"></span>
                            </p>
                            <p class="text-body-muted flex w-full items-center justify-between text-sm">
                                Is In Home: <span v-html="formatters.boolean(props.data.is_in_home)"></span>
                            </p>
                            <p class="text-body-muted flex w-full items-center justify-between text-sm">
                                Is Special: <span v-html="formatters.boolean(props.data.is_special)"></span>
                            </p>
                            <p class="text-body-muted flex w-full items-center justify-between text-sm">
                                Is Active: <span v-html="formatters.boolean(props.data.is_active)"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Short Description -->
                    <div v-if="props.data.short_description" class="border-muted gap-2-b flex flex-col border-b pb-4">
                        <h4 class="text-body-muted text-sm">Short Description:</h4>
                        <p class="text-active text-base font-medium whitespace-pre-line">{{ props.data.short_description }}</p>
                    </div>

                    <!-- Description -->
                    <div v-if="props.data.description" class="border-muted flex flex-col gap-2 border-b pb-4">
                        <h4 class="text-body-muted text-sm font-semibold">Description:</h4>
                        <p class="text-active text-base font-medium whitespace-pre-line">{{ props.data.description }}</p>
                    </div>
                </div>

                <!-- Options Section -->
                <div class="flex flex-col gap-1.5">
                    <h4 class="text-body font-bold">Menu item options:</h4>

                    <div class="mb-3 flex flex-col gap-2">
                        <div
                            v-if="options && options.length > 0"
                            class="custom-scrollbar grid max-h-[70vh] max-w-full gap-2.5 overflow-y-auto md:grid-cols-3 lg:gap-4"
                        >
                            <div v-for="group in formattedGrpOptions" :key="group.group_id" class="flex flex-col gap-2">
                                <p class="text-active-link pt-2 text-sm">{{ group.group_name }}:</p>

                                <template v-if="group.options.length > 0">
                                    <div class="bg-[var(--primary)]/10 dark:bg-[var(--primary)]/8 rounded-md p-2 md:p-4">
                                        <div
                                            v-for="(option, index) in group.options"
                                            :key="option.id"
                                            class="flex flex-col gap-1"
                                            :class="Number(index) + 1 === group.options.length ? '' : 'border-muted border-b pb-2'"
                                        >
                                            <div class="flex items-center justify-between gap-1">
                                                <span class="text-body-muted text-xs">Name:</span>
                                                <span class="text-body text-sm">{{ option.name }}</span>
                                            </div>
                                            <div class="flex items-center justify-between gap-1">
                                                <span class="text-body-muted text-xs">Name (ar):</span>
                                                <span class="text-body text-sm">{{ option.name_ar }}</span>
                                            </div>
                                            <div class="flex items-center justify-between gap-1">
                                                <span class="text-body-muted text-xs">Price delta:</span>
                                                <span class="text-body text-sm">{{ option.price_delta }}$</span>
                                            </div>
                                            <div class="flex items-center justify-between gap-1">
                                                <span class="text-body-muted text-xs">Price:</span>
                                                <span class="text-body text-sm">{{ option.is_increase ? 'Increase' : 'Deacrease' }}</span>
                                            </div>
                                            <div v-if="option.option_explain" class="flex items-center justify-between gap-1">
                                                <span class="text-body-muted text-xs">Option explain:</span>
                                                <span class="text-body text-sm">{{ option.option_explain }}</span>
                                            </div>
                                            <div class="flex items-center justify-between gap-1">
                                                <span class="text-body-muted text-xs">Is Active:</span>
                                                <span v-html="formatters.boolean(option.is_active)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div v-else class="bg-[var(--primary)]/10 dark:bg-[var(--primary)]/8 text-body-muted flex w-full items-center justify-center rounded-md py-8 text-sm">
                                    No Options
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-body-muted relative flex items-center justify-between gap-1 px-4 pt-4 text-xs">
                Created At: <span class="font-medium">{{ formatters.date(props.data.created_at, 'long') }}</span>
            </p>
            <p class="text-body-muted relative flex items-center justify-between gap-1 px-4 pt-3 text-xs">
                Updated At: <span class="font-medium">{{ formatters.date(props.data.updated_at, 'long') }}</span>
            </p>
        </div>
    </DashboardLayout>
</template>
