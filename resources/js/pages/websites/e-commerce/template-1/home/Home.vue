<script setup lang="ts">
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onMounted, onUnmounted } from 'vue';
import Layout from '../Layout.vue';
import Hero from './sections/Hero.vue';
import Products from './sections/Products.vue';
import SpecialOffers from './sections/SpecialOffers.vue';
// import About from './sections/About.vue';
import Contact from './sections/Contact.vue';

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
    <Layout>
        <Hero />

        <Products />

        <SpecialOffers />

        <!-- <About /> -->

        <Contact />
    </Layout>


    <!-- <footer class="border-t border-[var(--border)] bg-[var(--background)] py-12">
        <div class="text-secondary container mx-auto px-8 text-center text-sm">
            <p>&copy; 2025 Aura. All rights reserved.</p>
            <div class="mt-4 space-x-6">
                <a href="#" class="transition-colors hover:text-white">Privacy Policy</a>
                <a href="#" class="transition-colors hover:text-white">Terms of Service</a>
                <a href="#" class="transition-colors hover:text-white">Contact</a>
            </div>
        </div>
    </footer> -->
</template>

<style>
.font-display {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    text-transform: uppercase;
}

.ethereal-card:hover {
    transform: translateY(-10px) !important;
}
</style>
