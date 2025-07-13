<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/fields';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Mail, Gift, Bell, Sparkles } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const email = ref('');
const isSubscribed = ref(false);

const subscribe = () => {
    if (email.value && email.value.includes('@')) {
        // Implement newsletter subscription logic here
        console.log('Subscribing email:', email.value);
        isSubscribed.value = true;
        email.value = '';
        
        // Reset after 3 seconds
        setTimeout(() => {
            isSubscribed.value = false;
        }, 3000);
    }
};

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger);

    // Animate newsletter content
    gsap.from('.newsletter-content', {
        opacity: 0,
        y: 50,
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.newsletter-section',
            start: 'top 80%',
            toggleActions: 'play none none none',
        },
    });

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
    gsap.from('.benefit-item', {
        opacity: 0,
        x: -30,
        duration: 0.8,
        stagger: 0.2,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.benefits-section',
            start: 'top 80%',
            toggleActions: 'play none none none',
        },
    });
});
</script>

<template>
    <section class="newsletter-section py-22 bg-gradient-to-br from-purple-500/10 via-blue-500/10 to-pink-500/10">
        <div class="container mx-auto px-6">
            <div class="newsletter-content max-w-4xl mx-auto text-center">
                <!-- Floating Icons -->
                <div class="floating-icon absolute top-10 left-10 text-purple-500/30">
                    <Gift class="size-8" />
                </div>
                <div class="floating-icon absolute top-20 right-20 text-blue-500/30">
                    <Bell class="size-6" />
                </div>
                <div class="floating-icon absolute bottom-10 left-20 text-pink-500/30">
                    <Sparkles class="size-7" />
                </div>

                <div class="relative">
                    <h2 class="text-active text-3xl font-bold md:text-4xl mb-6">
                        Stay Updated with Our Newsletter
                    </h2>
                    <p class="text-body-muted text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                        Be the first to know about new products, exclusive offers, and special promotions. 
                        Join our community of savvy shoppers and never miss a deal!
                    </p>

                    <!-- Subscription Form -->
                    <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto mb-8">
                        <div class="flex-1">
                            <Input
                                type="email"
                                v-model="email"
                                placeholder="Enter your email address"
                                class="w-full"
                                :class="{ 'border-green-500': isSubscribed }"
                            />
                        </div>
                        <Button
                            @click="subscribe"
                            class="bg-primary hover:bg-primary/90 text-white px-8 py-2 rounded-lg transition-all duration-300 hover:scale-105"
                            :disabled="isSubscribed"
                        >
                            <Mail class="size-4 mr-2" />
                            {{ isSubscribed ? 'Subscribed!' : 'Subscribe' }}
                        </Button>
                    </div>

                    <!-- Success Message -->
                    <div v-if="isSubscribed" class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 mb-8">
                        <p class="text-green-600 font-medium">
                            🎉 Thank you for subscribing! You'll receive our latest updates soon.
                        </p>
                    </div>

                    <!-- Benefits Section -->
                    <div class="benefits-section grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                        <div class="benefit-item text-center p-6 rounded-xl bg-white/5 backdrop-blur border border-white/10">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <Gift class="size-6 text-white" />
                            </div>
                            <h3 class="text-lg font-semibold text-active mb-2">Exclusive Offers</h3>
                            <p class="text-sm text-body-muted">
                                Get access to subscriber-only discounts and early access to sales
                            </p>
                        </div>

                        <div class="benefit-item text-center p-6 rounded-xl bg-white/5 backdrop-blur border border-white/10">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <Bell class="size-6 text-white" />
                            </div>
                            <h3 class="text-lg font-semibold text-active mb-2">New Arrivals</h3>
                            <p class="text-sm text-body-muted">
                                Be the first to know when new products hit our store
                            </p>
                        </div>

                        <div class="benefit-item text-center p-6 rounded-xl bg-white/5 backdrop-blur border border-white/10">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <Sparkles class="size-6 text-white" />
                            </div>
                            <h3 class="text-lg font-semibold text-active mb-2">Special Events</h3>
                            <p class="text-sm text-body-muted">
                                Get notified about flash sales, seasonal events, and promotions
                            </p>
                        </div>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="mt-8 pt-8 border-t border-white/10">
                        <p class="text-sm text-body-muted mb-4">
                            🔒 We respect your privacy. Unsubscribe at any time.
                        </p>
                        <div class="flex justify-center items-center gap-6 text-xs text-body-muted">
                            <span>✓ No spam</span>
                            <span>✓ Easy unsubscribe</span>
                            <span>✓ Secure & private</span>
                        </div>
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
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.floating-icon {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}
</style> 