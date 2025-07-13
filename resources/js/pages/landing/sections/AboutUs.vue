<script setup lang="ts">
import { onMounted } from 'vue';
import { gsap } from 'gsap';
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';

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
</script>

<template>
    <section id="about-us" class="py-22 px-4 relative overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="floating-element absolute top-20 left-10 w-4 h-4 bg-primary rounded-full"></div>
            <div class="floating-element absolute top-40 right-20 w-6 h-6 bg-destructive rounded-full"></div>
            <div class="floating-element absolute bottom-20 left-1/4 w-3 h-3 bg-primary rounded-full"></div>
        </div>

        <div class="mx-auto max-w-7xl">
            <!-- Header Section -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Trusted by 10,000+ creators
                </div>

                <h2 class="section-title text-active mb-3 text-4xl sm:text-5xl md:text-6xl font-bold leading-tight">
                    About <span class="gradient-text relative">
                        Us
                        <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 100 20" fill="none">
                            <path d="M0 10 Q 25 0, 50 10 T 100 10" stroke="var(--primary)" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h2>

                <p class="text-base text-body-muted max-w-3xl mx-auto leading-relaxed">
                    We're revolutionizing web creation by making powerful tools accessible to everyone.
                    Your vision, our expertise, together we build something extraordinary.
                </p>
            </div>

            <!-- Main Content -->
            <div class="about-content">
                <!-- Mission Statement -->
                <div class="bg-gradient-to-br from-[var(--primary)]/5 to-[var(--destructive)]/5 rounded-3xl p-8 md:px-12 md:py-16 mb-16 border border-[var(--primary)]/10">
                    <div class="grid md:grid-cols-2 gap-8 items-center">
                        <div>
                            <h3 class="text-2xl md:text-3xl font-bold text-active mb-6">
                                Our Mission
                            </h3>
                            <p class="text-base text-body-muted leading-relaxed mb-6">
                                At Spotly, we believe that everyone deserves a beautiful and functional online presence without the traditional barriers of cost and complexity. Our mission is to democratize web creation, providing powerful tools that are accessible to entrepreneurs, creators, and small businesses alike.
                            </p>
                            <p class="text-base text-body-muted leading-relaxed">
                                We are a passionate team of designers, developers, and dreamers dedicated to helping you succeed online. Your vision is our inspiration.
                            </p>
                        </div>
                        <div class="relative">
                            <div class="w-full h-64 bg-black/1 dark:bg-white/1 rounded-xl flex items-center justify-center">
                                <AppLogoIcon class="w-40 h-40" />
                            </div>
                            <div class="absolute -top-4 -right-4 w-8 h-8 bg-destructive rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Section -->
                <div id="stats-section" class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
                    <div class="stat-item text-center p-6 bg-black/2 dark:bg-white/2 rounded-lg border border-[var(--primary)]/10">
                        <div class="text-3xl md:text-4xl font-bold text-primary mb-2">10K+</div>
                        <div class="text-sm text-body-muted">Happy Users</div>
                    </div>
                    <div class="stat-item text-center p-6 bg-black/2 dark:bg-white/2 rounded-lg border border-[var(--destructive)]/20">
                        <div class="text-3xl md:text-4xl font-bold text-destructive mb-2">500+</div>
                        <div class="text-sm text-body-muted">Websites Built</div>
                    </div>
                    <div class="stat-item text-center p-6 bg-black/2 dark:bg-white/2 rounded-lg border border-[var(--primary)]/10">
                        <div class="text-3xl md:text-4xl font-bold text-primary mb-2">24/7</div>
                        <div class="text-sm text-body-muted">Support</div>
                    </div>
                    <div class="stat-item text-center p-6 bg-black/2 dark:bg-white/2 rounded-lg border border-[var(--destructive)]/20">
                        <div class="text-3xl md:text-4xl font-bold text-destructive mb-2">99%</div>
                        <div class="text-sm text-body-muted">Satisfaction</div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="text-center mt-10">
                    <Link href="/WebsiteBuilder" class="group inline-flex items-center gap-3 cursor-pointer px-5 py-3 bg-gradient-to-r from-[var(--primary)] to-[var(--destructive)] text-white rounded-full font-semibold hover:shadow-lg transition-all duration-300 transform hover:px-6 active:scale-98">
                        <span>Start Building Today</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
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
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}
</style>
