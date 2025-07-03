<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ChevronRight, ShoppingCart } from 'lucide-vue-next';
import { onMounted } from 'vue';

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

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger);

    gsap.from('.featured-offer-image', {
        xPercent: -10,
        opacity: 0,
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.featured-offer-section',
            start: 'top 90%',
            toggleActions: 'play none none none',
        },
    });

    gsap.from('.featured-offer-content > *', {
        opacity: 0,
        y: 30,
        duration: 1.5,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.featured-offer-section',
            start: 'top 90%',
            toggleActions: 'play none none none',
        },
    });
});
</script>

<template>
    <section class="featured-offer-section relative py-22 flex flex-col gap-4">
        <div class="flex w-full items-center justify-between">
            <h2 class="text-active text-3xl font-bold md:text-4xl">Special Offer</h2>

            <Link href="#" class="group text-active-link flex items-center gap-0.5 text-sm font-medium">
                <span>See All</span>
                <ChevronRight class="size-4 transition-all duration-300 ease-in-out group-hover:translate-x-1" />
            </Link>
        </div>

        <div
            class="group special-cadre dark:from-bg-blue-950/40 flex w-full flex-col items-center gap-10 lg:gap-30
            rounded-2xl border border-blue-800/30 bg-gradient-to-b from-blue-950/20 to-black/4 px-5 py-8 lg:flex-row dark:to-white/4"
        >
            <div class="relative w-full overflow-hidden rounded-xl lg:w-1/2
                flex justify-center items-center">
                <img
                    :src="featuredProduct.imageUrl"
                    :alt="featuredProduct.name"
                    class="featured-offer-image w-full shadow rounded-xl transition-all duration-300 ease-in-out"
                />
            </div>

            <div class="featured-offer-content flex w-full flex-col gap-8 lg:w-1/2">
                <div class="flex items-center justify-between">
                    <span class="bg-destructive w-fit rounded-md px-4 py-2 text-sm font-semibold text-white shadow-lg">
                        {{ featuredProduct.discount }}
                    </span>
                </div>

                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-3">
                        <h2 class="font-display text-active text-3xl leading-tight font-bold tracking-wide">
                            {{ featuredProduct.name }}
                        </h2>
                        <p class="text-body-muted max-w-2xl text-base leading-relaxed">
                            {{ featuredProduct.description }}
                        </p>
                    </div>

                    <div>
                        <p class="text-base font-medium">{{ featuredProduct.category }}</p>
                    </div>
                </div>

                <div class="flex flex justify-between items-center gap-4 border-t pt-4">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-bold text-active">${{ featuredProduct.currentPrice }}</span>
                        <span class="text-body-muted text-lg line-through">${{ featuredProduct.originalPrice }}</span>
                    </div>

                    <button
                        class="w-fit bg-primary flex cursor-pointer items-center gap-2 rounded px-4 py-2 font-medium text-white
                        transition-all duration-300 ease-in-out text-sm"
                    >
                        <ShoppingCart class="size-5" />
                        <span>Add to Cart</span>
                    </button>
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
