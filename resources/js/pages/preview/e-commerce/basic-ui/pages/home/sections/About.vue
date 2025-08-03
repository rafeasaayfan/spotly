<script setup lang="ts">
import { Heart, Layers, MapPin, Shield, ShoppingBag, Star, Tag, Truck, Users } from 'lucide-vue-next';
</script>

<template>
    <section class="py-22">
        <!-- Main About Content -->
        <div class="mb-16 grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            <div>
                <h2 class="web-text-active web-section-title-underline mb-6 text-3xl font-bold md:text-4xl">
                    About Our
                    <span class="web-gradient-text">Store</span>
                </h2>
                <p class="web-text-body-muted mb-8 text-lg leading-relaxed">
                    We are passionate about bringing you the best products from around the world. Our carefully curated collection features premium
                    brands and unique items that enhance your lifestyle and bring joy to your everyday moments. With over 10 years of experience in
                    e-commerce, we've built a reputation for quality, reliability, and exceptional customer service. Our team works tirelessly to
                    ensure every shopping experience is seamless and enjoyable.
                </p>

                <div class="flex flex-wrap gap-6">
                    <div class="web-text-body-muted flex items-center gap-1 text-sm">
                        <Shield class="size-4 text-green-500" />
                        <span>Secure Shopping</span>
                    </div>
                    <div class="web-text-body-muted flex items-center gap-1 text-sm">
                        <Truck class="size-4 text-blue-500" />
                        <span>Fast Delivery</span>
                    </div>
                    <div class="web-text-body-muted flex items-center gap-1 text-sm">
                        <Star class="size-4 text-yellow-500" />
                        <span>Quality Guaranteed</span>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="relative overflow-hidden rounded-2xl">
                    <img src="/images/img.jpg" alt="Our Store" class="h-[400px] w-full rounded-2xl object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>

                <!-- Floating stats card -->
                <div class="absolute -bottom-6 start-6 md:-start-6 rounded-md border-s border-b border-[var(--primary_light)]/50 p-2 
                bg-[var(--bg_body_light)]/98 dark:bg-[var(--bg_body_dark)]/98">
                    <div class="flex items-center gap-2">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br 
                            from-[var(--primary_light)] to-[var(--danger_light)] dark:from-[var(--primary_dark)] dark:to-[var(--danger_dark)]"
                        >
                            <MapPin class="size-5 text-white" />
                        </div>
                        <div>
                            <div class="web-text-active text-base font-bold">123 Business Street</div>
                            <div class="web-text-body-muted text-xs">New York, NY 10001</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid mb-16 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="stat-card rounded-xl border web-bg-card p-6 text-center backdrop-blur
            border-[var(--bg_body_dark)]/10  dark:border-[var(--bg_body_light)]/10">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600">
                    <ShoppingBag class="size-6 text-white" />
                </div>
                <div class="web-text-active mb-2 text-2xl font-bold">500+</div>
                <div class="web-text-body-muted text-sm">Products</div>
            </div>

            <div class="stat-card rounded-xl border web-bg-card p-6 text-center backdrop-blur 
            border-[var(--bg_body_dark)]/10 dark:border-[var(--bg_body_light)]/10">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-blue-600">
                    <Layers class="size-6 text-white" />
                </div>
                <div class="web-text-active mb-2 text-2xl font-bold">25+</div>
                <div class="web-text-body-muted text-sm">Brands</div>
            </div>

            <div class="stat-card rounded-xl border web-bg-card p-6 text-center backdrop-blur 
            border-[var(--bg_body_dark)]/10 dark:border-[var(--bg_body_light)]/10">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-pink-600">
                    <Tag class="size-6 text-white" />
                </div>
                <div class="web-text-active mb-2 text-2xl font-bold">15+</div>
                <div class="web-text-body-muted text-sm">Categories</div>
            </div>

            <div class="stat-card rounded-xl border web-bg-card p-6 text-center backdrop-blur 
            border-[var(--bg_body_dark)]/10 dark:border-[var(--bg_body_light)]/10">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-red-600">
                    <Heart class="size-6 text-white" />
                </div>
                <div class="web-text-active mb-2 text-2xl font-bold">98%</div>
                <div class="web-text-body-muted text-sm">Satisfaction</div>
            </div>
        </div>

        <!-- Values Section -->
        <div class="values-section">
            <h3 class="web-text-active mb-5 text-center text-3xl font-bold">
                Our
                <span class="web-gradient-text">Values</span>
            </h3>
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div
                    class="value-card group rounded-xl border web-bg-card p-6 backdrop-blur transition-all duration-300 border-[var(--bg_body_dark)]/10 dark:border-[var(--bg_body_light)]/10"
                >
                    <div
                        class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 transition-transform duration-300 group-hover:scale-110"
                    >
                        <Shield class="h-6 w-6 text-white" />
                    </div>
                    <h4 class="web-text-active mb-3 text-xl font-semibold">Quality Assurance</h4>
                    <p class="web-text-body-muted text-sm leading-relaxed">
                        Every product in our store undergoes rigorous quality checks to ensure you receive only the best items that meet our high
                        standards.
                    </p>
                </div>

                <div
                    class="value-card group rounded-xl border web-bg-card p-6 backdrop-blur transition-all duration-300 border-[var(--bg_body_dark)]/10 dark:border-[var(--bg_body_light)]/10"
                >
                    <div
                        class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-green-500 to-blue-600 transition-transform duration-300 group-hover:scale-110"
                    >
                        <Users class="h-6 w-6 text-white" />
                    </div>
                    <h4 class="web-text-active mb-3 text-xl font-semibold">Customer First</h4>
                    <p class="web-text-body-muted text-sm leading-relaxed">
                        Your satisfaction is our priority. We provide exceptional customer service and support to ensure your shopping experience is
                        always positive.
                    </p>
                </div>

                <div
                    class="value-card group rounded-xl border web-bg-card p-6 backdrop-blur transition-all duration-300  border-[var(--bg_body_dark)]/10 dark:border-[var(--bg_body_light)]/10"
                >
                    <div
                        class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 transition-transform duration-300 group-hover:scale-110"
                    >
                        <Truck class="h-6 w-6 text-white" />
                    </div>
                    <h4 class="web-text-active mb-3 text-xl font-semibold">Fast Delivery</h4>
                    <p class="web-text-body-muted text-sm leading-relaxed">
                        We offer fast and reliable delivery to ensure you receive your products as soon as possible.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.value-card {
    transition: all 0.3s ease;
}

.value-card:hover {
    transform: translateY(-8px);
}
</style>
