<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

import { SharedData } from '@/types';
import { ArrowUp, ChevronRight, Facebook, Instagram, Mail, MapPin, Phone, Youtube } from 'lucide-vue-next';

const page = usePage<SharedData>();

const websiteArrayName = () => {
    return 'Ecommerce'.toUpperCase().split('');
};
</script>

<template>
    <div class="footer-section web-border-color web-bg-footer relative border-t" :dir="page.props.lang === 'ar' ? 'rtl' : 'ltr'">
        <div class="container mx-auto px-4 py-16 md:px-10 lg:px-4">
            <div class="footer-content grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-5">
                <!-- Company Info -->
                <div class="flex flex-col gap-4 lg:col-span-2 lg:max-w-3/4">
                    <div class="flex flex-col justify-center gap-1">
                        <h3 class="web-text-active-link text-xl font-bold uppercase">Ecommerce</h3>
                        <p class="web-text-body-muted text-sm leading-relaxed">
                            {{ $t('web.footer.paragraph') }}
                        </p>
                    </div>

                    <!-- Social Links -->
                    <div class="social-section">
                        <h4 class="web-text-active mb-3 text-sm font-semibold">{{ $t('footer.followUs') }}</h4>
                        <div class="flex gap-3">
                            <div
                                class="social-icon group flex h-10 w-10 items-center justify-center rounded-full bg-[var(--bg_content_light)] stroke-[var(--foreground_muted_light)] transition-all duration-300 hover:bg-gradient-to-br hover:from-[#FE2C55] hover:to-[#25F4EE] hover:stroke-[var(--foreground_active_light)] dark:bg-[var(--bg_content_dark)] dark:stroke-[var(--foreground_muted_dark)] dark:hover:stroke-[var(--foreground_active_dark)]"
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
                            </div>
                            <a
                                class="social-icon group web-text-body-muted flex h-10 w-10 items-center justify-center rounded-full bg-[var(--bg_content_light)] transition-all duration-300 hover:bg-gradient-to-tr hover:from-[#f9ce34] hover:via-[#ee2a7b] hover:to-[#6228d7] dark:bg-[var(--bg_content_dark)]"
                                title="Instagram"
                            >
                                <Instagram class="size-5 transition-all duration-300" />
                            </a>
                            <div
                                class="social-icon group web-text-body-muted flex h-10 w-10 items-center justify-center rounded-full bg-[var(--bg_content_light)] transition-all duration-300 hover:bg-[#FF0000] dark:bg-[var(--bg_content_dark)] dark:hover:bg-[#FF0000]"
                                title="YouTube"
                            >
                                <Youtube class="size-5 transition-all duration-300" />
                            </div>
                            <div
                                class="social-icon group web-text-body-muted flex h-10 w-10 items-center justify-center rounded-full bg-[var(--bg_content_light)] transition-all duration-300 hover:bg-[#1877F3] dark:bg-[var(--bg_content_dark)] dark:hover:bg-[#1877F3]"
                                title="Facebook"
                            >
                                <Facebook class="size-5 transition-all duration-300" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-3 flex flex-col justify-between gap-8 md:flex-row">
                    <!-- Quick Links -->
                    <div>
                        <h3 class="web-text-active mb-3 text-lg font-semibold">{{ $t('footer.quickLinks') }}</h3>
                        <div class="flex flex-col gap-3">
                            <div
                                v-for="item in ['Home', 'Shop']"
                                :key="item"
                                :class="item === 'Home' ? 'web-text-active' : 'web-text-body-muted'"
                                class="web-text-body-muted group flex items-center gap-1 text-sm transition-colors duration-300"
                            >
                                <ChevronRight
                                    class="size-3.5 transition-transform duration-300"
                                    :class="
                                        item === 'Home'
                                            ? page.props.lang === 'ar'
                                                ? '-translate-x-1 rotate-180'
                                                : 'translate-x-1'
                                            : page.props.lang === 'ar'
                                              ? 'rotate-180 group-hover:-translate-x-1'
                                              : 'group-hover:translate-x-1'
                                    "
                                />
                                {{ $t(item) }}
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="web-text-active mb-3 text-lg font-semibold">{{ $t('footer.contact') }}</h3>

                        <div class="space-y-4">
                            <div class="flex items-start gap-2">
                                <Mail class="web-text-body-muted mt-0.5 size-4" />
                                <div>
                                    <p class="web-text-body-muted text-sm font-medium">{{ $t('email') }}</p>
                                    <div class="web-text-body-muted text-sm underline underline-offset-2"> Email </div>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <Phone class="web-text-body-muted mt-0.5 size-4" />
                                <div>
                                    <p class="web-text-body-muted text-sm font-medium">{{ $t('phone') }}</p>
                                    <div class="web-text-body-muted text-sm underline underline-offset-2">
                                        <p class="[direction:ltr]">7477473884</p>
                                    </div>
                                </div>
                            </div>  

                            <div class="flex items-start gap-2">
                                <MapPin class="web-text-body-muted mt-0.5 size-4" />
                                <div>
                                    <p class="web-text-body-muted text-sm font-medium">{{ $t('address') }}</p>
                                    <p class="web-text-body-muted text-sm">Address</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Service -->
                    <div class="">
                        <h3 class="web-text-active mb-3 text-lg font-semibold">{{ $t('footer.custom') }}</h3>
                        <div class="flex flex-col gap-3">
                            <div
                                v-for="item in [$t('footer.help'), $t('footer.privacy'), $t('footer.terms')]"
                                :key="item"
                                class="web-text-body-muted group flex items-center gap-1 text-sm transition-colors duration-300"
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="web-border-color mt-12 border-t pt-3">
                <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
                    <div class="text-center md:text-left">
                        <p class="web-text-body-muted text-sm">
                            &copy;
                            {{ $t('web.footer.rights') }}
                            {{ $t('web.footer.powered') }}
                            <div class="text-active-link text-xs">Spotly</div>
                        </p>
                    </div>

                    <!-- Scroll to Top Button -->
                    <button
                        type="button"
                        class="web-bg-primary flex size-10 items-center justify-center rounded-full transition-all duration-300 hover:scale-110"
                    >
                        <ArrowUp class="web-text-for-primary size-5" />
                    </button>
                </div>
            </div>

            <div
                class="flex w-full flex-col items-center justify-between gap-2 pt-30 lg:flex-row"
                :class="false ? 'justify-center' : page.props.lang === 'ar' ? 'lg:flex-row-reverse' : ''"
            >
                <div class="items-center gap-2.5 [direction:ltr] sm:gap-4 hidden lg:flex">
                    <h1
                        v-for="letter in websiteArrayName()"
                        :key="letter"
                        class="web-text-active text-5xl font-bold sm:text-7xl md:text-8xl lg:text-9xl"
                    >
                        {{ letter }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
</template>
