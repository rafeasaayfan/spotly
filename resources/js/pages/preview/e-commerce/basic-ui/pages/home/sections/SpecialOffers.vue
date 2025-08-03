<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { ChevronRight, ShoppingCart } from 'lucide-vue-next';

// Placeholder for product data - ideally this would come from a prop or API call
const featuredProduct = {
    name: 'Nike Air Max 270',
    category: "Men's Running Shoes",
    description:
        'Experience unparalleled comfort and style with the Nike Air Max 270. Engineered for performance and designed for everyday wear, featuring a large Max Air unit for ultimate cushioning.',
    originalPrice: 150,
    currentPrice: 120,
    discount: '20% OFF',
    imageUrl: '/images/img.jpg', // Make sure this path is correct
    features: ['Max Air Unit Cushioning', 'Breathable Knit Upper', 'Durable Rubber Outsole', 'Lightweight Comfort'],
};
</script>

<template>
    <section class="featured-offer-section relative flex flex-col gap-4 py-22">
        <div class="flex w-full items-center justify-between">
            <h2 class="web-text-active web-section-title-underline text-3xl font-bold md:text-4xl">
                Special
                <span class="web-gradient-text">Offer</span>
            </h2>

            <Link href="#" class="group web-text-active-link flex items-center gap-0.5 text-sm font-medium">
                <span>See All</span>
                <ChevronRight class="size-4 transition-all duration-300 ease-in-out group-hover:translate-x-1" />
            </Link>
        </div>

        <div
            class="group special-cadre flex w-full flex-col items-center gap-10 rounded-2xl border border-[var(--primary_light)]/20 
            bg-gradient-to-b from-[var(--primary_light)]/10 to-[var(--danger_light)]/5 dark:from-[var(--primary_dark)]/10 dark:to-[var(--danger_dark)]/5 
            px-5 py-8 lg:flex-row lg:gap-30"
        >
            <div class="relative flex w-full items-center overflow-hidden justify-center rounded-xl lg:w-1/2">
                <div class="w-3/4 sm:w-1/2 lg:w-full overflow-hidden rounded-xl">
                    <img
                        :src="featuredProduct.imageUrl"
                        :alt="featuredProduct.name"
                        class="featured-offer-image w-full rounded-xl shadow transition-all duration-300 ease-in-out"
                    />
                </div>
            </div>

            <div class="featured-offer-content flex w-full flex-col gap-8 lg:w-1/2">
                <div class="flex items-center justify-between">
                    <span class="web-bg-danger w-fit rounded-md px-4 py-2 text-sm font-semibold text-white shadow-lg">
                        {{ featuredProduct.discount }}
                    </span>
                </div>

                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-3">
                        <h2 class="web-text-active text-3xl leading-tight font-bold tracking-wide">
                            {{ featuredProduct.name }}
                        </h2>
                        <p class="web-text-body-muted max-w-2xl text-base leading-relaxed">
                            {{ featuredProduct.description }}
                        </p>
                    </div>

                    <div>
                        <p class="text-base font-medium">{{ featuredProduct.category }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4 border-t pt-4">
                    <div class="flex items-center gap-3">
                        <span class="web-text-active text-2xl font-bold">${{ featuredProduct.currentPrice }}</span>
                        <span class="web-text-body-muted text-lg line-through">${{ featuredProduct.originalPrice }}</span>
                    </div>

                    <Button class="web-glow-button">
                        <ShoppingCart class="size-5" />
                        <span>Add to Cart</span>
                    </Button>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.special-cadre:hover .featured-offer-image {
    transform: scale(1.5) !important;
}
</style>
