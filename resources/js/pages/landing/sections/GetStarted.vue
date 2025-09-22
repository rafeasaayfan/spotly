<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

import { CheckCircle, CircleHelp, Palette, ShoppingCart, Utensils } from 'lucide-vue-next';

const scrollContainer = ref<HTMLElement | null>(null);
const sectionRef = ref<HTMLElement | null>(null);
let direction = 1;
let isPaused = false;
let isVisible = false;
let intervalId: number | null = null;
let delayTimeout: number | null = null;

const autoScroll = () => {
    if (!scrollContainer.value || isPaused || !isVisible) return;

    const container = scrollContainer.value;
    const maxScroll = container.scrollWidth - container.clientWidth;

    if (container.scrollLeft >= maxScroll) {
        direction = -1;
    } else if (container.scrollLeft <= 0) {
        direction = 1;
    }

    container.scrollLeft += direction;
};

const startAutoScroll = () => {
    if (intervalId === null) {
        intervalId = window.setInterval(autoScroll, 20);
    }
};

const stopAutoScroll = () => {
    if (intervalId !== null) {
        clearInterval(intervalId);
        intervalId = null;
    }
    if (delayTimeout !== null) {
        clearTimeout(delayTimeout);
        delayTimeout = null;
    }
};

const handleSectionVisible = () => {
    isVisible = true;
    delayTimeout = window.setTimeout(startAutoScroll, 500); // 0.5 second delay
};

const handleSectionHidden = () => {
    isVisible = false;
    stopAutoScroll();
};

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    handleSectionVisible();
                } else {
                    handleSectionHidden();
                }
            });
        },
        { threshold: 0.4 }
    );

    if (sectionRef.value) {
        observer.observe(sectionRef.value);
    }

    onUnmounted(() => {
        observer.disconnect();
        stopAutoScroll();
    });
});

const pauseAutoScroll = () => {
    isPaused = true;
};

const resumeAutoScroll = () => {
    isPaused = false;
};

const props = defineProps<{
    websiteTypes: Record<string, any>;
}>();

function handleIcon(type: string) {
    switch (type) {
        case 'E-commerce':
            return ShoppingCart;
        case 'Restaurant':
            return Utensils;
        case 'Portfolio':
            return Palette;
        default:
            return CircleHelp;
    }
}
</script>

<template>
    <section ref="sectionRef" id="get-started" class="relative px-4 py-22">
        <div class="mx-auto">
            <div class="mb-14 flex w-full flex-col items-start gap-3">
                <h2 class="section-title section-title-underline text-active text-3xl font-bold sm:text-4xl md:text-4xl lg:text-5xl">
                    {{ $t('landing.get_started.title_part1') }} <span class="gradient-text">{{ $t('landing.get_started.title_part2') }}</span>
                </h2>
                <p class="text-body-muted max-w-3xl">{{ $t('landing.get_started.subtitle') }}</p>
            </div>

            <!-- Floating blurred circles -->
            <div class="pointer-events-none absolute inset-0 z-0">
                <span class="floating-circle absolute top-32 start-10 h-40 w-40 rounded-full bg-[var(--primary)] opacity-20 dark:opacity-10 blur-2xl"></span>
                <!-- <span class="floating-circle absolute top-120 end-24 h-32 w-32 rounded-full bg-black opacity-25 blur-2xl"></span> -->
            </div>

            <div class="group relative overflow-hidden" @mouseenter="pauseAutoScroll" @mouseleave="resumeAutoScroll">
                <div ref="scrollContainer" class="scrollbar-hide flex space-x-4 overflow-x-auto overflow-y-hidden scroll-smooth p-2">
                    <template v-for="item in props.websiteTypes" :key="item.type">
                        <Link
                            :href="`/websiteBuilder?type=${item.type}`"
                            v-if="item.is_active"
                            class="cards-landing-animation group/card relative min-w-[380px] rounded-xl"
                        >
                            <div
                                class="absolute end-3 top-3 z-10 flex size-10 scale-75 items-center justify-center rounded-lg 
                                bg-gradient-to-br from-[var(--primary)] via-[var(--primary-hover)] to-[var(--primary-active)] 
                                opacity-0 transition-all duration-300 group-hover/card:scale-100 group-hover/card:rotate-12 group-hover/card:opacity-100"
                            >
                                <component :is="CheckCircle" class="size-6 text-white" />
                            </div>

                            <div
                                class="text-body border-[var(--border-landing)] flex h-full w-full flex-col gap-3 rounded-xl border bg-black/7 p-8 text-center backdrop-blur-lg 
                                transition-all duration-200 hover:translate-y-[-2px] hover:scale-102 hover:bg-white
                                active:scale-98 dark:bg-white/7 hover:dark:bg-black hover:border-[var(--primary)]"
                            >
                                <component :is="handleIcon(item.title)" class="mx-auto size-16" />

                                <div class="flex flex-col items-center justify-center gap-2">
                                    <h3 class="text-xl font-semibold">{{ $t(item.title) }}</h3>
                                    <p class="text-body-muted text-sm">{{ $t(item.description) }}</p>
                                </div>
                            </div>
                        </Link>

                        <div v-else class="cards-landing-animation group/card relative min-w-[380px] rounded-xl">
                            <div
                                class="border-[var(--border-landing)]/50 absolute inset-0 z-10 rounded-lg border 
                                bg-black/1 backdrop-blur-[1px] transition-all duration-300"
                            >
                                <h1 class="p-2 text-body-muted text-sm font-medium">{{ $t('landing.get_started.coming_soon') }}</h1>
                            </div>

                            <div class="text-body flex h-full w-full flex-col gap-3 rounded-xl p-8 text-center blur-[1px]">
                                <component :is="handleIcon(item.title)" class="mx-auto size-16" />

                                <div class="flex flex-col items-center justify-center gap-2">
                                    <h3 class="text-xl font-semibold">{{ $t(item.title) }}</h3>
                                    <p class="text-body-muted text-sm">{{ $t(item.description) }}</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
