<script setup lang="ts">
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { CircleDollarSign, Shield, Star, Zap } from 'lucide-vue-next';

const cards = [
    {
        title: 'Build in Minutes',
        description: 'Launch your professional website in just 5 minutes—no coding, no waiting, no hassle.',
        icon: Zap,
        color: 'yellow-500',
        border: 'hover:border-yellow-500',
    },
    {
        title: 'Affordable Pricing',
        description: 'Pay only $10–$15/month. Forget about $500+ development costs and $120/year hosting fees.',
        icon: CircleDollarSign,
        color: 'red-500',
        border: 'hover:border-red-500',
    },
    {
        title: 'Full Creative Freedom',
        description: 'Customize your design, colors, and layout to fit your brand—without touching a single line of code.',
        icon: Star,
        color: 'orange-500',
        border: 'hover:border-orange-500',
    },
    {
        title: 'Reliable & Secure',
        description: 'Enjoy enterprise-grade hosting, 99.9% uptime, free SSL, and automatic security updates.',
        icon: Shield,
        color: 'green-500',
        border: 'hover:border-green-500',
    },
];

const scrollToSection = (id: string) => {
    const el = document.querySelector(id);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
    }
};

const page = usePage<SharedData>();
</script>

<template>
    <section id="why-spotly" class="px-4 py-22">
        <div class="relative z-10 mx-auto">
            <div class="mb-16 flex w-full flex-col items-start justify-center gap-3 md:items-center">
                <h2 class="section-title section-title-underline text-active font-display text-3xl font-bold sm:text-4xl lg:text-5xl">
                    {{ $t('landing.why_spotly.title_part1') }} <span class="gradient-text">{{ $t('landing.why_spotly.title_part2') }}</span>
                    {{ page.props.lang === 'ar' ? '؟' : '?' }}
                </h2>
            </div>

            <div class="grid grid-cols-1 items-center gap-7 md:gap-14 lg:grid-cols-2">
                <div
                    v-for="(item, index) in cards"
                    :key="index"
                    class="cards-landing-animation group relative flex flex-col gap-4 border-s-4 sm:py-8 py-6 sm:px-10 px-8
                    transition-all duration-200 bg-black/2 dark:bg-white/2 rounded-md border-[var(--border-landing)]"
                    :class="item.border"
                >
                    <!-- <div class="pointer-events-none absolute inset-0 top-0 left-0 h-full w-full pb-20">
                        <div
                            class="h-full w-1/2 rounded-s-full border-t border-[var(--border-landing)]"
                            :class="{
                                'group-hover:border-red-500': item.color === 'red-500',
                                'group-hover:border-green-500': item.color === 'green-500',
                                'group-hover:border-orange-500': item.color === 'orange-500',
                                'group-hover:border-yellow-500': item.color === 'yellow-500',
                            }"
                        ></div>
                    </div> -->
                    <div :class="['text-' + item.color, 'flex items-center gap-2']">
                        <component :is="item.icon" class="size-5" />
                        <h3 class="text-lg font-medium">{{ $t(item.title) }}</h3>
                    </div>
                    <p class="text-body-muted font-bold">
                        {{ $t(item.description) }}
                    </p>
                </div>
            </div>

            <div class="mt-10 flex w-full justify-end">
                <button
                    type="button"
                    @click="scrollToSection('#about-us')"
                    class="group bg-primary glow-button inline-flex cursor-pointer items-center gap-1.5 rounded-full px-5 py-2.5 text-sm font-medium text-white transition-all duration-300 hover:shadow-lg active:scale-98"
                >
                    <span>{{ $t('landing.why_spotly.learn_more_button') }}</span>
                    <svg
                        class="size-4.5 transition-all duration-300 group-hover:translate-x-1"
                        :class="page.props.lang === 'ar' ? 'rotate-180' : ''"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
        </div>
    </section>
</template>

<style scoped>
.cards-landing-animation:hover {
    transform: translateY(-4px) !important;
}
</style>
