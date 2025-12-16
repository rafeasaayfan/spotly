<script setup lang="ts">
import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { onMounted } from 'vue';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

onMounted(() => {
    // --- About Us Content Animation ---
    const aboutContent = document.querySelector('#about-us .about-content');
    if (aboutContent) {
        gsap.from(aboutContent.children, {
            opacity: 0,
            y: 30,
            stagger: 0.2,
            duration: 0.6,
            scrollTrigger: {
                trigger: aboutContent,
                start: 'top 80%',
                toggleActions: 'play none none none',
                once: true,
            },
        });
    }

    // Stats animation
    const stats = document.querySelectorAll('.stat-item');
    stats.forEach((stat, index) => {
        gsap.from(stat, {
            opacity: 0,
            scale: 0.8,
            duration: 0.8,
            delay: 0.3 + index * 0.2,
            scrollTrigger: {
                trigger: '#stats-section',
                start: 'top 80%',
                toggleActions: 'play none none none',
                once: true,
            },
        });
    });

    // Cards animation
    const cards = document.querySelectorAll('.feature-card');
    cards.forEach((card, index) => {
        gsap.from(card, {
            opacity: 0,
            y: 50,
            duration: 0.8,
            delay: 0.1 + index * 0.2,
            scrollTrigger: {
                trigger: '#features-section',
                start: 'top 80%',
                toggleActions: 'play none none none',
                once: true,
            },
        });
    });

    // Floating elements animation
    gsap.to('.floating-element', {
        y: -20,
        duration: 3,
        repeat: -1,
        yoyo: true,
        ease: 'power2.inOut',
        stagger: 0.5,
    });
});

const page = usePage<SharedData>();
</script>

<template>
    <section id="about-us" class="relative overflow-hidden px-4 py-22">
        <!-- Background decorative elements -->
        <!-- <div class="pointer-events-none absolute inset-0">
            <div class="floating-element bg-primary absolute top-20 left-10 h-4 w-4 rounded-full"></div>
            <div class="floating-element bg-destructive absolute top-40 right-20 h-6 w-6 rounded-full"></div>
            <div class="floating-element bg-primary absolute bottom-20 left-1/4 h-3 w-3 rounded-full"></div>
        </div> -->

        <div class="mx-auto max-w-7xl">
            <!-- Header Section -->
            <div class="mb-16 text-center">
                <!-- <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Trusted by 10,000+ creators
                </div> -->

                <h2 class="section-title text-active section-title-underline mb-3 text-4xl leading-tight font-bold sm:text-5xl md:text-6xl">
                    {{ $t('landing.about_us.title_part1') }} <span class="gradient-text relative"> {{ $t('landing.about_us.title_part2') }} </span>
                </h2>

                <p class="text-body-muted mx-auto max-w-3xl text-base leading-relaxed">
                    {{ $t('landing.about_us.subtitle') }}
                </p>
            </div>

            <!-- Main Content -->
            <div class="about-content">
                <!-- Mission Statement -->
                <div class="relative mb-16">
                    <!-- <div
                        class="pointer-events-none absolute inset-0 start-0 top-0 z-0 rounded-full bg-gradient-to-br from-[var(--primary)] via-transparent to-[var(--background)] opacity-20 blur-2xl dark:opacity-5"
                    ></div> -->

                    <div class="grid lg:grid-cols-2 gap-20">
                        <div class="flex flex-col gap-10">
                            <div class="flex flex-col gap-5">
                                <h3 class="text-active mb-1 text-2xl font-bold md:text-3xl">{{ $t('landing.about_us.mission_title') }}</h3>
                                <p class="text-body-muted text-base leading-relaxed">
                                    {{ $t('landing.about_us.mission_paragraph1') }}
                                </p>
                                <p class="text-body-muted text-base leading-relaxed">
                                    {{ $t('landing.about_us.mission_paragraph2') }}
                                </p>
                            </div>

                            <!-- Stats Section -->
                            <div id="stats-section" class="grid sm:grid-cols-2 gap-6">
                                <div class="stat-item rounded-md border-b-4 border-[var(--primary)]/20 dark:border-[var(--primary)]/10 bg-black/1 p-6 text-center dark:bg-white/1">
                                    <div class="text-active mb-2 text-3xl font-bold md:text-4xl">10K+</div>
                                    <div class="text-body text-sm font-medium opacity-70">{{ $t('landing.about_us.happy_users') }}</div>
                                </div>
                                <div class="stat-item rounded-md border-b-4 border-[var(--destructive)]/30 dark:border-[var(--destructive)]/20 bg-black/1 p-6 text-center dark:bg-white/1">
                                    <div class="text-active mb-2 text-3xl font-bold md:text-4xl">500+</div>
                                    <div class="text-body text-sm font-medium opacity-70">{{ $t('landing.about_us.websites_built') }}</div>
                                </div>
                                <div class="stat-item rounded-md border-b-4 border-[var(--primary)]/20 dark:border-[var(--primary)]/10 bg-black/1 p-6 text-center dark:bg-white/1">
                                    <div class="text-active mb-2 text-3xl font-bold md:text-4xl">24/7</div>
                                    <div class="text-body text-sm font-medium opacity-70">{{ $t('landing.about_us.support') }}</div>
                                </div>
                                <div class="stat-item rounded-md border-b-4 border-[var(--destructive)]/30 dark:border-[var(--destructive)]/20 bg-black/1 p-6 text-center dark:bg-white/1">
                                    <div class="text-active mb-2 text-3xl font-bold md:text-4xl">99%</div>
                                    <div class="text-body text-sm font-medium opacity-70">{{ $t('landing.about_us.satisfaction') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="relative h-full">
                            <div class="flex h-full w-full items-center justify-center rounded-md bg-black/3 dark:bg-white/3 py-5 lg:py-0 border-b-6 border-muted">
                                <AppLogoIcon class="size-40 md:size-50" />
                            </div>
                            <div class="bg-primary absolute -top-3 -end-3 flex size-8 items-center justify-center rounded-full">
                                <svg class="size-4.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="mt-8 text-center">
                    <Link
                        href="/website-builder"
                        class="group bg-primary inline-flex cursor-pointer items-center gap-3 rounded-full glow-button 
                        px-5 py-3 font-medium text-white transition-all duration-300 hover:px-6 hover:shadow-lg active:scale-98"
                    >
                        <span>{{ $t('landing.about_us.start_building_button') }}</span>
                        <svg
                            class="size-5 transition-all duration-300 group-hover:translate-x-1"
                            :class="page.props.lang === 'ar' ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.floating-element {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}
</style>
