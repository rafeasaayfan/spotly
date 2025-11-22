<script setup lang="ts">
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';

const categories = [
    {
        name: 'Electronics',
        ar_name: 'إلكترونيات',
        ecommerce_products_count: 120
    },
    {
        name: 'Apparel',
        ar_name: 'ملابس',
        ecommerce_products_count: 350
    },
    {
        name: 'Home Goods',
        ar_name: 'أدوات منزلية',
        ecommerce_products_count: 80
    },
    {
        name: 'Books',
        ar_name: 'كتب',
        ecommerce_products_count: 200
    },
    {
        name: 'Sports & Outdoors',
        ar_name: 'رياضة وهواء الطلق',
        ecommerce_products_count: 150
    },
    {
        name: 'Beauty & Personal Care',
        ar_name: 'الجمال والعناية الشخصية',
        ecommerce_products_count: 95
    },
    {
        name: 'Toys & Games',
        ar_name: 'ألعاب وهوايات',
        ecommerce_products_count: 60
    },
    {
        name: 'Automotive',
        ar_name: 'سيارات',
        ecommerce_products_count: 44
    },
    {
        name: 'Jewelry',
        ar_name: 'مجوهرات',
        ecommerce_products_count: 76
    },
];

const page = usePage<SharedData>();

const getCategoryBgClass = (index: number) => {
    const colors = [
        'bg-blue-600',
        'bg-fuchsia-600',
        'bg-teal-600',
        'bg-orange-600',
        'bg-lime-600',
        'bg-rose-600',
        'bg-emerald-600',
        'bg-cyan-600',
        'bg-yellow-600',
    ];
    return colors[index % colors.length];
};
</script>

<template>
    <section v-if="categories && categories.length > 0" class="py-22" id="eco-categories-section">
        <h2 class="web-text-active eco-section-title-underline mb-10 text-3xl font-bold sm:text-4xl lg:text-5xl">
            {{ $t('our') }} <span class="eco-gradient-text">{{ $t('our.categories') }}</span>
        </h2>

        <div class="flex flex-col flex-wrap items-center gap-3 sm:flex-row">
            <div
                v-for="(category, index) in categories"
                :key="index"
                class="category-card group web-bg-card web-border-color relative flex h-20 w-full rounded-md border px-2 transition-all duration-300 ease-in-out md:h-40 md:w-60"
            >
                <div
                    :class="[
                        'card-media flex h-full -translate-y-2 items-center justify-center px-2 transition-all duration-300 ease-in-out group-hover:translate-y-0',
                        getCategoryBgClass(Number(index)),
                    ]"
                >
                    <div class="flex size-8 items-center justify-center rounded-full bg-white/15 md:size-12">
                        <span class="text-xl font-bold text-white uppercase md:text-3xl">
                            {{ page.props.lang === 'ar' ? category.ar_name.charAt(0) : category.name.charAt(0) }}
                        </span>
                    </div>
                </div>
                <div class="flex flex-col justify-center gap-1 ps-4">
                    <h3 class="web-text-active text-lg font-medium md:text-2xl">
                        {{ page.props.lang === 'ar' ? category.ar_name : category.name }}
                    </h3>
                    <p class="web-text-body-muted text-xs md:text-sm">{{ category.ecommerce_products_count }} {{ $t('products') }}</p>
                </div>
            </div>

            <div
                class="web-border-color group relative flex h-20 w-full items-center justify-center rounded-md border-2 
                border-dashed bg-transparent transition-all duration-100 ease-in-out hover:border-3 hover:border-blue-500
                hover:bg-[var(--bg_content_light)]/70 sm:h-30 sm:w-45 md:h-40 md:w-60 dark:hover:bg-[var(--bg_content_dark)]/70"
            >
                <div class="flex h-full flex-col items-center justify-center gap-1 text-center">
                    <div class="flex flex-row-reverse items-center gap-1 sm:flex-col">
                        <ArrowRight
                            class="sm:mb-1 size-4.5 sm:size-5.5 transition-transform duration-300 md:mb-2 md:size-8"
                            :class="page.props.lang === 'ar' ? 'rotate-180 group-hover:-translate-x-1' : 'group-hover:translate-x-1'"
                        />
                        <h3 class="web-text-active text-base font-bold md:text-xl">{{ $t('more.categories') }}</h3>
                    </div>
                    <p class="web-text-body-muted text-xs md:text-sm">{{ $t('explore.everything') }}</p>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.category-card {
    transform-style: preserve-3d; /* Enable 3D transforms for hover */
    perspective: 1000px;
}

.card-media {
    /* This clip-path creates the L-shape cut-out effect */
    clip-path: polygon(0 0, 100% 0, 100% 70%, 0% 100%);
}
</style>
