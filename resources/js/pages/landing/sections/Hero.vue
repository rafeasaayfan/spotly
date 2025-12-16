<script setup lang="ts">
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { BadgeDollarSign, ChevronDown, Code, Zap } from 'lucide-vue-next';
import { onMounted } from 'vue';

onMounted(() => {
    // --- Hero Section Animations ---
    gsap.from('#hero-title, #hero-subtitle-main', {
        opacity: 0,
        y: 50,
        stagger: 0.15,
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '#hero-title',
            start: 'top 80%',
            toggleActions: 'play none none none',
        },
    });

    // Mouse move parallax for hero background
    const heroSection = document.getElementById('hero');
    const heroSvg = document.querySelector<SVGElement>('.hero-bg-svg');
    if (heroSection && heroSvg) {
        heroSection.addEventListener('mousemove', (e) => {
            const { clientX, clientY } = e;
            const x = (clientX / window.innerWidth - 0.5) * 20;
            const y = (clientY / window.innerHeight - 0.5) * 20;
            gsap.to(heroSvg, {
                x: -x,
                y: -y,
                duration: 1,
                ease: 'power3.out',
            });
        });
    }
});

const scrollToSection = (id: string) => {
    const el = document.querySelector(id);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
    }
};

const page = usePage<SharedData>();
</script>

<template>
    <section
        id="hero"
        class="relative flex min-h-[650px] w-full flex-col items-center justify-center overflow-hidden px-2 pb-22 text-center sm:px-4 pt-4 xl:pt-15"
    >
        <div
            class="relative z-10 flex min-h-[650px] w-full flex-col items-center justify-center overflow-hidden rounded-md backdrop-blur-[2px] lg:px-0"
        >
            <div class="mb-6 sm:mb-10 flex flex-wrap items-center justify-center gap-3 sm:gap-10">
                <div class="bg-content text-body-muted flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-medium">
                    <Zap class="size-3.5 text-yellow-500" />
                    {{ $t('landing.hero.badge_fast') }}
                </div>
                <div class="bg-content text-body-muted flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-medium">
                    <BadgeDollarSign class="size-3.5 text-red-500" />
                    {{ $t('landing.hero.badge_affordable') }}
                </div>
                <div class="bg-content text-body-muted flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-medium">
                    <Code class="size-3.5 text-blue-500" />
                    {{ $t('landing.hero.badge_no_code') }}
                </div>
            </div>

            <h1 id="hero-title" class="text-active sm:mb-6 mb-4 text-4xl font-bold sm:text-5xl md:text-6xl lg:text-8xl">
                {{ $t('landing.hero.title_part1') }} <span class="gradient-text relative">{{ $t('landing.hero.title_part2') }}</span>
                <br class="hidden md:block" />
                {{ $t('landing.hero.title_part3') }}
            </h1>
            <p id="hero-subtitle-main" class="text-body-muted mx-auto sm:mb-10 mb-6 text-center text-base font-medium 
               md:max-w-3xl md:text-lg leading-relaxed px-4 md:px-0"
            >
                {{ $t('landing.hero.subtitle') }}
            </p>

            <button
                type="button"
                @click="scrollToSection('#get-started')"
                class="group bg-primary glow-button inline-flex cursor-pointer items-center gap-3 rounded-full px-4 sm:px-5 py-3 font-medium 
                text-white transition-all duration-300 hover:px-6 hover:shadow-lg active:scale-98 text-sm sm:text-base"
            >
                <span>{{ $t('landing.hero.get_started_button') }}</span>
                <svg
                    class="size-5 transition-all duration-300 group-hover:translate-x-1"
                    :class="page.props.lang === 'ar' ? 'rotate-180' : ''"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>

            <div class="absolute bottom-0 flex w-full items-center justify-center pb-8">
                <ChevronDown class="text-body-muted size-5 animate-bounce" />
            </div>
        </div>
    </section>

    <div
        class="pointer-events-none absolute inset-0 top-0 left-0 z-0 h-full w-full rounded-full bg-gradient-to-t from-[var(--primary)] via-transparent to-transparent opacity-30 blur-3xl dark:opacity-15"
    ></div>
</template>
