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
        title: 'landing.how_it_work.step1_title',
        description: 'landing.how_it_work.step1_description',
        class: 'rounded-ss-none rounded-se-[1.5rem] rounded-ee-[1.5rem] rounded-es-none md:rounded-es-[5rem]',
    },
    {
        title: 'landing.how_it_work.step2_title',
        description: 'landing.how_it_work.step2_description',
        class: 'rounded-se-[1.5rem] md:rounded-se-none md:rounded-es-[1.5rem] md:rounded-ss-[1.5rem] rounded-ee-[1.5rem] md:rounded-ee-[5rem]',
    },
    {
        title: 'landing.how_it_work.step3_title',
        description: 'landing.how_it_work.step3_description',
        class: 'rounded-ss-none rounded-se-[1.5rem] rounded-ee-[1.5rem] rounded-es-none md:rounded-es-[5rem]',
    },
    {
        title: 'landing.how_it_work.step4_title',
        description: 'landing.how_it_work.step4_description',
        class: 'rounded-se-[1.5rem] md:rounded-se-none md:rounded-es-[1.5rem] md:rounded-ss-[1.5rem] rounded-ee-[1.5rem] md:rounded-ee-[5rem]',
    },
];
</script>

<template>
    <section id="how-it-works" class="relative px-4 py-22">
        <div class="mx-auto">
            <div class="mb-16 flex w-full flex-col items-start gap-3">
                <h2 class="section-title section-title-underline text-active text-3xl font-bold sm:text-4xl lg:text-5xl">
                    {{ $t('landing.how_it_work.title_part1') }} <span class="gradient-text">{{ $t('landing.how_it_work.title_part2') }}</span> {{ $t('landing.how_it_work.title_part3') }}
                </h2>
                <p class="text-body-muted">{{ $t('landing.how_it_work.subtitle') }}</p>
            </div>

            <!-- Floating blurred circles -->
            <div class="pointer-events-none absolute inset-0 z-0">
                <span class="floating-circle absolute top-32 start-10 size-20 md:size-40 rounded-full bg-[var(--primary)] opacity-20 dark:opacity-10 blur-2xl"></span>
                <span class="floating-circle absolute bottom-20 start-1/2 size-20 md:size-48 rounded-full bg-[var(--primary)] opacity-20 dark:opacity-10 blur-2xl"></span>
            </div>

            <div class="timeline relative min-h-[800px]">
                <!-- Timeline line -->
                <div
                    class="timeline-line animate-glow absolute start-0 top-0 h-full w-0.5 -translate-x-1/2 md:start-1/2
                    bg-gradient-to-b from-[var(--enhancement-color)] via-[var(--primary-hover)] to-[var(--primary-active)]"
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
                        <div :class="step.class" class="step-card relative w-full max-w-md p-9 backdrop-blur hover:-translate-y-2 md:max-w-xl">
                            <div class="step-content flex flex-col gap-5">
                                <div>
                                    <span class="text-active-link text-5xl font-bold">0{{ index + 1 }}</span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <h3 class="text-active text-2xl font-semibold">{{ $t(step.title) }}</h3>
                                    <p class="text-body-muted text-base">{{ $t(step.description) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.step-card {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #4a5568;
}
.step-card:hover {
    border-color: #6b7280;
}
</style>
