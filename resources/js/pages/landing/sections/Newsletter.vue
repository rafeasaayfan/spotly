<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/fields';
import { useForm } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Bell, Gift, LoaderCircle, Mail, Sparkles } from 'lucide-vue-next';
import { onMounted } from 'vue';

// const isSubscribed = ref(false);

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger);

    // Animate newsletter content
    // gsap.from('.newsletter-content', {
    //     opacity: 0,
    //     y: 50,
    //     duration: 1,
    //     ease: 'power2.out',
    //     scrollTrigger: {
    //         trigger: '.newsletter-section',
    //         start: 'top 80%',
    //         toggleActions: 'play none none none',
    //     },
    // });

    // Animate floating elements
    gsap.to('.floating-icon', {
        y: -10,
        duration: 2,
        ease: 'power2.inOut',
        repeat: -1,
        yoyo: true,
        stagger: 0.5,
    });

    // Animate benefits
    // gsap.from('.benefit-item', {
    //     opacity: 0,
    //     x: -30,
    //     duration: 0.8,
    //     stagger: 0.2,
    //     ease: 'power2.out',
    //     scrollTrigger: {
    //         trigger: '.benefits-section',
    //         start: 'top 80%',
    //         toggleActions: 'play none none none',
    //     },
    // });
});

const form = useForm({ email: '' });

const subscribe = () => {
    form.put(route('subscribe'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const cards = [
    {
        title: 'Exclusive Features',
        description: 'Unlock early access to new templates, tools, and premium features before anyone else',
        icon: Gift,
    },
    {
        title: 'Latest Updates',
        description: 'Stay informed about new UI designs, and performance improvements in Spotly',
        icon: Bell,
    },
    {
        title: 'Growth Tips',
        description: 'Receive expert tips, best practices, and offers to help your online business grow faster',
        icon: Sparkles,
    },
];
</script>

<template>
    <section class="newsletter-section px-4 py-22">
        <div class="relative">
            <!-- Floating Icons -->
            <div class="floating-icon absolute top-25 sm:top-15 md:top-10 start-10 text-purple-500/50 dark:text-purple-500/30">
                <Gift class="size-8" />
            </div>
            <div class="floating-icon absolute top-10 end-5 lg:top-25 md:end-10 lg:end-30 text-blue-500/50 dark:text-blue-500/30">
                <Bell class="size-6" />
            </div>
            <div class="floating-icon absolute bottom-10 end-10 md:start-20 text-pink-500/50 dark:text-pink-500/30">
                <Sparkles class="size-7" />
            </div>

            <div class="flex w-full flex-col items-start justify-center gap-3 md:items-center">
                <h2 class="section-title section-title-underline text-active font-display text-3xl font-bold sm:text-4xl lg:text-5xl">
                    Stay Updated with Our <span class="gradient-text">Newsletter</span>
                </h2>
            </div>

            <div class="flex flex-col gap-5">
                <div class="border-muted flex w-full flex-col items-center gap-10 rounded-full 
                border-t-10 border-b-10 md:border-t-0 md:border-b-0 md:border-s-10 md:border-e-10 border-double py-10">
                    <div class="z-5 flex w-full max-w-full md:max-w-4xl items-center justify-center">
                        <form @submit.prevent="subscribe" class="flex w-full md:w-2xl flex-col gap-1 rounded-full backdrop-blur-[4px]">
                            <div class="border-muted flex w-full items-center rounded-md border-4 border-double">
                                <Input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="Enter your email"
                                    autocomplete="email"
                                    required
                                    class="h-9 md:h-12 rounded-none rounded-s border-none bg-transparent"
                                    :class="form.errors.email ? 'border-[var(--destructive)]' : ''"
                                />
                                <Button
                                    type="submit"
                                    size="lg"
                                    :disabled="form.processing"
                                    class="border-muted h-9 md:h-12 text-sm md:text-base rounded-none rounded-e-md border-s bg-transparent hover:bg-[var(--primary)] text-active"
                                >
                                    <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                                    <Mail v-else class="size-4" />
                                    Subscribe
                                </Button>
                            </div>
                            <InputError v-if="form.errors" :message="form.errors.email" />
                        </form>
                    </div>

                    <div class="benefits-section grid max-w-5xl grid-cols-1 gap-5 md:grid-cols-3">
                        <div
                            v-for="(item, index) in cards"
                            :key="index"
                            class="benefit-item border-muted rounded-md border p-4.5 text-center backdrop-blur-[4px]"
                        >
                            <div
                                :class="[
                                    'mx-auto mb-2 flex size-10 items-center justify-center rounded-full bg-gradient-to-br opacity-70',
                                    index === 0
                                        ? 'from-orange-400 to-red-700'
                                        : index === 1
                                          ? 'from-lime-500 to-emerald-600'
                                          : 'from-green-500 to-blue-600',
                                ]"
                            >
                                <component :is="item.icon" class="size-5 text-white" />
                            </div>
                            <h3 class="text-active mb-4 font-semibold">{{ item.title }}</h3>
                            <p class="text-body-muted text-sm">
                                {{ item.description }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border-muted flex flex-col items-start justify-center gap-4 border-t pt-8 md:items-center">
                    <p class="text-body-muted text-sm">🔒 We respect your privacy — unsubscribe anytime.</p>
                    <div class="flex items-center justify-center gap-4 text-xs md:gap-6">
                        <p class="text-body-muted">✓ Only Spotly updates</p>
                        <p class="text-body-muted">✓ Secure & private</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.benefit-item {
    transition: all 0.3s ease;
}

.benefit-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}
.dark .benefit-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 1px 5px rgba(255, 255, 255, 0.1);
}

.floating-icon {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}
</style>
