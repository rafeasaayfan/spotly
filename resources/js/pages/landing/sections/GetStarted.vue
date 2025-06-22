<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

import { CheckCircle, Palette, ShoppingCart, Utensils, CircleHelp } from 'lucide-vue-next';

const scrollContainer = ref<HTMLElement | null>(null);
let direction = 1; // 1 = forward, -1 = backward
let isPaused = false;

const autoScroll = () => {
    if (!scrollContainer.value || isPaused) return;

    const container = scrollContainer.value;
    const maxScroll = container.scrollWidth - container.clientWidth;

    // Reverse direction if we hit start or end
    if (container.scrollLeft >= maxScroll) {
        direction = -1; // Go left
    } else if (container.scrollLeft <= 0) {
        direction = 1; // Go right
    }

    container.scrollLeft += direction;
};

const pauseAutoScroll = () => {
    isPaused = true;
};
const resumeAutoScroll = () => {
    isPaused = false;
};

let intervalId: number | null = null;
onMounted(() => {
    intervalId = window.setInterval(autoScroll, 20);
});
onUnmounted(() => {
    if (intervalId !== null) {
        clearInterval(intervalId);
    }
});

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
    <section id="get-started" class="px-4 py-22">
        <div class="mx-auto">
            <div class="mb-14 flex w-full flex-col items-start gap-3">
                <h2 class="section-title section-title-underline text-active text-3xl font-bold sm:text-4xl md:text-4xl lg:text-5xl">
                    Start Your <span class="gradient-text">Project Now</span>
                </h2>
                <p class="t ext-body-muted max-w-3xl">Choose your project type and get started created your website with Spotly.</p>
            </div>

            <div class="group relative overflow-hidden" @mouseenter="pauseAutoScroll" @mouseleave="resumeAutoScroll">
                <div ref="scrollContainer" class="scrollbar-hide flex space-x-4 overflow-x-auto overflow-y-hidden scroll-smooth p-2">
                    <template v-for="item in props.websiteTypes" :key="item.type">
                        <Link href="/home" v-if="item.is_active" class="cards-landing-animation group/card relative min-w-[380px] rounded-xl">
                            <div
                                class="absolute top-3 end-3 z-10 flex size-10 scale-75 items-center justify-center rounded-lg bg-gradient-to-br from-[var(--primary)] to-[var(--destructive)] opacity-0 transition-all duration-300 group-hover/card:scale-100 group-hover/card:rotate-12 group-hover/card:opacity-100"
                            >
                                <component :is="CheckCircle" class="size-6 text-white" />
                            </div>

                            <div
                                class="text-body border-muted flex h-full w-full flex-col gap-3 rounded-xl border bg-black/4 p-8 text-center backdrop-blur-lg transition-all duration-200 hover:translate-y-[-2px] hover:scale-102 hover:bg-black/6 active:scale-98 dark:bg-white/4 hover:dark:bg-white/6"
                            >
                                <component :is="handleIcon(item.type)" class="mx-auto size-16" />

                                <div class="flex flex-col items-center justify-center gap-2">
                                    <h3 class="text-xl font-semibold">{{ item.type }}</h3>
                                    <p class="text-body-muted text-sm">{{ item.description }}</p>
                                </div>
                            </div>
                        </Link>

                        <div v-else class="cards-landing-animation group/card relative min-w-[380px] rounded-xl">
                            <div
                                class="absolute inset-0 z-10 rounded-lg bg-gradient-to-br from-gray-900/30 to-black/30 backdrop-blur-[1.2px] transition-all duration-300 border border-muted"
                            >
                                <h1 class="text-sm font-medium p-2">Coming Soon</h1>
                            </div>

                            <div
                                class="text-body flex h-full w-full flex-col gap-3 rounded-xl  p-8 text-center backdrop-blur-lg"
                            >
                                <component :is="handleIcon(item.type)" class="mx-auto size-16" />

                                <div class="flex flex-col items-center justify-center gap-2">
                                    <h3 class="text-xl font-semibold">{{ item.type }}</h3>
                                    <p class="text-body-muted text-sm">{{ item.description }}</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
