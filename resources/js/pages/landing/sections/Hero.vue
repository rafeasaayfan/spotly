<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { gsap } from 'gsap';
import { ChevronDown } from 'lucide-vue-next';
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

    // Website mockup elements pulsing
    gsap.utils.toArray<SVGElement>('.website-element').forEach((elem, i) => {
        gsap.to(elem, {
            opacity: 0.8,
            scale: 1.05,
            transformOrigin: 'center center',
            duration: 2,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
            delay: 0.5 + i * 0.6,
        });
    });

    // Floating cart swimming animation
    gsap.to('#floating-cart', {
        y: 3,
        rotation: 2,
        duration: 2,
        repeat: -1,
        yoyo: true,
        ease: 'sine.inOut',
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
            class="relative z-10 flex min-h-[650px] w-full flex-col items-center justify-center rounded-md backdrop-blur-[2px] lg:px-0 overflow-hiiden"
        >
            <h1 id="hero-title" class="text-active font-bold text-4xl sm:text-5xl md:text-6xl lg:text-7xl mb-6">
                Welcome To <span class="relative gradient-text">Spotly,</span> <br class="hidden md:block" />
                The Website Build Tool
            </h1>
            <p id="hero-subtitle-main" class="mx-auto text-body-muted mb-10 md:max-w-3xl text-center text-sm sm:text-base md:text-lg font-medium">
                Launch your online business in minutes with no upfront costs. Forget about paying hundreds for developers, domains,
                and hosting. With Spotly, you get a complete professional website for just $10/month. Simple, fast, and affordable — start
                your journey to success today!
            </p>
            <Button @click="scrollToSection('#get-started')" id="hero-cta" class="glow-button" size="lg">Get Started Now</Button>

            <div class="absolute bottom-0 flex w-full items-center justify-center pb-8">
                <ChevronDown class="size-5 text-[var(--destructive)] animate-bounce" />
            </div>
        </div>
    </section>

    <div class="absolute top-0 left-0 inset-0 h-full w-full bg-gradient-to-t from-[var(--primary)] via-transparent
    to-transparent z-0 blur-3xl rounded-full pointer-events-none opacity-30 dark:opacity-15"></div>
</template>
