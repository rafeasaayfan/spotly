<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ArrowRight, CircleDollarSign, Shield, Star, Zap } from 'lucide-vue-next';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const cards = [
    {
        title: 'Build in Minutes',
        description: 'Launch your professional website in just 5 minutes—no coding, no waiting, no hassle.',
        icon: Zap,
        color: 'yellow-500',
    },
    {
        title: 'Affordable Pricing',
        description: 'Pay only $10–$15/month. Forget about $500+ development costs and $120/year hosting fees.',
        icon: CircleDollarSign,
        color: 'red-500',
    },
    {
        title: 'Full Creative Freedom',
        description: 'Customize your design, colors, and layout to fit your brand—without touching a single line of code.',
        icon: Star,
        color: 'orange-500',
    },
    {
        title: 'Reliable & Secure',
        description: 'Enjoy enterprise-grade hosting, 99.9% uptime, free SSL, and automatic security updates.',
        icon: Shield,
        color: 'green-500',
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
            <div class="mb-16 flex w-full flex-col items-start md:items-center justify-center gap-3">
                <h2 class="section-title section-title-underline text-active font-display text-3xl font-bold sm:text-4xl lg:text-5xl">
                    {{ $t('landing.why_spotly.title_part1') }} <span class="gradient-text">{{ $t('landing.why_spotly.title_part2') }}</span>
                    {{ page.props.lang === 'ar' ? '؟' : '?' }}
                </h2>
            </div>

            <div class="grid grid-cols-1 items-center gap-10 md:gap-16 lg:grid-cols-2">
                <div
                    v-for="(item, index) in cards"
                    :key="index"
                    class="cards-landing-animation group border-muted relative flex flex-col gap-4 rounded-full border-b-3 px-5 pt-5 pb-10 transition-all duration-200 md:px-15"
                >
                    <div class="pointer-events-none absolute inset-0 top-0 left-0 h-full w-full pb-20">
                        <div
                            class="h-full w-1/2 rounded-s-full border-t border-[var(--border-landing)]"
                            :class="{
                                'group-hover:border-red-500': item.color === 'red-500',
                                'group-hover:border-green-500': item.color === 'green-500',
                                'group-hover:border-orange-500': item.color === 'orange-500',
                                'group-hover:border-yellow-500': item.color === 'yellow-500',
                            }"
                        ></div>
                    </div>
                    <div :class="['text-' + item.color, 'flex items-center gap-2']">
                        <component :is="item.icon" class="size-5" />
                        <h3 class="text-lg font-medium">{{ $t(item.title) }}</h3>
                    </div>
                    <p class="text-body-muted text-sm font-bold">
                        {{ $t(item.description) }}
                    </p>
                </div>
            </div>

            <div class="mt-10 flex w-full justify-end">
                <Button @click="scrollToSection('#about-us')" class="glow-button hover:px-4">
                    <span>{{ $t('landing.why_spotly.learn_more_button') }}</span>
                    <ArrowRight class="size-4" :class="page.props.lang === 'ar' ? 'rotate-180' : ''" />
                </Button>
            </div>
        </div>
    </section>
</template>

<style scoped>
.cards-landing-animation:hover {
    transform: translateY(-4px) !important;
}
</style>
