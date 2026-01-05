<script setup lang="ts">
import StyleLayout from '@/pages/websites/StyleLayout.vue';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onMounted } from 'vue';
import Footer from '../components/AppFooter.vue';
import Navbar from '../components/navbar/AppNavbar.vue';

const props = defineProps<{
    colors: Record<string, string>;
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
    cartItemsCount: number;
}>();

const page = usePage<SharedData>();

gsap.registerPlugin(ScrollTrigger);

onMounted(() => {
    // --- Section Title Underline Animation ---
    gsap.utils.toArray<HTMLElement>('.res-section-title-underline').forEach((title) => {
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
    <StyleLayout :colors="props.colors">
        <Navbar :websiteNameAndLogo="props.websiteNameAndLogo" :cartItemsCount="props.cartItemsCount" />

        <main :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'" class="mx-auto h-full min-h-screen w-full max-w-7xl px-2 md:px-4">
            <slot />
        </main>

        <Footer :websiteNameAndLogo="props.websiteNameAndLogo" :websiteFooterData="props.websiteFooterData" />
    </StyleLayout>
</template>

<style>
/* Gradient Text */
.res-gradient-text {
    background-image:
        linear-gradient(to right, transparent 0%, var(--bg_body_light) 20%, transparent 40%),
        linear-gradient(50deg, var(--primary_light), var(--primary_hover_light), var(--primary_hover_light));
    background-size:
        200% 100%,
        100% 100%;
    background-position:
        -200% 0,
        0 0;
    background-repeat: no-repeat;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: text-shine 3s infinite linear;
}
.dark .res-gradient-text {
    background-image:
        linear-gradient(to right, transparent 0%, var(--bg_body_dark) 20%, transparent 40%),
        linear-gradient(50deg, var(--primary_dark), var(--primary_hover_dark), var(--primary_hover_dark));
    background-size:
        200% 100%,
        100% 100%;
    background-position:
        -200% 0,
        0 0;
    background-repeat: no-repeat;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: text-shine 3s infinite linear;
}
@keyframes text-shine {
    0% {
        background-position:
            200% 0,
            0 0;
    }
    100% {
        background-position:
            -200% 0,
            0 0;
    }
}

/* Glow Button */
.res-glow-button {
    position: relative;
    overflow: hidden;
    z-index: 1;
    transition: all 0.3s ease-in-out;
    background-image: linear-gradient(to right, var(--primary_light) 50%, var(--primary_hover_light) 80%, var(--primary_hover_light) 100%);
}
.dark .res-glow-button {
    position: relative;
    overflow: hidden;
    z-index: 1;
    transition: all 0.3s ease-in-out;
    background-image: linear-gradient(to right, var(--primary_dark) 50%, var(--primary_hover_dark) 80%, var(--primary_hover_dark) 100%);
}
.res-glow-button:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300%;
    height: 300%;
    background: radial-gradient(circle, var(--primary_light), var(--primary_hover_light), transparent, transparent);
    z-index: -1;
    transform: translate(-50%, -50%) scale(0);
    transition: transform 0.8s ease;
    opacity: 0;
}
.dark .res-glow-button:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300%;
    height: 300%;
    background: radial-gradient(circle, var(--primary_dark), var(--primary_hover_dark), transparent, transparent);
    z-index: -1;
    transform: translate(-50%, -50%) scale(0);
    transition: transform 0.8s ease;
    opacity: 0;
}
.res-glow-button:hover:before {
    transform: translate(-50%, -50%);
    opacity: 0.7;
}
.res-glow-button:hover {
    background-image: none;
    background-color: hsl(0, 0%, 40%);
    color: white;
}
.dark .res-glow-button:hover {
    background-image: none;
    background-color: hsl(0, 0%, 5%);
    color: white;
}

/* Title Underline */
.res-section-title-underline {
    position: relative;
    display: inline-block;
    padding-bottom: 8px;
}
.res-section-title-underline::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: var(--underline-width, 0%);
    height: 3px;
    background: linear-gradient(90deg, var(--primary_light), var(--bg_body_light));
    border-radius: 2px;
    transition: width 0.5s ease-out;
}
.dark .res-section-title-underline::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: var(--underline-width, 0%);
    height: 3px;
    background: linear-gradient(90deg, var(--primary_dark), var(--bg_body_dark));
    border-radius: 2px;
    transition: width 0.5s ease-out;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 1px;
    height: 1px;
    scrollbar-width: thin;
    background: transparent !important;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent !important;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: transparent !important;
}
</style>
