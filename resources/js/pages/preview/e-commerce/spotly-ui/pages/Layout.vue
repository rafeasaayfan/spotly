<script setup lang="ts">
import StyleLayout from '@/pages/websites/StyleLayout.vue';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onMounted, watch, reactive } from 'vue';
import Footer from '../components/AppFooter.vue';
import Navbar from '../components/navbar/AppNavbar.vue';

const props = defineProps<{
    colors: Record<string, string>;
}>();

const page = usePage<SharedData>();

const reactiveColors = reactive({ ...props.colors });

watch(
    () => props.colors,
    (newColors) => {
        // for reset
        Object.keys(reactiveColors).forEach((key) => {
            delete reactiveColors[key];
        });
        Object.assign(reactiveColors, newColors);
    },
    { deep: true, immediate: true }
);

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
    <StyleLayout :colors="reactiveColors">
        <Navbar />

        <section
            :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
            class="h-full min-h-screen w-full"
        >
            <div class="mx-auto max-w-7xl px-4 md:px-10 lg:px-4">
                <slot />
            </div>

            <Footer />
        </section>

    </StyleLayout>
</template>

<style>
.web-gradient-text {
    background: linear-gradient(50deg, var(--primary_light), var(--danger_light)) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
}
.dark .web-gradient-text {
    background: linear-gradient(50deg, var(--primary_dark), var(--danger_dark)) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
}

.web-glow-button {
    position: relative;
    overflow: hidden;
    z-index: 1;
    transition: all 0.3s ease-in-out;
    background-image: linear-gradient(to right, var(--primary_light), var(--danger_light));
}
.dark .web-glow-button {
    background-image: linear-gradient(to right, var(--primary_dark), var(--danger_dark));
}
.web-glow-button:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300%;
    height: 300%;
    background: radial-gradient(circle, var(--primary_light), var(--danger_light), transparent, transparent);
    z-index: -1;
    transform: translate(-50%, -50%) scale(0);
    transition: transform 0.5s ease;
    opacity: 0;
}
.dark .web-glow-button:before {
    background: radial-gradient(circle, var(--primary_dark), var(--danger_dark), transparent, transparent);
}
.web-glow-button:hover:before {
    transform: translate(-50%, -50%);
    opacity: 0.7;
}
.web-glow-button:hover {
    background-image: none;
    background-color: hsl(0, 0%, 40%);
    color: white;
}
.dark .web-glow-button:hover {
    background-image: none;
    background-color: hsl(0, 0%, 5%);
    color: white;
}

.web-section-title-underline {
    position: relative;
    display: inline-block;
    padding-bottom: 8px;
}
.web-section-title-underline::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: var(--underline-width, 0%);
    height: 2px;
    background: linear-gradient(90deg, var(--primary_light), var(--danger_light));
    border-radius: 2px;
    transition: width 0.5s ease-out;
}
.dark .web-section-title-underline::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: var(--underline-width, 0%);
    height: 2px;
    background: linear-gradient(90deg, var(--primary_dark), var(--danger_dark));
    border-radius: 2px;
    transition: width 0.5s ease-out;
}
</style>
