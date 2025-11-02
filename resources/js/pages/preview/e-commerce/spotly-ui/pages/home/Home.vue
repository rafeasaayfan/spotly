<script setup lang="ts">
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onMounted, onUnmounted, reactive, watch } from 'vue';
import Layout from '../../pages/Layout.vue';
import Hero from './sections/Hero.vue';
import Products from './sections/Products.vue';
import About from './sections/About.vue';
import Contact from './sections/Contact.vue';
import Categories from './sections/Categories.vue';

const props = defineProps<{
    colors: Record<string, string>;
}>();

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

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger);

    // --- Image Reveal Animation (Adjusted for vertical scroll context) ---
    gsap.utils.toArray<HTMLElement>('.image-reveal-container').forEach((container) => {
        gsap.from(container, {
            clipPath: 'inset(100% 0% 0% 0%)',
            ease: 'power3.out',
            duration: 1.5,
            scrollTrigger: {
                trigger: container,
                start: 'top 80%', // When the top of the container is 80% down the viewport
                toggleActions: 'play none none none',
            },
        });
    });
});

onUnmounted(() => {
    // Clean up GSAP instances to prevent memory leaks
    gsap.globalTimeline.clear();
    ScrollTrigger.getAll().forEach((st) => st.kill());
});
</script>

<template>
    <Layout :colors="reactiveColors">
        <Hero />

        <Categories />
        
        <Products />

        <About />

        <Contact />
    </Layout>
</template>

<style>
.ethereal-card:hover {
    transform: translateY(-10px) !important;
}
</style>