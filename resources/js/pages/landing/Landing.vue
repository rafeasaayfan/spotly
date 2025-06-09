<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import AboutUs from './sections/AboutUs.vue';
import ContactUs from './sections/ContactUs.vue';
import Hero from './sections/Hero.vue';
import HowItWork from './sections/HowItWork.vue';
import WhySpotly from './sections/WhySpotly.vue';
import GetStarted from './sections/GetStarted.vue';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

onMounted(() => {
    ScrollTrigger.refresh();

    window.addEventListener('resize', () => {
        ScrollTrigger.refresh();
    });
});
</script>

<template>
    <Head title="Welcome to Spotly" />

    <AppLayout>
        <Hero />

        <WhySpotly />

        <HowItWork />

        <GetStarted />

        <AboutUs />

        <ContactUs />
    </AppLayout>
</template>

<style>
:root {
    --subtle-line-dark: #282828;
    --subtle-line-light: #5a5a5a;
}

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
    transform: translate(-50%, -50%) scale(1.1);
    opacity: 0.7;
}
.glow-button:hover {
    transform: scale(1.05) !important;
    background-image: none;
    background-color: transparent;
}
.glow-button:active {
    transform: scale(0.98) !important;
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

.feature-card:hover {
    transform: translateY(-5px) scale(1.02) !important;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3) !important;
}

.animated-line {
    stroke-dasharray: 1000;
    stroke-dashoffset: 1000;
}

/* .pulsating-element class was used previously, now using .pulsating-block for clarity */
/* GSAP directly animates .pulsating-block elements */

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
