<script setup lang="ts">
import { CircleDollarSign, Shield, Star, TimerOffIcon } from 'lucide-vue-next';

const cards = [
    {
        title: 'Lightning-Fast Setup',
        description: 'Build your website in minutes, not months. Our platform creates stunning designs instantly, saving you time and effort.',
        icon: TimerOffIcon,
        color: 'text-yellow-500',
    },

    {
        title: 'Pure Creative Freedom',
        description: 'No coding required. Focus on your vision, and let Spotly handle the technical complexities.',
        icon: Star,
        color: 'text-blue-500',
    },

    {
        title: 'Cost Effective',
        description: 'Pay just $10/month instead of $300+ upfront plus hosting fees. Get everything you need in one affordable package.',
        icon: CircleDollarSign,
        color: 'text-red-500',
    },

    {
        title: 'Secure & Reliable',
        description: 'Your website is hosted on enterprise-grade infrastructure with 99.9% uptime guarantee and automatic security updates.',
        icon: Shield,
        color: 'text-green-500',
    },
];
</script>

<template>
    <section id="why-spotly" class="relative px-4 py-22">
        <div class="pointer-events-none absolute inset-0 opacity-40">
            <svg width="100%" height="100%" viewBox="0 0 800 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Gold/Yellow Top Right Glow -->
                <radialGradient id="yellow-grad" cx="0" cy="0" r="1" gradientTransform="translate(650 100) scale(130)" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#fbbf24" stop-opacity="0.25" />
                    <stop offset="1" stop-color="#fbbf24" stop-opacity="0" />
                </radialGradient>
                <circle cx="650" cy="100" r="180" fill="url(#yellow-grad)" />
                <!-- Blue Bottom Left Glow -->
                <radialGradient id="blue-grad" cx="0" cy="0" r="1" gradientTransform="translate(200 500) scale(140)" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#3b82f6" stop-opacity="0.18" />
                    <stop offset="1" stop-color="#3b82f6" stop-opacity="0" />
                </radialGradient>
                <circle cx="200" cy="500" r="140" fill="url(#blue-grad)" />
                <!-- Red Accent Top Left -->
                <radialGradient id="red-grad" cx="0" cy="0" r="1" gradientTransform="translate(80 80) scale(90)" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#ef4444" stop-opacity="0.13" />
                    <stop offset="1" stop-color="#ef4444" stop-opacity="0" />
                </radialGradient>
                <circle cx="80" cy="80" r="90" fill="url(#red-grad)" />
                <!-- Green Accent Bottom Right -->
                <radialGradient id="green-grad" cx="0" cy="0" r="1" gradientTransform="translate(720 520) scale(100)" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#22c55e" stop-opacity="0.12" />
                    <stop offset="1" stop-color="#22c55e" stop-opacity="0" />
                </radialGradient>
                <circle cx="720" cy="520" r="100" fill="url(#green-grad)" />
            </svg>
        </div>
        <div class="relative z-10 mx-auto text-center">
            <div class="mb-16 flex w-full flex-col items-center justify-center gap-3">
                <h2 class="section-title section-title-underline text-active font-display text-3xl font-bold sm:text-4xl md:text-4xl lg:text-5xl">
                    Why Choose <span class="gradient-text">Spotly</span>?
                </h2>
                <p class="text-body-muted max-w-3xl text-lg">
                    Spotly is engineered for simplicity and power, offering an unbeatable value proposition. Launch your online venture without
                    breaking the bank or your patience.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <div
                    v-for="card in cards"
                    :key="card.title"
                    class="cards-landing-animation border-muted flex flex-col items-center justify-center gap-6 rounded-md border bg-black/5 p-6 backdrop-blur transition-all duration-300 hover:shadow dark:bg-white/5"
                >
                    <div class="flex flex-col items-center justify-center gap-4">
                        <component :is="card.icon" class="size-15" :class="card.color" />
                        <h3 class="text-active text-xl font-semibold">{{ card.title }}</h3>
                    </div>
                    <div class="md:max-w-3/4">
                        <p class="text-body-muted text-center text-sm">
                            {{ card.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.cards-landing-animation:hover {
    transform: translateY(-4px) scale(1.01) !important;
}
</style>



<!-- <script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ArrowRight, CheckCircle, Zap, Shield, Heart } from 'lucide-vue-next';
</script>

<template>
    <section id="about" class="section-animate py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8">
                    <div>
                        <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                            Why Choose <span class="gradient-text">Spotly</span>
                        </h2>
                        <p class="text-xl text-gray-600 leading-relaxed">
                            We believe everyone deserves a beautiful, professional website. That's why we've created
                            the most intuitive website builder that makes your vision come to life without any technical barriers.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <Zap class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Lightning Fast</h3>
                                <p class="text-gray-600">Build your website in minutes, not hours. Our intuitive drag-and-drop interface makes it effortless.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <Shield class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure & Reliable</h3>
                                <p class="text-gray-600">Your website is hosted on enterprise-grade infrastructure with 99.9% uptime guarantee.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                <Heart class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Customer First</h3>
                                <p class="text-gray-600">24/7 support team ready to help you succeed. We're here for you every step of the way.</p>
                            </div>
                        </div>
                    </div>

                    <Button class="glow-button text-lg px-8 py-4">
                        Learn More
                        <ArrowRight class="ml-2 h-5 w-5" />
                    </Button>
                </div>

                <div class="relative">
                    <div class="relative bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-8 shadow-xl">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <div class="bg-gray-100 px-4 py-3 flex items-center space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="h-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded"></div>
                                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                                    <div class="grid grid-cols-2 gap-4 mt-6">
                                        <div class="h-20 bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg"></div>
                                        <div class="h-20 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                            <CheckCircle class="w-8 h-8 text-white" />
                        </div>
                        <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center shadow-lg">
                            <Zap class="w-6 h-6 text-white" />
                        </div>
                    </div>

                    <div class="absolute -bottom-8 -left-8 bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="text-center">
                            <div class="text-2xl font-bold gradient-text">500+</div>
                            <div class="text-sm text-gray-600">Templates</div>
                        </div>
                    </div>

                    <div class="absolute -top-8 -right-8 bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="text-center">
                            <div class="text-2xl font-bold gradient-text">24/7</div>
                            <div class="text-sm text-gray-600">Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.gradient-text {
    background: linear-gradient(50deg, var(--primary), var(--destructive)) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
}

.glow-button {
    position: relative;
    overflow: hidden;
    z-index: 1;
    transition: all 0.3s ease-in-out;
    background-image: linear-gradient(to right, var(--primary), var(--destructive));
}
.glow-button:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300%;
    height: 300%;
    background: radial-gradient(circle, var(--primary), var(--destructive), transparent, transparent);
    z-index: -1;
    transform: translate(-50%, -50%) scale(0);
    transition: transform 0.8s ease;
    opacity: 0;
}
.glow-button:hover:before {
    transform: translate(-50%, -50%);
    opacity: 0.7;
}
.glow-button:hover {
    background-image: none;
    background-color: transparent;
}
</style> -->



