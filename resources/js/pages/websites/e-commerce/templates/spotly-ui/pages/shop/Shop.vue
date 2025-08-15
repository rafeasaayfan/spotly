<script setup lang="ts">
import { Button } from '@/components/ui/button';


import Filters from './sections/Filters.vue';
import Layout from '../Layout.vue';
import Products from './sections/Products.vue';


</script>

<template>
    <Layout>
        <section class="flex flex-col gap-8 py-22">
            <div class="min from-primary/30 relative h-[32rem] w-full rounded-3xl bg-gradient-to-br to-sky-400/60">
                <img src="/images/bg3.jpg" alt="Shop Banner" class="absolute inset-0 h-full w-full rounded-3xl bg-center object-cover opacity-10" />

                <div class="z-10 flex h-full w-full flex-col items-center justify-center gap-6 px-4 text-center">
                    <div class="flex flex-col items-center justify-center gap-3">
                        <h1 class="font-display animate-fade-in text-active text-4xl font-extrabold drop-shadow-lg md:text-5xl">
                            Unbelievable Deals. Unbeatable Style.
                        </h1>
                        <p class="animate-fade-in text-body-muted z-10 max-w-2xl text-lg delay-100 md:text-xl">
                            Discover the latest arrivals, exclusive offers, and must-have essentials. Shop now and elevate your everyday.
                        </p>
                    </div>

                    <Button variant="destructive" size="lg" class="z-10"> Shop Now </Button>
                </div>
            </div>

            <div class="grid grid-cols-4">
                <Filters />

                <Products />
            </div>
        </section>
    </Layout>
</template>

<style scoped>
.ethereal-card:hover {
    transform: translateY(-10px) !important;
}

aside input[type='checkbox']:focus {
    outline: 2px solid var(--color-primary);
}
</style>
