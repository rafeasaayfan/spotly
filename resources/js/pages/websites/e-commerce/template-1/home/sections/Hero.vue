<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ArrowRightCircle } from 'lucide-vue-next';
import { onMounted } from 'vue';

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger);

    const heroTitle = document.getElementById('hero-title');
    if (heroTitle) {
        const words = heroTitle.textContent?.trim().split(' ');
        if (words) {
            heroTitle.innerHTML = words
                .map(
                    (word) =>
                        `<span class="word inline-block mr-2 overflow-hidden">
                ${[...word].map((char) => `<span class="char inline-block">${char}</span>`).join('')}
            </span>`,
                )
                .join(' ');
        }

        const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

        tl.from('.char', {
            yPercent: 100,
            opacity: 0,
            duration: 1,
            stagger: {
                each: 0.03,
                from: 'start',
            },
        }).from(
            '#hero-subtitle',
            {
                opacity: 0,
                y: 30,
                duration: 1,
            },
            '-=0.8',
        );
    }

    // Animate floating SVG elements
    gsap.to('.floating-svg', {
        y: -15,
        duration: 4,
        ease: 'power2.inOut',
        stagger: 0.3,
        repeat: -1,
        yoyo: true,
    });

    // Animate rotating elements
    gsap.to('.rotating-svg', {
        rotation: 360,
        duration: 25,
        ease: 'none',
        repeat: -1,
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

            const moveX = (x - centerX) / centerX * 5;
            const moveY = (y - centerY) / centerY * 5;

            gsap.to(backgroundImage, {
                x: moveX,
                y: moveY,
                duration: 0.5,
                ease: 'power2.out'
            });
        });

        // Reset position when mouse leaves
        section.addEventListener('mouseleave', () => {
            gsap.to(backgroundImage, {
                x: 0,
                y: 0,
                duration: 0.5,
                ease: 'power2.out'
            });
        });
    }
});
</script>

<template>
    <section class="relative flex items-center justify-center overflow-hidden py-22">
        <!-- Additional decorative elements -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <!-- Floating geometric shapes -->
            <svg class="floating-svg absolute top-20 left-10 h-16 w-16 opacity-20" viewBox="0 0 100 100">
                <polygon points="50,10 90,90 10,90" fill="none" stroke="currentColor" stroke-width="2" class="text-primary" />
            </svg>

            <svg class="floating-svg absolute top-40 right-20 h-12 w-12 opacity-15" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-700" />
            </svg>

            <svg class="floating-svg absolute bottom-32 left-20 h-14 w-14 opacity-25" viewBox="0 0 100 100">
                <rect x="20" y="20" width="60" height="60" fill="none" stroke="currentColor" stroke-width="2" class="text-red-700" />
            </svg>

            <svg class="rotating-svg absolute right-10 bottom-20 h-10 w-10 opacity-30" viewBox="0 0 100 100">
                <polygon points="50,10 61,35 90,35 68,57 79,82 50,65 21,82 32,57 10,35 39,35" fill="currentColor" class="text-primary" />
            </svg>

            <!-- Decorative lines -->
            <svg class="absolute top-1/4 left-0 h-px w-32 opacity-10" viewBox="0 0 128 1">
                <line x1="0" y1="0.5" x2="128" y2="0.5" stroke="currentColor" stroke-width="1" class="text-primary" />
            </svg>

            <svg class="absolute right-0 bottom-1/4 h-px w-32 opacity-10" viewBox="0 0 128 1">
                <line x1="0" y1="0.5" x2="128" y2="0.5" stroke="currentColor" stroke-width="1" class="text-blue-700" />
            </svg>
        </div>

        <div class="relative overflow-hidden z-10 flex flex-col gap-25 rounded-xl bg-black/2 px-5 py-22 text-center backdrop-blur-md dark:bg-white/3">
            <div
                class="parallax-bg absolute inset-0 z-0 h-full w-full rounded-2xl bg-cover bg-center bg-no-repeat opacity-10"
                style="background-image: url('images/bg.jpg')"
                data-speed="0.2"
            ></div>

            <div class="flex flex-col gap-2">
                <h1 class="font-display text-active z-10 text-4xl leading-tight md:text-6xl lg:text-7xl" id="hero-title">
                    Discover Your Next Favorite Product
                </h1>

                <p class="z-10 mx-auto max-w-3xl text-lg md:text-xl leading-relaxed text-body-muted" id="hero-subtitle">
                    Explore our curated collection of premium products designed to enhance your lifestyle and bring joy to your everyday moments.
                </p>
            </div>

            <div class="z-10 flex w-full items-center justify-center">
                <Link
                    href="/shop"
                    class="group bg-primary relative flex w-fit items-center gap-2 overflow-hidden rounded-full px-12 py-3 font-bold !text-white transition-all duration-300 ease-in-out hover:px-18 active:scale-98"
                >
                    <span>Shop Now</span>
                    <div
                        class="absolute end-[1000px] flex h-full items-center justify-center transition-all duration-300 ease-in-out group-hover:end-8"
                    >
                        <ArrowRightCircle class="size-5" />
                    </div>
                </Link>
            </div>
        </div>
    </section>
</template>

<style scoped>
.char {
    display: inline-block;
    transform-origin: center;
}

.word {
    position: relative;
}

.word:hover .char {
    animation: bounce 0.6s ease;
}

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

.rotating-svg {
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.15));
}
</style>
