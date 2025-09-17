<script setup lang="ts">
import '../../../css/landing.css';

import { Head } from '@inertiajs/vue3';
import { onMounted, watchEffect, ref } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import AboutUs from './sections/AboutUs.vue';
import ContactUs from './sections/ContactUs.vue';
import GetStarted from './sections/GetStarted.vue';
import Hero from './sections/Hero.vue';
import HowItWork from './sections/HowItWork.vue';
import WhySpotly from './sections/WhySpotly.vue';

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { toast } from '@/lib/sweetAlert';
import { MessageSquare, X } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import Newsletter from './sections/Newsletter.vue';

gsap.registerPlugin(ScrollTrigger);

const isDropdownOpen = ref(false);

onMounted(() => {
    // --- Section Title Underline Animation ---
    gsap.utils.toArray<HTMLElement>('.section-title-underline').forEach((title) => {
        ScrollTrigger.create({
            trigger: title,
            start: 'top 85%',
            onEnter: () => {
                gsap.to(title, {
                    '--underline-width': '60%',
                    duration: 0.4,
                    ease: 'expo.out',
                });
            },
            once: true,
        });
    });

    // --- Landing Cards Animation ---
    gsap.utils.toArray<HTMLElement>('.cards-landing-animation').forEach((card, i) => {
        gsap.from(card, {
            opacity: 0,
            y: 50,
            scale: 0.95,
            duration: 0.3,
            delay: i * 0.15,
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none none',
                once: true,
            },
        });
    });

    ScrollTrigger.refresh();

    window.addEventListener('resize', () => {
        ScrollTrigger.refresh();
    });
});

const props = defineProps<{
    websiteTypes: Record<string, any>;
        flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info',
        message: string,
    }
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});
</script>

<template>
    <Head title="Welcome to Spotly" />

    <AppLayout>
        <Hero />

        <WhySpotly />

        <HowItWork />

        <GetStarted :websiteTypes="props.websiteTypes" />

        <AboutUs />

        <Newsletter />

        <div class="fixed bottom-4 end-4 md:bottom-8 md:end-8 z-10">
            <DropdownMenu v-model:open="isDropdownOpen">
                <DropdownMenuTrigger as-child>
                    <Button class="rounded-full h-0 p-0 size-14 sm:size-15 hover:rotate-10 bg-[var(--primary)]/80
                    hover:bg-[var(--primary)] backdrop-blur-[2px]" :class="isDropdownOpen ? 'bg-[var(--primary)]' : ''">
                        <MessageSquare v-if="!isDropdownOpen" class="size-5" />
                        <X v-else class="size-5" />
                    </Button>
                </DropdownMenuTrigger>

                <DropdownMenuContent align="end" class="w-70 h-140 sm:w-fit sm:h-auto">
                    <DropdownMenuShortcut class="px-2 py-2 section-title text-active font-bold text-lg">
                        Get In <span class="gradient-text">Touch</span>
                    </DropdownMenuShortcut>

                    <DropdownMenuSeparator />

                    <ContactUs />
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </AppLayout>
</template>
