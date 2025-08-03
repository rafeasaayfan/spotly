<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ArrowRightCircle } from 'lucide-vue-next';
import { onMounted } from 'vue';

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger);
    gsap.from('#hero-title, #hero-subtitle', {
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

    // Animate floating SVG elements
    gsap.to('.floating-svg', {
        y: -15,
        duration: 4,
        ease: 'power2.inOut',
        stagger: 0.3,
        repeat: -1,
        yoyo: true,
    });

    // Mouse movement parallax effect for background image
    const section = document.querySelector('section');
    const backgroundImage = document.querySelector('.parallax-bg');

    if (section && backgroundImage) {
        section.addEventListener('mousemove', (e) => {
            const rect = section.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const moveX = ((x - centerX) / centerX) * 5;
            const moveY = ((y - centerY) / centerY) * 5;

            gsap.to(backgroundImage, {
                x: moveX,
                y: moveY,
                duration: 0.5,
                ease: 'power2.out',
            });
        });

        // Reset position when mouse leaves
        section.addEventListener('mouseleave', () => {
            gsap.to(backgroundImage, {
                x: 0,
                y: 0,
                duration: 0.5,
                ease: 'power2.out',
            });
        });
    }
});
</script>

<template>
    <section class="relative flex items-center justify-center overflow-hidden pt-20 pb-22">
        <!-- Background with parallax effect -->
        <div class="absolute end-0 bottom-20">
            <img src="images/websites/e-commerce/cart_hero.png" alt="" class="parallax-bg w-[500px] object-cover opacity-20" data-speed="0.2" />
        </div>

        <div
            class="relative z-10 flex min-h-[650px] w-full flex-col items-center justify-center overflow-hidden rounded-md px-5 text-center backdrop-blur-xs
            bg-[var(--bg_nav_light)]/60 dark:bg-[var(--bg_nav_dark)]/60"
        >
            <!-- Floating decorative elements -->
            <div class="floating-svg absolute start-10 bottom-1 z-5 sm:bottom-8 lg:bottom-20">
                <div
                    class="web-border-color flex size-24 flex-col items-center justify-center gap-1 rounded-full border backdrop-blur transition-all duration-300 ease-in-out hover:scale-105"
                >
                    <div class="web-text-active text-xl font-bold">500+</div>
                    <div class="web-text-body-muted text-xs">Products</div>
                </div>
            </div>
            <div class="floating-svg absolute end-10 top-3 z-5 md:top-20">
                <div
                    class="web-border-color flex size-24 flex-col items-center justify-center gap-1 rounded-full border backdrop-blur transition-all duration-300 ease-in-out hover:scale-105"
                >
                    <div class="web-text-active text-xl font-bold">50+</div>
                    <div class="web-text-body-muted text-xs">Brands</div>
                </div>
            </div>

            <div class="mx-auto mb-10 flex flex-col gap-6">

            </div>

            <div class="z-10 flex w-full items-center justify-center">
                <div>
                    <Button class="web-glow-button group px-14 hover:px-16" size="lg">
                        <span>Shop Now</span>

                        <div
                            class="absolute end-[1000px] flex h-full items-center justify-center transition-all duration-300 ease-in-out group-hover:end-8"
                        >
                            <ArrowRightCircle class="size-5" />
                        </div>
                    </Button>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@keyframes bounce {
    0%,
    20%,
    50%,
    80%,
    100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.floating-svg {
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
}
</style>
