<script setup lang="ts">
import { onMounted } from 'vue';

import { gsap } from 'gsap';

onMounted(() => {
    // --- About Us Content Animation ---
    const aboutContent = document.querySelector('#about-us .about-content');
    if (aboutContent) {
        gsap.from(aboutContent.children, {
            opacity: 0,
            y: 30,
            stagger: 0.2,
            duration: 0.4,
            scrollTrigger: {
                trigger: aboutContent,
                start: 'top 80%',
                toggleActions: 'play none none none',
                once: true,
            },
        });
    }
    // About Us SVG lines animation
    gsap.utils.toArray<SVGElement>('#about-svg-lines .animated-line').forEach((line, i) => {
        gsap.to(line, {
            strokeDashoffset: 0,
            duration: 1.5,
            delay: 0.2 + i * 0.3,
            ease: 'power2.inOut',
            scrollTrigger: {
                trigger: '#about-svg-lines',
                start: 'top 80%',
                toggleActions: 'play none none none',
                once: true,
            },
        });
    });
});
</script>

<template>
    <section id="about-us" class="py-22 px-4">
        <div class="mx-auto max-w-4xl text-center">
            <h2 class="section-title section-title-underline text-active mb-16 text-3xl sm:text-4xl md:text-4xl lg:text-5xl font-bold">About <span class="gradient-text">Us</span></h2>

            <div class="about-content space-y-4 text-lg leading-relaxed text-body-muted">
                <p>
                    At Spotly, we believe that everyone deserves a beautiful and functional online presence without the traditional barriers of cost
                    and complexity. Our mission is to democratize web creation, providing powerful tools that are accessible to entrepreneurs,
                    creators, and small businesses alike.
                </p>
                <p>
                    We are a passionate team of designers, developers, and dreamers dedicated to helping you succeed online. Your vision is our
                    inspiration.
                </p>
            </div>

            <div class="mt-10">
                <svg id="about-svg-lines" viewBox="0 0 200 50" class="mx-auto w-[85%] md:w-3/4 lg:w-1/2" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        id="about-line-1"
                        d="M0 25 Q 50 0, 100 25 T 200 25"
                        stroke="var(--primary)"
                        stroke-opacity="0.6"
                        stroke-width="1"
                        class="animated-line"
                    />
                    <path
                        id="about-line-2"
                        d="M0 30 Q 50 55, 100 30 T 200 30"
                        stroke="var(--destructive)"
                        stroke-opacity="0.4"
                        stroke-width="0.8"
                        class="animated-line"
                        style="animation-delay: 0.2s"
                    />
                </svg>
            </div>
        </div>
    </section>
</template>
