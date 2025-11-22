<script setup lang="ts">
import { Button } from '@/components/ui/button';
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
</script>

<template>
    <section id="hero" class="relative flex min-h-[650px] w-full flex-col items-center justify-center overflow-hidden text-center px-2 sm:px-4 xl:pt-15 pb-22">
        <div
            class="relative z-10 flex min-h-[650px] w-full flex-col items-center justify-center rounded-md backdrop-blur-[2px] lg:px-0 overflow-hidden"
        >
            <div class="flex items-center flex-wrap justify-center gap-3 sm:gap-10 mb-10"> 
                <div class="px-3.5 py-1.5 rounded-full font-medium bg-content text-body-muted text-xs flex items-center gap-1.5">
                    <Zap class="size-3.5 text-yellow-500" />
                    {{ $t('landing.hero.badge_fast') }}
                </div>
                <div class="px-3.5 py-1.5 rounded-full font-medium bg-content text-body-muted text-xs flex items-center gap-1.5">
                    <BadgeDollarSign class="size-3.5 text-red-500" />
                    {{ $t('landing.hero.badge_affordable') }}
                </div>
                <div class="px-3.5 py-1.5 rounded-full font-medium bg-content text-body-muted text-xs flex items-center gap-1.5">
                    <Code class="size-3.5 text-blue-500" />
                    {{ $t('landing.hero.badge_no_code') }}
                </div>
            </div>   

            <h1 id="hero-title" class="text-active font-bold text-4xl sm:text-5xl md:text-6xl lg:text-8xl mb-6">
                {{ $t('landing.hero.title_part1') }} <span class="relative gradient-text">{{ $t('landing.hero.title_part2') }}</span> <br class="hidden md:block" />
                {{ $t('landing.hero.title_part3') }}
            </h1>
            <p id="hero-subtitle-main" class="mx-auto text-body-muted mb-10 md:max-w-3xl text-center text-sm sm:text-base md:text-lg font-medium">
                {{ $t('landing.hero.subtitle') }}
            </p>
            <Button @click="scrollToSection('#get-started')" id="hero-cta" class="glow-button" size="lg">{{ $t('landing.hero.get_started_button') }}</Button>

            <div class="absolute bottom-0 flex w-full items-center justify-center pb-8">
                <ChevronDown class="size-5 text-[var(--destructive)] animate-bounce" />
            </div>
        </div>
    </section>

    <div class="absolute top-0 left-0 inset-0 h-full w-full bg-gradient-to-t from-[var(--primary)] via-transparent
    to-transparent z-0 blur-3xl rounded-full pointer-events-none opacity-30 dark:opacity-15"></div>
</template>
