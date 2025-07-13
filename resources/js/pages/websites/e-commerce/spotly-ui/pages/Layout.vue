<script setup lang="ts">
import Navbar from '../components/navbar/AppNavbar.vue';
import Footer from '../components/AppFooter.vue';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const page = usePage<SharedData>();

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onMounted } from 'vue';

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
});
</script>

<template>
    <Navbar />

    <main :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'" class="mx-auto h-full min-h-screen w-full max-w-7xl px-4 md:px-10 lg:px-4">
        <slot />
    </main>

    <Footer />
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
    transition: transform 0.5s ease;
    opacity: 0;
}
.glow-button:hover:before {
    transform: translate(-50%, -50%);
    opacity: 0.7;
}
.glow-button:hover {
    background-image: none;
    background-color: hsl(0, 0%, 40%);
    color: white;
}
.dark .glow-button:hover {
    background-image: none;
    background-color: hsl(0, 0%, 5%);
    color: white;
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
    height: 2px;
    background: linear-gradient(90deg, var(--primary), var(--destructive));
    border-radius: 2px;
    transition: width 0.5s ease-out;
}
</style>
