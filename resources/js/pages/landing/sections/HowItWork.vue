<script setup lang="ts">
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onMounted } from 'vue';

onMounted(() => {
    setTimeout(() => {
        const worksSection = document.getElementById('how-it-works');
        if (!worksSection) return;

        // --- Timeline Animation ---
        const timelineLine = document.querySelector('.timeline-line');
        if (timelineLine) {
            gsap.fromTo(
                '.timeline-line',
                {
                    scaleY: 0,
                    transformOrigin: 'top center',
                },
                {
                    scaleY: 1,
                    duration: 1.2,
                    ease: 'power2.inOut',
                    scrollTrigger: {
                        trigger: '.timeline',
                        start: 'top 80%',
                        end: 'bottom 20%',
                        scrub: 1,
                    },
                },
            );
        }

        // --- Step Cards Animation ---
        const cards = gsap.utils.toArray<HTMLElement>('.step-card-container');
        cards.forEach((card, index) => {
            gsap.fromTo(
                card,
                {
                    opacity: 0,
                    y: 60,
                    scale: 0.9,
                },
                {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    duration: 0.3,
                    delay: index * 0.1,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 85%',
                        toggleActions: 'play none none reverse',
                    },
                },
            );
        });

        // Refresh ScrollTrigger to handle any layout changes
        ScrollTrigger.refresh();
    }, 100);
});

const steps = [
    {
        title: 'Choose a Template',
        description: 'Start with a professionally designed template that fits your niche.',
    },
    {
        title: 'Customize Your Design',
        description: 'Personalize colors, fonts, and layouts with our easy-to-use editor.',
    },
    {
        title: 'Add Your Content',
        description: 'Upload images, write text, and integrate your unique brand elements.',
    },
    {
        title: 'Launch & Grow',
        description: 'Publish your site with one click and start reaching your audience.',
    },
];
</script>

<template>
    <section id="how-it-works" class="relative px-4 py-22">
        <div class="mx-auto">
            <div class="w-full mb-16 flex flex-col items-start gap-3">
                <h2 class="section-title section-title-underline text-active text-3xl font-bold sm:text-4xl md:text-4xl lg:text-5xl">
                    How <span class="gradient-text">Spotly</span> Works
                </h2>
                <p class="text-body-muted">The Steps to create your webiste</p>
            </div>

                    <!-- Floating blurred circles -->
        <div class="pointer-events-none absolute inset-0 z-0">
            <span class="floating-circle bg-[var(--primary)] opacity-10 blur-2xl absolute left-10 top-32 w-40 h-40 rounded-full"></span>
            <span class="floating-circle bg-[var(--destructive)] opacity-10 blur-2xl absolute right-24 top-60 w-32 h-32 rounded-full"></span>
            <span class="floating-circle bg-[var(--primary)] opacity-10 blur-2xl absolute left-1/2 bottom-20 w-48 h-48 rounded-full"></span>
        </div>

            <div class="timeline relative min-h-[800px]">
                <!-- Rounded circle -->
                <div class="relative top-0 start-0 z-10 hidden w-fit md:start-1/2 md:block">
                    <p class="-ms-2 h-4 w-4 rounded-full bg-blue-700"></p>
                </div>
                <!-- Timeline line -->
                <div
                    class="timeline-line absolute top-0 left-0 h-full w-0.5 -translate-x-1/2 bg-gradient-to-b from-blue-700 to-red-700 md:left-1/2 shadow-[0_0_16px_2px_rgba(99,102,241,0.2)] animate-glow"
                ></div>

                <!-- Steps container -->
                <div class="steps-container relative space-y-15 md:pt-4">
                    <!-- Step 1 -->
                    <div
                        v-for="(step, index) in steps"
                        :key="index"
                        :class="index % 2 === 0 ? 'md:justify-end' : 'md:justify-start'"
                        class="step-card-container flex items-center justify-center"
                    >
                        <div
                            :class="index % 2 === 0 ? 'step-card-right' : 'step-card-left'"
                            class="step-card relative w-full max-w-md p-9 backdrop-blur hover:-translate-y-2 md:max-w-xl"
                        >
                            <div class="step-content flex flex-col gap-5">
                                <div>
                                    <span class="gradient-text text-5xl font-bold">0{{ index + 1 }}</span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <h3 class="text-active text-2xl font-semibold">{{ step.title }}</h3>
                                    <p class="text-body-muted text-base">{{ step.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.step-card {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #4a5568;
}
.step-card:hover {
    border-color: #6b7280;
}

.step-card-right {
    border-radius: 0rem 1.5rem 1.5rem 5rem;
}

.step-card-left {
    border-radius: 1.5rem 0rem 5rem 1.5rem;
}

/* Mobile layout */
@media (max-width: 767px) {
    .step-card-right {
        border-radius: 0rem 1.5rem 1.5rem 0rem;
    }

    .step-card-left {
        border-radius: 0rem 1.5rem 1.5rem 0rem;
    }
}
</style>
