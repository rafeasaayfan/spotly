<script setup lang="ts">
// import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ChevronRight, Heart, Pin, ShoppingCart } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const badgeRef = ref<HTMLElement | null>(null);

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

    // Animate the badge with a vibrate effect every 3 seconds
    const vibrate = () => {
        if (!badgeRef.value) return;
        gsap.fromTo(
            badgeRef.value,
            { x: 0 },
            {
                x: 4,
                duration: 0.06,
                repeat: 7,
                yoyo: true,
                ease: 'power1.inOut',
                onComplete: () => {
                    gsap.set(badgeRef.value, { x: 0 });
                },
            },
        );
    };

    setInterval(vibrate, 5000);
});
</script>

<template>
    <section class="relative py-22 flex flex-col gap-8">
        <div class="flex w-full items-center justify-between">
            <h2 class="text-active text-3xl font-bold md:text-4xl section-title-underline">
                Our
                <span class="gradient-text">Products</span> 
            </h2>

            <Link href="#" class="group text-active-link flex items-center gap-0.5 text-sm font-medium">
                <span>See All</span>
                <ChevronRight class="size-4 transition-all duration-300 ease-in-out group-hover:translate-x-1" />
            </Link>
        </div>

        <div class="relative grid grid-cols-1 gap-6 md:gap-10 sm:grid-cols-2 lg:grid-cols-3">
            <!-- <div class="absolute top-0 left-0 h-full w-full opacity-10">
                <div class="flex h-full w-full items-center justify-center">
                    <AppLogoIcon class="size-[55rem]" />
                </div>
            </div> -->

            <Link
                v-for="n in 6"
                :key="n"
                href="#"
                class="product-card-item ethereal-card group relative flex max-h-[30rem] flex-col gap-5 rounded-xl bg-black/5 p-4 backdrop-blur-md transition duration-300 dark:bg-white/5"
            >
                <div class="absolute start-2 -top-1 z-10" ref="badgeRef">
                    <div class="bg-destructive relative flex flex-col rounded-t rounded-b px-1.5 pt-6 pb-3 text-xs text-white shadow">
                        <div class="absolute start-0 -top-1 flex w-full items-center justify-center">
                            <Pin class="size-4 fill-white shadow-xl" />
                        </div>

                        <span>20%</span>
                        <span>OFF</span>
                    </div>
                </div>

                <!-- Product Image -->
                <div class="relative overflow-hidden rounded-xl shadow">
                    <img
                        :src="'/images/img.jpg'"
                        class="w-full min-w-[20rem] rounded-xl bg-center object-cover transition-all duration-300 ease-in-out group-hover:scale-120"
                        alt="Product"
                    />

                    <!-- Quick Actions Overlay -->
                    <div class="absolute top-1 end-1 group-hover:opacity-100 transition-opacity duration-300 gap-4">
                        <Button
                            size="icon"
                            class="bg-transparent backdrop-blur rounded-full hover:bg-white shadow-none"
                        >
                            <Heart class="size-5 text-black" />
                        </Button>
                    </div>

                    <div class="absolute start-3 bottom-2">
                        <span class="text-active-link rounded-full bg-white/90 px-2 py-1 text-base font-bold backdrop-blur"> Samsung </span>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex flex-col gap-3">
                    <div class="flex flex-col">
                        <h3 class="text-active text-lg font-semibold">Nike Air Max 270</h3>
                        <p class="line-clamp-2 text-sm text-gray-500">
                            Experience seamless connectivity and style with this cutting-edge smartwatch.
                        </p>
                    </div>

                    <p class="text-sm font-medium">Men's Running Shoes</p>
                </div>

                <!-- Price and Actions -->
                <div class="flex w-full items-center justify-between border-t border-gray-500/30 pt-2">
                    <div class="flex items-center gap-2">
                        <span class="text-active text-lg font-bold">$120</span>
                        <span class="text-body-muted text-sm line-through">$150</span>
                    </div>

                    <Link
                        href="#"
                    >
                        <Button
                            class="glow-button"
                        >
                            <ShoppingCart class="size-5 text-white" />
                            <span>Add to Cart</span>
                        </Button>
                    </Link>
                </div>
            </Link>
        </div>
    </section>
</template>

