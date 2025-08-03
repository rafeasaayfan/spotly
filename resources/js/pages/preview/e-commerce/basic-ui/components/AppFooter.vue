<script setup lang="ts">
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowUp, ChevronRight, Facebook, Instagram, Mail, MapPin, Phone, Youtube } from 'lucide-vue-next';

const page = usePage<SharedData>();
</script>

<template>
    <footer class="web-border-color border-t" :dir="page.props.lang === 'ar' ? 'rtl' : 'ltr'">
        <div class="web-bg-footer">
            <div class="max-w-7xl mx-auto px-4 py-16 md:px-10 lg:px-4">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-5">
                    <!-- Company Info -->
                    <div class="lg:col-span-2 lg:max-w-3/4">
                        <h3 class="web-text-active mb-3 text-xl font-bold">Spotly Store</h3>
                        <p class="web-text-body-muted mb-6 text-sm leading-relaxed">
                            Your trusted destination for premium products and exceptional shopping experiences. We bring you the best from around the
                            world.
                        </p>

                        <!-- Social Links -->
                        <div class="social-section">
                            <h4 class="web-text-active mb-3 text-sm font-semibold">Follow Us</h4>
                            <div class="flex gap-3">
                                <a
                                    href="#"
                                    class="social-icon group flex h-10 w-10 items-center justify-center rounded-full web-bg-content
                                    transition-all duration-300 
                                    hover:bg-gradient-to-br hover:from-[#FE2C55] hover:to-[#25F4EE]"
                                    title="TikTok"
                                >
                                    <svg viewBox="0 0 256 256" class="size-5 stroke-black transition-all duration-300 dark:stroke-white">
                                        <path
                                            d="M168,106a95.9,95.9,0,0,0,56,18V84a56,56,0,0,1-56-56H128V156a28,28,0,1,1-40-25.3V89.1A68,68,0,1,0,168,156Z"
                                            fill="none"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="22"
                                        />
                                    </svg>
                                </a>
                                <a
                                    href="#"
                                    class="social-icon group flex h-10 w-10 items-center justify-center rounded-full web-bg-content
                                    transition-all duration-300 
                                    hover:bg-gradient-to-tr hover:from-[#f9ce34] hover:via-[#ee2a7b] hover:to-[#6228d7]"
                                    title="Instagram"
                                >
                                    <Instagram class="size-5 text-black transition-all duration-300 dark:text-white" />
                                </a>
                                <a
                                    href="#"
                                    class="social-icon group flex h-10 w-10 items-center justify-center rounded-full
                                    bg-[var(--bg_content_light)] dark:bg-[var(--bg_content_dark)]
                                    transition-all duration-300 hover:bg-[#FF0000] dark:hover:bg-[#FF0000]"
                                    title="YouTube"
                                >
                                    <Youtube class="size-5 text-black transition-all duration-300 dark:text-white" />
                                </a>
                                <a
                                    href="#"
                                    class="social-icon group flex h-10 w-10 items-center justify-center rounded-full
                                    bg-[var(--bg_content_light)] dark:bg-[var(--bg_content_dark)]
                                    transition-all duration-300 hover:bg-[#1877F3] dark:hover:bg-[#1877F3]"
                                    title="Facebook"
                                >
                                    <Facebook class="size-5 text-black transition-all duration-300 dark:text-white" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-3 flex flex-col justify-between gap-8 md:flex-row">
                        <!-- Quick Links -->
                        <div class="lg:col-span-1">
                            <h3 class="web-text-active mb-3 text-lg font-semibold">Quick Links</h3>
                            <div class="flex flex-col gap-3">
                                <div
                                    v-for="item in ['link1', 'link2', 'link3']"
                                    :key="item"
                                    class="web-text-body-muted 
                                     group flex items-center gap-1 text-sm transition-colors duration-300"
                                >
                                    <ChevronRight
                                        class="size-3.5 transition-transform duration-300"
                                        :class="page.props.lang === 'ar' ? 'rotate-180 group-hover:-translate-x-1' : 'group-hover:translate-x-1'"
                                    />
                                    {{ item }}
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="lg:col-span-1">
                            <h3 class="web-text-active mb-3 text-lg font-semibold">Contact Info</h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-2">
                                    <Mail class="text-primary mt-0.5 size-5" />
                                    <div>
                                        <p class="web-text-active text-sm font-medium">Email</p>
                                        <a href="mailto:support@spotly.com" class="web-text-body-muted 
                                         text-sm">support@spotly.com</a>
                                    </div>
                                </div>

                                <div class="flex items-start gap-2">
                                    <Phone class="mt-0.5 size-5" />
                                    <div>
                                        <p class="web-text-active text-sm font-medium">Phone</p>
                                        <a href="tel:+15551234567" class="web-text-body-muted text-sm
                                        ">+1 (555) 123-4567</a>
                                    </div>
                                </div>

                                <div class="flex items-start gap-2">
                                    <MapPin class="mt-0.5 size-5" />
                                    <div>
                                        <p class="web-text-active text-sm font-medium">Address</p>
                                        <p class="web-text-body-muted text-sm
                                        ">123 Business Street<br />New York, NY 10001</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Service -->
                        <div class="lg:col-span-1">
                            <h3 class="web-text-active mb-3 text-lg font-semibold">Customer Service</h3>
                            <div class="flex flex-col gap-3">
                                <Link
                                    v-for="item in ['Help Center', 'Privacy Policy', 'Terms of Service']"
                                    :key="item"
                                    :href="item"
                                    target="_blank"
                                    class="web-text-body-muted  group flex items-center gap-1 text-sm transition-colors duration-300"
                                >
                                    <ArrowUp
                                        class="size-3.5 transition-transform duration-300 group-hover:-translate-y-[2px]"
                                        :class="
                                            page.props.lang === 'ar'
                                                ? '-rotate-45 group-hover:-translate-x-[2px]'
                                                : 'rotate-45 group-hover:translate-x-[2px]'
                                        "
                                    />
                                    {{ item }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section -->
                <div class="mt-12 border-t border-white/10 pt-3">
                    <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
                        <div class="text-center md:text-left">
                            <p class="web-text-body-muted text-sm">
                                &copy; 2025 Spotly Store | All rights reserved | Powered by
                                <a href="https://spotly.com" class="gradient-text font-bold">Spotly</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped>
.social-icon {
    transition: all 0.3s ease;
}

.social-icon:hover {
    transform: translateY(-3px);
}
</style>

<!-- <div class="absolute top-0 left-0 flex flex-col sm:flex-row h-full w-full items-center justify-center sm:gap-10 md:gap-15 lg:gap-26 xl:gap-30 pointer-events-none select-none">
            <h1 v-for="letter in ['S', 'h', 'o', 'p', 'l', 'y']" :key="letter" class="web-text-body-muted text-9xl opacity-10 font-extrabold">
                {{ letter }}
            </h1>
        </div> -->
