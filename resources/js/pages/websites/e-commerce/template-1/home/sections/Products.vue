<script setup lang="ts">
import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ChevronRight, Pin, ShoppingCart } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const badgeRef = ref<HTMLElement | null>(null);

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger);

    // Fix: Ensure cards are displayed by running GSAP animation after next DOM update
    gsap.utils.toArray<HTMLElement>('.product-card-item').forEach((card) => {
        gsap.from(card, {
            opacity: 0,
            y: 50,
            duration: 1,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: card,
                start: 'top 90%',
                toggleActions: 'play none none none',
            },
        });
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

    setInterval(vibrate, 3500);
});
</script>

<template>
    <section class="relative py-22 flex flex-col gap-4">
        <div class="flex w-full items-center justify-between">
            <h2 class="text-active text-3xl font-bold md:text-4xl">Products</h2>

            <Link href="#" class="group text-active-link flex items-center gap-0.5 text-sm font-medium">
                <span>See All</span>
                <ChevronRight class="size-4 transition-all duration-300 ease-in-out group-hover:translate-x-1" />
            </Link>
        </div>

        <div class="relative grid grid-cols-1 gap-x-10 gap-y-8 md:grid-cols-2 lg:grid-cols-3">
            <div class="absolute top-0 left-0 h-full w-full opacity-10">
                <div class="flex h-full w-full items-center justify-center">
                    <AppLogoIcon class="size-[55rem]" />
                </div>
            </div>

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

                <div class="relative overflow-hidden rounded-xl shadow">
                    <img
                        :src="'/images/img.jpg'"
                        class="w-full min-w-[20rem] rounded-xl bg-center object-cover transition-all duration-300 ease-in-out group-hover:scale-120"
                        alt="Product"
                    />

                    <div class="absolute start-3 bottom-2">
                        <span class="text-active-link rounded-full bg-white/90 px-2 py-1 text-base font-bold backdrop-blur"> Samsung </span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="flex flex-col">
                        <h3 class="text-active text-lg font-semibold">Nike Air Max 270</h3>
                        <p class="line-clamp-2 text-sm text-gray-500">
                            Experience seamless connectivity and style with this cutting-edge smartwatch.
                        </p>
                    </div>

                    <p class="text-sm font-medium">Men's Running Shoes</p>
                </div>

                <div class="flex w-full items-center justify-between border-t border-gray-500/30 pt-3">
                    <div class="flex items-center gap-2">
                        <span class="text-active text-lg font-bold">$120</span>
                        <span class="text-body-muted text-sm line-through">$150</span>
                    </div>

                    <Link
                        href="#"
                        class="bg-primary flex items-center gap-2 rounded px-4 py-2 text-sm text-white shadow transition duration-300 ease-in-out hover:scale-103 active:scale-98"
                    >
                        <ShoppingCart class="size-4" />
                        <span>Add to Cart</span>
                    </Link>
                </div>
            </Link>
        </div>
    </section>
</template>
