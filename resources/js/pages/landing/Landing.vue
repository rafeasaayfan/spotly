<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted, watchEffect } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import AboutUs from './sections/AboutUs.vue';
import ContactUs from './sections/ContactUs.vue';
import GetStarted from './sections/GetStarted.vue';
import Hero from './sections/Hero.vue';
import HowItWork from './sections/HowItWork.vue';
import WhySpotly from './sections/WhySpotly.vue';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { toast } from '@/lib/sweetAlert';

gsap.registerPlugin(ScrollTrigger);

onMounted(() => {
    // --- Section Title Underline Animation ---
    gsap.utils.toArray<HTMLElement>('.section-title-underline').forEach((title) => {
        ScrollTrigger.create({
            trigger: title,
            start: 'top 85%',
            onEnter: () => {
                gsap.to(title, {
                    '--underline-width': '60%',
                    duration: 0.4,
                    ease: 'expo.out',
                });
            },
            once: true,
        });
    });

    // --- Landing Cards Animation ---
    gsap.utils.toArray<HTMLElement>('.cards-landing-animation').forEach((card, i) => {
        gsap.from(card, {
            opacity: 0,
            y: 50,
            scale: 0.95,
            duration: 0.3,
            delay: i * 0.15,
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none none',
                once: true,
            },
        });
    });

    ScrollTrigger.refresh();

    window.addEventListener('resize', () => {
        ScrollTrigger.refresh();
    });
});

const props = defineProps<{
    websiteTypes: Record<string, any>;
        flash?: {
        message?: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: 'success', title: message });
    }
});
</script>

<template>
    <Head title="Welcome to Spotly" />

    <AppLayout>
        <Hero />

        <WhySpotly />

        <HowItWork />

        <GetStarted :websiteTypes="props.websiteTypes" />

        <AboutUs />

        <ContactUs />
    </AppLayout>
</template>

<style>
.gradient-text {
    background: linear-gradient(50deg, var(--primary), var(--destructive)) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
}

.glow-button {
    position: relative;
    overflow: hidden;
    z-index: 1;
    transition: all 0.3s ease-in-out;
    background-image: linear-gradient(to right, var(--primary), var(--destructive));
}
.glow-button:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300%;
    height: 300%;
    background: radial-gradient(circle, var(--primary), var(--destructive), transparent, transparent);
    z-index: -1;
    transform: translate(-50%, -50%) scale(0);
    transition: transform 0.8s ease;
    opacity: 0;
}
.glow-button:hover:before {
    transform: translate(-50%, -50%);
    opacity: 0.7;
}
.glow-button:hover {
    background-image: none;
    background-color: transparent;
}

.section-title-underline {
    position: relative;
    display: inline-block;
    padding-bottom: 8px;
}
.section-title-underline::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: var(--underline-width, 0%);
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--destructive));
    border-radius: 2px;
    transition: width 0.5s ease-out;
}

::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    background: #1a1a1a;
}
::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, var(--primary), var(--destructive));
    border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, color-mix(in srgb, var(--primary) 80%, white), color-mix(in srgb, var(--destructive) 80%, white));
}
</style>
