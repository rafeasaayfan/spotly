<script setup lang="ts">
import { onMounted } from 'vue';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { CircleDollarSign, Shield, Star, TimerOffIcon } from 'lucide-vue-next';

onMounted(() => {
    // --- Section Title Underline Animation ---
    gsap.utils.toArray<HTMLElement>('.section-title-underline').forEach((title) => {
        ScrollTrigger.create({
            trigger: title,
            start: 'top 85%',
            onEnter: () => {
                gsap.to(title, {
                    '--underline-width': '60%',
                    duration: 0.8,
                    ease: 'expo.out',
                });
            },
            once: true,
        });
    });

    // --- Feature Cards Animation (Why Spotly) ---
    gsap.utils.toArray<HTMLElement>('.feature-card').forEach((card, i) => {
        gsap.from(card, {
            opacity: 0,
            y: 50,
            scale: 0.95,
            duration: 0.6,
            delay: i * 0.15,
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none none',
                once: true,
            },
        });
    });
});

const cards = [
    {
        title: 'Lightning-Fast Setup',
        description: 'Build your website in minutes, not months. Our platform creates stunning designs instantly, saving you time and effort.',
        icon: TimerOffIcon,
        color: 'text-yellow-500',
    },

    {
        title: 'Pure Creative Freedom',
        description: 'No coding required. Focus on your vision, and let Spotly handle the technical complexities.',
        icon: Star,
        color: 'text-blue-500',
    },

    {
        title: 'Cost Effective',
        description: 'Pay just $10/month instead of $300+ upfront plus hosting fees. Get everything you need in one affordable package.',
        icon: CircleDollarSign,
        color: 'text-red-500',
    },

    {
        title: 'Secure & Reliable',
        description: 'Your website is hosted on enterprise-grade infrastructure with 99.9% uptime guarantee and automatic security updates.',
        icon: Shield,
        color: 'text-green-500',
    },
];
</script>

<template>
    <section id="why-spotly" class="py-22 px-4">
        <div class="mx-auto text-center">
            <div class="w-full flex flex-col items-center justify-center gap-4 mb-14">
                <h2 class="section-title section-title-underline text-active text-3xl sm:text-4xl md:text-4xl lg:text-5xl font-bold">
                    Why Choose <span class="gradient-text">Spotly</span>?
                </h2>
                <p class="max-w-3xl">
                    Spotly is engineered for simplicity and power, offering an unbeatable value proposition. Launch your online venture without
                    breaking the bank or your patience.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <div
                    v-for="card in cards"
                    :key="card.title"
                    class="feature-card border-muted flex flex-col items-center justify-center gap-6 rounded-md border bg-black/5 p-6 backdrop-blur transition-all duration-300 dark:bg-white/5"
                >
                    <div class="flex flex-col items-center justify-center gap-4">
                        <component :is="card.icon" class="size-15" :class="card.color" />

                        <h3 class="text-active text-xl font-semibold">{{ card.title }}</h3>
                    </div>

                    <div class="md:max-w-3/4">
                        <p class="text-body-muted text-center text-sm">
                            {{ card.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
