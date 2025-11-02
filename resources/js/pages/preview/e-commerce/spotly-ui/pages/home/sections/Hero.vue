<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollToPlugin } from 'gsap/ScrollToPlugin'; 
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ChevronDown, ChevronRight } from 'lucide-vue-next';
import { nextTick, onMounted, ref } from 'vue';
import SpecialProductCard from '../../../components/cards/SpecialProductCard.vue';
import { SharedData } from '@/types';

const page = usePage<SharedData>();

const specialProducts = [
    {
        id: 1,
        name: 'Stylish Running Shoes',
        short_description: 'Lightweight and comfortable shoes for your daily run.',
        long_description: 'Experience ultimate comfort and style with these running shoes. Featuring a breathable mesh upper and a responsive cushioning system, they are perfect for both casual wear and intense workouts.',
        price: 120.00,
        sale_price: 99.99,
        category: {
            name: 'Footwear',
            ar_name: 'أحذية'
        },
        brand: {
            name: 'Sportly'
        },
        is_special: true,
        in_stock_variants: [
            {
                color: '#FF0000',
                stock_quantity: 50,
                reserved_quantity: 5,
            },
            {
                color: '#0000FF',
                stock_quantity: 30,
                reserved_quantity: 2,
            }
        ]
    },
    {
        id: 2,
        name: 'Smart Fitness Tracker',
        short_description: 'Monitor your health and fitness with this advanced tracker.',
        long_description: 'Stay on top of your health goals with this sleek and smart fitness tracker. It monitors heart rate, steps, sleep, and much more, all while looking great on your wrist.',
        price: 75.00,
        sale_price: 60.00,
        category: {
            name: 'Wearable Tech',
            ar_name: 'تقنية قابلة للارتداء'
        },
        brand: {
            name: 'HealthFit'
        },
        is_special: true,
        in_stock_variants: [
            {
                color: '#000000',
                stock_quantity: 70,
                reserved_quantity: 10,
            },
            {
                color: '#FFFFFF',
                stock_quantity: 40,
                reserved_quantity: 3,
            }
        ]
    },
    {
        id: 3,
        name: 'Premium Wireless Headphones',
        short_description: 'Immersive sound experience with noise-cancellation.',
        long_description: 'Enjoy your music like never before with these premium wireless headphones. Featuring active noise cancellation and crystal-clear audio, they are perfect for travel or daily commutes.',
        price: 200.00,
        sale_price: 180.00,
        category: {
            name: 'Audio',
            ar_name: 'صوتيات'
        },
        brand: {
            name: 'SoundWave'
        },
        is_special: true,
        in_stock_variants: [
            {
                color: '#A9A9A9',
                stock_quantity: 25,
                reserved_quantity: 1,
            },
            {
                color: '#4682B4',
                stock_quantity: 15,
                reserved_quantity: 0,
            }
        ]
    }
];

const carouselRef = ref<HTMLElement | null>(null);
let autoScrollTween: gsap.core.Tween | null = null;
const scrollDuration = 20; 
const autoScrollDelay = 4; 

const startAutoScroll = () => {
    if (!carouselRef.value || specialProducts.length < 2) return;

    const productCount = specialProducts.length;
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
    <section id="hero" class="relative z-5 flex grid min-h-[100vh] w-full grid-cols-5 pt-15 pb-22 backdrop-blur">
        <div class="col-span-5 flex flex-col items-center justify-center gap-3">
            <h1 id="hero-title" class="eco-section-title-underline web-text-active text-3xl font-extrabold tracking-tight sm:text-4xl md:text-6xl">
                {{ $t('landing.hero.title_part1') }} <span class="eco-gradient-text">Ecommerce</span>
            </h1>

            <p id="hero-subtitle-main" class="text-body-muted mx-auto max-w-3xl text-center text-sm md:text-base">
                {{ $t('ecommerce.hero.subtitle') }}
            </p>
        </div>

        <div v-if="specialProducts && specialProducts.length > 0" class="col-span-5">
            <div class="col-span-3 flex flex-col justify-center">
                <div
                    ref="carouselRef"
                    @scroll="handleInteraction"
                    @mousedown="handleInteraction"
                    class="horizontal-carousel-track relative flex gap-15 overflow-x-scroll p-2 md:p-10"
                >
                    <SpecialProductCard
                        v-for="(product, index) in specialProducts"
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

        <div class="absolute bottom-0 flex w-full items-center justify-center pb-12">
            <ChevronDown class="size-5 animate-bounce text-[var(--danger_light)] dark:text-[var(--danger_dark)]" />
        </div>
    </section>
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

