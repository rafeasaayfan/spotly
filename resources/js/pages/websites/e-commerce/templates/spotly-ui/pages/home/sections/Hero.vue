<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollToPlugin } from 'gsap/ScrollToPlugin'; 
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ChevronDown, ChevronRight } from 'lucide-vue-next';
import { nextTick, onMounted, ref } from 'vue';
import SpecialProductCard from '../../../components/cards/SpecialProductCard.vue';
import { SharedData } from '@/types';

const props = defineProps<{
    webName: string;
    specialProducts: Record<string, any>;
}>();

const page = usePage<SharedData>();

const carouselRef = ref<HTMLElement | null>(null);
let autoScrollTween: gsap.core.Tween | null = null;
const scrollDuration = 20; 
const autoScrollDelay = 4; 

const startAutoScroll = () => {
    if (!carouselRef.value || props.specialProducts.length < 2) return;

    const productCount = props.specialProducts.length;
    const cardWidth = 350;
    const gapWidth = 32; 
    const scrollWidth = productCount * (cardWidth + gapWidth);

    // Create a loopable auto-scroll animation
    autoScrollTween = gsap.to(carouselRef.value, {
        scrollLeft: scrollWidth,
        duration: scrollDuration,
        ease: 'linear',
        repeat: -1,
        onUpdate: () => {
            if (carouselRef.value && carouselRef.value.scrollLeft >= scrollWidth) {
                carouselRef.value.scrollLeft = 0;
            }
        },
    });

    // Stop auto-scroll after a short delay to encourage manual interaction
    gsap.delayedCall(autoScrollDelay, () => {
        if (autoScrollTween) {
            autoScrollTween.pause();
        }
    });
};

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

    nextTick(() => {
        // --- Hero Section Entrance Animations ---
        gsap.from('#hero-title, #hero-subtitle-main', {
            opacity: 0,
            y: 30,
            stagger: 0.15,
            duration: 1.2,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: '#hero',
                start: 'top 80%',
                toggleActions: 'play none none none',
            },
        });

        // --- Start Auto-Scroll ---
        startAutoScroll();
    });
});

// Handle user interaction to stop the loop
const handleInteraction = () => {
    if (autoScrollTween && autoScrollTween.isActive()) {
        autoScrollTween.pause();
    }
};
</script>

<template>
    <section id="hero" class="relative z-5 flex grid min-h-[100vh] w-full grid-cols-5 pt-28 pb-22 backdrop-blur">
        <div class="col-span-5 flex flex-col items-center justify-center gap-3">
            <h1 id="hero-title" class="eco-section-title-underline web-text-active text-3xl font-extrabold tracking-tight sm:text-4xl md:text-6xl">
                {{ $t('landing.hero.title_part1') }} <span class="eco-gradient-text">{{ props.webName }}</span>
            </h1>

            <p id="hero-subtitle-main" class="text-body-muted mx-auto max-w-3xl text-center text-sm md:text-base">
                {{ $t('ecommerce.hero.subtitle') }}
            </p>
        </div>

        <div v-if="props.specialProducts && props.specialProducts.length > 0" class="col-span-5">
            <div class="col-span-3 flex flex-col justify-center">
                <div
                    ref="carouselRef"
                    @scroll="handleInteraction"
                    @mousedown="handleInteraction"
                    class="horizontal-carousel-track relative flex gap-15 overflow-x-scroll px-2 py-2 md:px-10 md:py-5"
                >
                    <SpecialProductCard
                        v-for="(product, index) in props.specialProducts"
                        :key="`${product.id}-${index}`"
                        :product="product"
                    />
                </div>

                <div class="flex flex-col items-center gap-1">
                    <p class="web-text-body-muted text-center text-xs">{{ $t('scroll.more') }}</p>

                    <Link href="/shop?special=true" class="group web-text-body flex cursor-pointer items-center gap-0.5 text-xs font-medium underline underline-offset-2">
                        <span>{{ $t('special.all') }}</span>
                        <ChevronRight 
                            class="size-3.5 transition-all duration-300 ease-in-out"
                            :class="page.props.lang === 'ar' ? 'rotate-180 group-hover:-translate-x-1' : 'group-hover:translate-x-1'" 
                        />
                    </Link>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 flex w-full items-center justify-center pb-15">
            <ChevronDown class="size-5 animate-bounce text-[var(--danger_light)] dark:text-[var(--danger_dark)]" />
        </div>
    </section>

    <div class="pointer-events-none absolute top-0 left-0 h-full w-full px-5">
        <div class="web-border-color h-full w-full rounded-s-full rounded-e-full border-s-8 border-e-8 border-double"></div>
    </div>
</template>

<style scoped>
.horizontal-carousel-track {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
.horizontal-carousel-track::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}
</style>
