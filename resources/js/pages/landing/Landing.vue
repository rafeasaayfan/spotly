<script setup lang="ts">
import '../../../css/landing.css';

import { Head } from '@inertiajs/vue3';
import { onMounted, ref, watchEffect } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import AboutUs from './sections/AboutUs.vue';
import ContactUs from './sections/ContactUs.vue';
import GetStarted from './sections/GetStarted.vue';
import Hero from './sections/Hero.vue';
import HowItWork from './sections/HowItWork.vue';
import WhySpotly from './sections/WhySpotly.vue';

import { DropdownMenu, DropdownMenuContent, DropdownMenuSeparator, DropdownMenuShortcut, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';

import { Button } from '@/components/ui/button';
import { toast } from '@/lib/sweetAlert';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { MessageSquare, X } from 'lucide-vue-next';
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
        toastType: 'success' | 'error' | 'warning' | 'info';
        message: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});
</script>

<template>
    <Head :title="$t('landing.head_title')" />

    <div class="landing-body">
        <AppLayout>
            <Hero />

            <WhySpotly />

            <HowItWork />

            <GetStarted :websiteTypes="props.websiteTypes" />

            <AboutUs />

            <Newsletter />

            <div class="fixed end-4 bottom-4 z-10 md:end-8 md:bottom-8">
                <DropdownMenu v-model:open="isDropdownOpen">
                    <DropdownMenuTrigger as-child>
                        <Button
                            class="size-14 rounded-full bg-[var(--primary)]/80 p-0 backdrop-blur-[2px] hover:rotate-10 hover:bg-[var(--primary)] sm:size-15"
                            :class="isDropdownOpen ? 'bg-[var(--primary)]' : ''"
                        >
                            <MessageSquare v-if="!isDropdownOpen" class="size-5" />
                            <X v-else class="size-5" />
                        </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent class="h-140 w-70 sm:h-auto sm:w-fit">
                        <DropdownMenuShortcut class="section-title text-active px-2 py-2 text-lg font-bold">
                            {{ $t('landing.contact_dropdown.title_part1') }}
                            <span class="gradient-text">{{ $t('landing.contact_dropdown.title_part2') }}</span>
                        </DropdownMenuShortcut>

                        <DropdownMenuSeparator />

                        <ContactUs />
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </AppLayout>
    </div>
</template>
