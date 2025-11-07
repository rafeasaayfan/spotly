<script setup lang="ts">
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Headset, Shield, Star, Truck } from 'lucide-vue-next';

const props = defineProps<{
    websiteNameAndLogo: Record<string, string>;
    aboutUs: string;
    aboutUsAr: string;
}>();

const page = usePage<SharedData>();
</script>

<template>
    <section class="about-section relative grid grid-cols-1 gap-12 py-22 lg:grid-cols-2">
        <!-- Main About Content -->
        <div class="flex flex-col">
            <h2 class="w-fit web-text-active eco-section-title-underline mb-5 text-3xl font-bold sm:text-4xl lg:text-5xl">
                {{ $t('ecommerce.about.title') }} <span class="eco-gradient-text">{{ $t('store') }}</span>
            </h2>

            <p class="text-body-muted mb-8 text-base leading-relaxed">
                {{ $t('ecommerce.about.paragraph') }}

                <br v-if="props.aboutUs" />
                <br v-if="props.aboutUs" />

                {{ page.props.lang === 'ar' ? props.aboutUs : props.aboutUsAr }}
            </p>

            <!-- Stats Grid -->
            <div id="stats-section" class="grid gap-6 sm:grid-cols-2">
                <div class="stat-item flex flex-col items-center justify-center gap-2 rounded-md bg-black/1 p-6 dark:bg-white/1 border-b-2 web-border-color">
                    <Shield class="size-8 text-green-600" />
                    <span class="web-text-active">{{ $t('secure.shopping') }}</span>
                </div>
                <div class="stat-item flex flex-col items-center justify-center gap-2 rounded-md bg-black/1 p-6 dark:bg-white/1 border-b-2 web-border-color">
                    <Truck class="size-8 text-yellow-600" />
                    <span class="web-text-active">{{ $t('fast.delivery') }}</span>
                </div>
                <div class="stat-item flex flex-col items-center justify-center gap-2 rounded-md bg-black/1 p-6 dark:bg-white/1 border-b-2 web-border-color">
                    <Star class="size-8 text-blue-600" />
                    <span class="web-text-active">{{ $t('quality.guaranteed') }}</span>
                </div>
                <div class="stat-item flex flex-col items-center justify-center gap-2 rounded-md bg-black/1 p-6 dark:bg-white/1 border-b-2 web-border-color">
                    <Headset class="size-8 text-red-600" />
                    <span class="web-text-active">{{ $t('24/7.support') }}</span>
                </div>
            </div>
        </div>

        <div class="relative">
            <div class="flex h-full w-full items-center justify-center border-b-6 web-border-color overflow-hidden rounded-md bg-black/1 dark:bg-white/1 py-20">
                <img
                    v-if="props.websiteNameAndLogo.dark_logo"
                    class="block size-50 rounded-full dark:hidden"
                    :src="props.websiteNameAndLogo.light_logo"
                />
                <img
                    v-if="props.websiteNameAndLogo.dark_logo"
                    class="hidden size-50 rounded-full dark:block"
                    :src="props.websiteNameAndLogo.dark_logo"
                />

                <p v-if="!props.websiteNameAndLogo.dark_logo && !props.websiteNameAndLogo.dark_logo" class="web-text-active text-xl font-bold">
                    {{ props.websiteNameAndLogo.name }}
                </p>
            </div>
        </div>

        <div
            class="pointer-events-none absolute inset-0 start-0 top-0 z-0 rounded-full bg-gradient-to-br 
            from-transparent to-[var(--primary_light)] dark:to-[var(--primary_dark)] 
            opacity-20 blur-2xl dark:opacity-5"
        ></div>
    </section>
</template>

<style scoped>
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}
</style>
