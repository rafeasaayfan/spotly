<script setup lang="ts">
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ChevronRight } from 'lucide-vue-next';
import { onMounted } from 'vue';
import ProductCard from '../../../components/cards/ProductCard.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const props = defineProps<{
    products: Record<string, any>;
}>();

const page = usePage<SharedData>();

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger);

    // Set initial state for cards
    gsap.set('.product-card-item', {
        opacity: 0,
        y: 50,
    });

    // Animate cards into view
    gsap.to('.product-card-item', {
        opacity: 1,
        y: 0,
        stagger: 0.15,
        duration: 0.5,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.product-card-item',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
    });
});
</script>

<template>
    <section v-if="props.products && props.products.length > 0" class="py-22" id="eco-products-section">
        <div class="mb-4 flex w-full items-end justify-between gap-3 border-b-4 border-double web-border-color pb-2">
            <h2 class="web-text-active text-3xl font-bold sm:text-4xl lg:text-5xl">
                {{ $t('our') }} <span class="eco-gradient-text">{{ $t('our.products') }}</span>
            </h2>
            <Link href="/shop" class="group web-text-body flex items-center gap-0.5 text-sm font-medium cursor-pointer">
                <span>{{ $t('seeAll') }}</span>
                <ChevronRight 
                    class="size-4 transition-all duration-300 ease-in-out"
                    :class="page.props.lang === 'ar' ? 'rotate-180 group-hover:-translate-x-1' : 'group-hover:translate-x-1'" 
                />
            </Link>
        </div>

        <div class="relative grid gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            <ProductCard v-for="product in props.products" :key="product.id" :product="product" />
        </div>

        <div class="flex w-full items-center justify-end border-t-4 border-double web-border-color mt-4">
        </div>
    </section>
</template>
