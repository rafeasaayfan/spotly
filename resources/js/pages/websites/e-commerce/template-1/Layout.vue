<script setup lang="ts">
import Navbar from '@/components/websites/e-commerce/navbar/Navbar.vue';
import Footer from '@/components/websites/e-commerce/Footer.vue';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const page = usePage<SharedData>();
</script>

<template>
    <Navbar />

    <main :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'" class="mx-auto h-full min-h-screen w-full max-w-7xl px-4 md:px-0">
        <slot />
    </main>

    <Footer />
</template>
