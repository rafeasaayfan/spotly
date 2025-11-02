<script setup lang="ts">
import StyleLayout from '@/pages/websites/StyleLayout.vue';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onMounted, reactive, watch } from 'vue';
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
    { deep: true, immediate: true },
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
        <div>
            <Navbar />

            <main :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'" class="mx-auto h-full w-full max-w-7xl px-2 md:px-10 lg:px-4">
                <slot />
            </main>

            <Footer />
        </div>
    </StyleLayout>
</template>

<style>
/* Gradient Text */
.eco-gradient-text {
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
.dark .eco-gradient-text {
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
.eco-glow-button {
    position: relative;
    overflow: hidden;
    z-index: 1;
    transition: all 0.3s ease-in-out;
    background-image: linear-gradient(to right, var(--primary_light) 50%, var(--primary_hover_light) 80%, var(--primary_hover_light) 100%);
}
.dark .eco-glow-button {
    position: relative;
    overflow: hidden;
    z-index: 1;
    transition: all 0.3s ease-in-out;
    background-image: linear-gradient(to right, var(--primary_dark) 50%, var(--primary_hover_dark) 80%, var(--primary_hover_dark) 100%);
}
.eco-glow-button:before {
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
.dark .eco-glow-button:before {
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
.eco-glow-button:hover:before {
    transform: translate(-50%, -50%);
    opacity: 0.7;
}
.eco-glow-button:hover {
    background-image: none;
    background-color: hsl(0, 0%, 40%);
    color: white;
}
.dark .eco-glow-button:hover {
    background-image: none;
    background-color: hsl(0, 0%, 5%);
    color: white;
}

/* Title Underline */
.eco-section-title-underline {
    position: relative;
    display: inline-block;
    padding-bottom: 8px;
}
.eco-section-title-underline::after {
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
.dark .eco-section-title-underline::after {
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
</style>
