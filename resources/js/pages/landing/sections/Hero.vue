<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { gsap } from 'gsap';
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
    <section id="hero" class="relative flex min-h-[650px] w-full flex-col items-center justify-center overflow-hidden text-center px-4 pt-15 pb-22">
        <svg class="hero-bg-svg absolute top-0 left-0 z-0 h-full w-full overflow-hidden" preserveAspectRatio="xMidYMid slice" viewBox="0 0 100 100">
            <defs>
                <!-- Professional grid pattern -->
                <!-- <pattern id="corporateGrid" width="8" height="8" patternUnits="userSpaceOnUse">
                    <path d="M 8 0 L 0 0 0 8" fill="none" stroke="#282828" stroke-width="0.1" />
                </pattern> -->

                <!-- Subtle glow -->
                <filter id="professionalGlow" x="-20%" y="-20%" width="140%" height="140%">
                    <feGaussianBlur stdDeviation="0.5" result="coloredBlur" />
                    <feMerge>
                        <feMergeNode in="coloredBlur" />
                        <feMergeNode in="SourceGraphic" />
                    </feMerge>
                </filter>
            </defs>

            <g transform="translate(5, 30)" class="hidden md:block">
                <!-- Website frame -->
                <rect
                    class="website-element"
                    x="0"
                    y="0"
                    width="12"
                    height="16"
                    rx="1"
                    fill="none"
                    stroke="var(--primary)"
                    stroke-width="0.2"
                    opacity="0.6"
                />
                <!-- Website header -->
                <rect class="website-element" x="1" y="1" width="10" height="2" rx="0.5" fill="var(--primary)" opacity="0.4" />
                <!-- Website content blocks -->
                <rect class="website-element" x="1" y="4" width="4" height="1" rx="0.2" fill="var(--destructive)" opacity="0.5" />
                <rect class="website-element" x="6" y="4" width="4" height="1" rx="0.2" fill="var(--primary)" opacity="0.5" />
                <rect class="website-element" x="1" y="6" width="10" height="1" rx="0.2" fill="var(--primary)" opacity="0.3" />
            </g>

            <g transform="translate(40, 8)" class="block md:hidden">
                <!-- Website frame -->
                <rect
                    class="website-element"
                    x="0"
                    y="0"
                    width="12"
                    height="16"
                    rx="1"
                    fill="none"
                    stroke="var(--primary)"
                    stroke-width="0.2"
                    opacity="0.6"
                />
                <!-- Website header -->
                <rect class="website-element" x="1" y="1" width="10" height="2" rx="0.5" fill="var(--primary)" opacity="0.4" />
                <!-- Website content blocks -->
                <rect class="website-element" x="1" y="4" width="4" height="1" rx="0.2" fill="var(--destructive)" opacity="0.5" />
                <rect class="website-element" x="6" y="4" width="4" height="1" rx="0.2" fill="var(--primary)" opacity="0.5" />
                <rect class="website-element" x="1" y="6" width="10" height="1" rx="0.2" fill="var(--primary)" opacity="0.3" />
            </g>

            <!-- Background -->
            <rect width="100" height="100" fill="url(#corporateGrid)" />
            <rect width="100" height="100" fill="url(#professionalGradient)" />

            <!-- Data visualization elements -->
            <g transform="translate(15, 60)" class="hidden md:block">
                <rect class="data-bar" x="0" y="0" width="1" height="8" fill="var(--primary)" opacity="0.3" />
                <rect class="data-bar" x="2" y="2" width="1" height="6" fill="var(--destructive)" opacity="0.3" />
                <rect class="data-bar" x="4" y="1" width="1" height="7" fill="var(--primary)" opacity="0.3" />
                <rect class="data-bar" x="6" y="3" width="1" height="5" fill="var(--destructive)" opacity="0.3" />
            </g>

            <g transform="translate(25, 60)" class="block md:hidden">
                <rect class="data-bar" x="0" y="0" width="1" height="8" fill="var(--primary)" opacity="0.3" />
                <rect class="data-bar" x="2" y="2" width="1" height="6" fill="var(--destructive)" opacity="0.3" />
                <rect class="data-bar" x="4" y="1" width="1" height="7" fill="var(--primary)" opacity="0.3" />
                <rect class="data-bar" x="6" y="3" width="1" height="5" fill="var(--destructive)" opacity="0.3" />
            </g>

            <g transform="translate(78, 35)">
                <rect class="data-bar" x="0" y="0" width="1" height="6" fill="var(--destructive)" opacity="0.3" />
                <rect class="data-bar" x="2" y="1" width="1" height="5" fill="var(--primary)" opacity="0.3" />
                <rect class="data-bar" x="4" y="0" width="1" height="7" fill="var(--destructive)" opacity="0.3" />
            </g>

            <!-- Professional geometric elements -->
            <g class="geometric-element" transform="translate(40, 30)">
                <rect x="-1" y="-1" width="2" height="2" fill="none" stroke="var(--foreground)" stroke-width="0.1" opacity="0.2" />
            </g>

            <g class="geometric-element" transform="translate(80, 60)">
                <polygon points="-1,1 0,-1 1,1" fill="none" stroke="var(--foreground)" stroke-width="0.1" opacity="0.2" />
            </g>
        </svg>

        <!-- Content -->
        <div
            class="relative z-10 flex min-h-[650px] w-full flex-col items-center justify-center rounded-md bg-black/3 px-4 backdrop-blur-[2px] lg:px-0 dark:bg-white/2"
        >
            <h1 id="hero-title" class="text-active text-4xl font-bold sm:text-5xl md:text-6xl lg:text-7xl mb-6">
                <span class="gradient-text">Spotly</span> - Build Your <br class="hidden md:block" />
                Digital Presence, Effortlessly
            </h1>
            <p id="hero-subtitle-main" class="mx-auto text-body-muted mb-10 max-w-3xl text-center text-base md:text-xl">
                Start your online business easily with Spotly. No need to pay expensive setup fees or buy a domain and hosting. For only $10/month,
                you get a full, professional website. It’s simple, fast, and saves you money. Begin your journey to success today!
            </p>
            <Button @click="scrollToSection('#get-started')" id="hero-cta" class="glow-button" size="lg">Get Started Now</Button>

            <div class="absolute bottom-0 flex w-full items-center justify-center pb-8">
                <svg
                    class="animate-bounce"
                    fill="var(--foreground-muted)"
                    enable-background="new 0 0 50 50"
                    width="20"
                    version="1.1"
                    viewBox="0 0 50 50"
                >
                    <rect fill="none" height="50" width="50" />
                    <polygon points="47.25,15 45.164,12.914 25,33.078 4.836,12.914 2.75,15 25,37.25 " />
                </svg>
            </div>
        </div>
    </section>
</template>
