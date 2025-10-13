<script setup lang="ts">
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onUnmounted, watchEffect } from 'vue';
import Layout from '../../pages/Layout.vue';
import Hero from './sections/Hero.vue';
import Products from './sections/Products.vue';
import About from './sections/About.vue';
import Contact from './sections/Contact.vue';
import Categories from './sections/Categories.vue';
import { toast } from '@/lib/sweetAlert';

const props = defineProps<{
    colors: Record<string, string>;
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
    homeSpecialProducts: Record<string, any>;
    homeProducts: Record<string, any>;
    categories: Record<string, any>;
    aboutUs: string;
    cartItemsCount: number;
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info';
        message: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});

onUnmounted(() => {
    gsap.globalTimeline.clear();
    ScrollTrigger.getAll().forEach((st) => st.kill());
});
</script>

<template>
    <Layout :colors="props.colors" :websiteNameAndLogo="props.websiteNameAndLogo" :websiteFooterData="props.websiteFooterData"
        :cartItemsCount="props.cartItemsCount"
    >
        <Hero :webName="websiteNameAndLogo.name" :specialProducts="props.homeSpecialProducts" />

        <Categories :categories="props.categories" />
        
        <Products :products="props.homeProducts" />

        <About :websiteNameAndLogo="props.websiteNameAndLogo" :aboutUs="props.aboutUs" />

        <Contact />
    </Layout>
</template>

<style>

.ethereal-card:hover {
    transform: translateY(-10px) !important;
}
</style>
