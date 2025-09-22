<script setup lang="ts">
import { useNavigation } from '@/composables/navigation/useNavigation';
import { navbarItems } from '@/config/navigations';

import { Link, usePage } from '@inertiajs/vue3';

import { SharedData } from '@/types';
import { ArrowUp, ChevronRight, Facebook, Instagram, Mail, Phone, Youtube } from 'lucide-vue-next';
import AppLogoIcon from './logo/AppLogoIcon.vue';

const page = usePage<SharedData>();

const { scrollToSection } = useNavigation(navbarItems);

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

const spotlyArrayName = () => {
    if(page.props.lang === 'ar') {
        // return ['س', 'ب', 'و', 'ت', 'ل', 'ي'];
        return ['سبوتلي'];
    }

    return ['S', 'P', 'O', 'T', 'L', 'Y'];
}
</script>

<template>
    <footer class="footer-section border-muted relative border-t bg-black/3 dark:bg-white/3" :dir="page.props.lang === 'ar' ? 'rtl' : 'ltr'">
        <div class="container mx-auto px-4 py-16 md:px-10 lg:px-4">
            <div class="footer-content grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-5">
                <!-- Company Info -->
                <div class="flex flex-col gap-4 lg:col-span-2 lg:max-w-3/4">
                    <div class="flex flex-col justify-center gap-1">
                        <h3 class="text-active-link text-xl font-bold uppercase">{{ $t('spotly') }}</h3>
                        <p class="text-body-muted text-sm leading-relaxed">
                            {{ $t('footer.paragraph') }}
                        </p>
                    </div>

                    <!-- Social Links -->
                    <div class="social-section">
                        <h4 class="text-active mb-3 text-sm font-semibold">{{ $t('footer.followUs') }}</h4>
                        <div class="flex gap-3">
                            <a
                                href="#"
                                class="social-icon group flex h-10 w-10 items-center justify-center rounded-full bg-black/6 transition-all duration-300
                                 hover:bg-gradient-to-br hover:from-[#FE2C55] hover:to-[#25F4EE] dark:bg-white/6 stroke-body-muted"
                                title="TikTok"
                            >
                                <svg viewBox="0 0 256 256" class="size-5 transition-all duration-300">
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
                                class="social-icon group flex h-10 w-10 items-center justify-center rounded-full bg-black/6 transition-all duration-300
                                text-body-muted hover:bg-gradient-to-tr hover:from-[#f9ce34] hover:via-[#ee2a7b] hover:to-[#6228d7] dark:bg-white/6"
                                title="Instagram"
                            >
                                <Instagram class="size-5 transition-all duration-300" />
                            </a>
                            <a
                                href="#"
                                class="social-icon group flex h-10 w-10 items-center justify-center rounded-full bg-black/6 transition-all duration-300
                                text-body-muted hover:bg-[#FF0000] dark:bg-white/6 dark:hover:bg-[#FF0000]"
                                title="YouTube"
                            >
                                <Youtube class="size-5 transition-all duration-300" />
                            </a>
                            <a
                                href="#"
                                class="social-icon group flex h-10 w-10 items-center justify-center rounded-full bg-black/6 transition-all duration-300
                                text-body-muted hover:bg-[#1877F3] dark:bg-white/6 dark:hover:bg-[#1877F3]"
                                title="Facebook"
                            >
                                <Facebook class="size-5 transition-all duration-300" />
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-span-3 flex flex-col justify-between gap-8 md:flex-row">
                    <!-- Quick Links -->
                    <div class="">
                        <h3 class="text-active mb-3 text-lg font-semibold">{{ $t('footer.quickLinks') }}</h3>
                        <div class="flex flex-col gap-3">
                            <button
                                type="button"
                                v-for="(item, index) in navbarItems"
                                :key="index"
                                @click="scrollToSection(item.href ?? '')"
                                class="text-body-muted group flex cursor-pointer items-center gap-1 text-sm transition-colors duration-300"
                            >
                                <ChevronRight
                                    class="size-3.5 transition-transform duration-300"
                                    :class="page.props.lang === 'ar' ? 'rotate-180 group-hover:-translate-x-1' : 'group-hover:translate-x-1'"
                                />
                                {{ $t(item.title) }}
                            </button>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="">
                        <h3 class="text-active mb-3 text-lg font-semibold">{{ $t('footer.contact') }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-2">
                                <Mail class="text-body-muted mt-0.5 size-4" />
                                <div>
                                    <p class="text-body-muted text-sm font-medium">{{ $t('email') }}</p>
                                    <a href="mailto:spotly.lb@gmail.com" class="text-body-muted text-sm underline underline-offset-2">spotly.lb@gmail.com</a>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <Phone class="text-body-muted mt-0.5 size-4" />
                                <div>
                                    <p class="text-body-muted text-sm font-medium">{{ $t('phone') }}</p>
                                    <a href="tel:+96171412898" class="text-body-muted text-sm underline underline-offset-2">
                                        <p class="[direction:ltr]">+961 71 412 898</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Service -->
                    <div class="">
                        <h3 class="text-active mb-3 text-lg font-semibold">{{ $t('footer.custom') }}</h3>
                        <div class="flex flex-col gap-3">
                            <Link
                                v-for="item in [ $t('footer.help'), $t('footer.privacy'), $t('footer.terms')]"
                                :key="item"
                                :href="item"
                                target="_blank"
                                class="text-body-muted group flex items-center gap-1 text-sm transition-colors duration-300"
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
            <div class="border-muted mt-12 border-t pt-3">
                <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
                    <div class="text-center md:text-left">
                        <p class="text-body-muted text-sm">
                            &copy; {{ $t('footer.rights') }}
                        </p>
                    </div>

                    <!-- Scroll to Top Button -->
                    <button
                        @click="scrollToTop"
                        class="bg-primary hover:bg-primary/90 flex h-10 w-10 items-center justify-center rounded-full transition-all duration-300 hover:scale-110"
                    >
                        <ArrowUp class="size-5 text-white" />
                    </button>
                </div>
            </div>

            <div class="flex w-full items-center flex-col gap-2 lg:flex-row justify-between pt-30" 
            :class="page.props.lang === 'ar' ? 'lg:flex-row-reverse' : ''">
                <div>
                    <AppLogoIcon class="size-30 sm:size-35 md:size-45" />
                </div>
                <div class="flex items-center gap-2.5 sm:gap-4">
                    <h1 v-for="letter in spotlyArrayName()" :key="letter" class="text-active text-5xl sm:text-7xl md:text-8xl lg:text-9xl font-bold">
                        {{ letter }}
                    </h1>
                </div>
            </div>
        </div>
    </footer>
</template>
