<script setup lang="ts">
import '../../../css/landing.css';

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
        toastType: 'success' | 'error' | 'warning' | 'info',
        message: string,
    }
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
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
