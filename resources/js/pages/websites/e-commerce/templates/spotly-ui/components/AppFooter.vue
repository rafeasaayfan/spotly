<script setup lang="ts">
import { useNavigation } from '@/composables/navigation/useNavigation';
import { navbarItems } from '../../../config/navigations/navbar';

import { Link, usePage } from '@inertiajs/vue3';

import { SharedData } from '@/types';
import { ArrowUp, ChevronRight, Facebook, Instagram, Mail, MapPin, Phone, Youtube } from 'lucide-vue-next';

const props = defineProps<{
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
}>();

const page = usePage<SharedData>();

const { isCurrentRoute } = useNavigation(navbarItems);

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

const websiteArrayName = () => {
    return (props.websiteNameAndLogo?.name || 'SPOTLY').toUpperCase().split('');
};
</script>

<template>
    <footer class="footer-section web-border-color relative border-t web-bg-footer" :dir="page.props.lang === 'ar' ? 'rtl' : 'ltr'">
        <div class="container mx-auto px-4 py-16 md:px-10 lg:px-4">
            <div class="footer-content grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-5">
                <!-- Company Info -->
                <div class="flex flex-col gap-4 lg:col-span-2 lg:max-w-3/4">
                    <div class="flex flex-col justify-center gap-1">
                        <h3 class="web-text-active-link text-xl font-bold uppercase">{{ props.websiteNameAndLogo.name }}</h3>
                        <p class="web-text-body-muted text-sm leading-relaxed">
                            {{ $t('web.footer.paragraph') }}
                        </p>
                    </div>

                    <!-- Social Links -->
                    <div class="social-section" v-if="props.websiteFooterData.instagram || props.websiteFooterData.facebook ||
                     props.websiteFooterData.tiktok || props.websiteFooterData.youTube">
                        <h4 class="web-text-active mb-3 text-sm font-semibold">{{ $t('footer.followUs') }}</h4>
                        <div class="flex gap-3">
                            <a
                                v-if="props.websiteFooterData.tiktok"
                                href="#"
                                class="social-icon group stroke-[var(--foreground_muted_light)] dark:stroke-[var(--foreground_muted_dark)] 
                                hover:stroke-[var(--foreground_active_light)] dark:hover:stroke-[var(--foreground_active_dark)] flex h-10 w-10 items-center 
                                justify-center rounded-full transition-all duration-300
                                bg-[var(--bg_content_light)] dark:bg-[var(--bg_content_dark)] hover:bg-gradient-to-br hover:from-[#FE2C55] hover:to-[#25F4EE]"
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
                                v-if="props.websiteFooterData.instagram"
                                href="#"
                                class="social-icon group web-text-body-muted flex h-10 w-10 items-center justify-center rounded-full 
                                bg-[var(--bg_content_light)] dark:bg-[var(--bg_content_dark)] transition-all duration-300 
                                hover:bg-gradient-to-tr hover:from-[#f9ce34] hover:via-[#ee2a7b] hover:to-[#6228d7]"
                                title="Instagram"
                            >
                                <Instagram class="size-5 transition-all duration-300" />
                            </a>
                            <a
                                v-if="props.websiteFooterData.youTube"
                                href="#"
                                class="social-icon group web-text-body-muted flex h-10 w-10 items-center justify-center
                                rounded-full bg-[var(--bg_content_light)] dark:bg-[var(--bg_content_dark)] transition-all duration-300 hover:bg-[#FF0000] 
                                dark:hover:bg-[#FF0000]"
                                title="YouTube"
                            >
                                <Youtube class="size-5 transition-all duration-300" />
                            </a>
                            <a
                                v-if="props.websiteFooterData.facebook"
                                href="#"
                                class="social-icon group web-text-body-muted flex h-10 w-10 items-center justify-center rounded-full 
                                bg-[var(--bg_content_light)] dark:bg-[var(--bg_content_dark)] transition-all duration-300 hover:bg-[#1877F3] dark:hover:bg-[#1877F3]"
                                title="Facebook"
                            >
                                <Facebook class="size-5 transition-all duration-300" />
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-span-3 flex flex-col justify-between gap-8 md:flex-row">
                    <!-- Quick Links -->
                    <div>
                        <h3 class="web-text-active mb-3 text-lg font-semibold">{{ $t('footer.quickLinks') }}</h3>
                        <div class="flex flex-col gap-3">
                            <Link
                                v-for="item in navbarItems"
                                :key="item.title"
                                :href="item.href ?? ''"
                                :class="isCurrentRoute(item.href ?? '') ? 'web-text-active' : 'web-text-body-muted'"
                                class="web-text-body-muted group flex items-center gap-1 text-sm transition-colors duration-300"
                            >
                                <ChevronRight
                                    class="size-3.5 transition-transform duration-300"
                                    :class="
                                        isCurrentRoute(item.href ?? '')
                                            ? page.props.lang === 'ar'
                                                ? '-translate-x-1 rotate-180'
                                                : 'translate-x-1'
                                            : page.props.lang === 'ar'
                                              ? 'rotate-180 group-hover:-translate-x-1'
                                              : 'group-hover:translate-x-1'
                                    "
                                />
                                {{ $t(item.title) }}
                            </Link>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div v-if="props.websiteFooterData.email || props.websiteFooterData.phone_number || props.websiteFooterData.address">
                        <h3 class="web-text-active mb-3 text-lg font-semibold">{{ $t('footer.contact') }}</h3>

                        <div class="space-y-4">
                            <div class="flex items-start gap-2" v-if="props.websiteFooterData.email">
                                <Mail class="web-text-body-muted mt-0.5 size-4" />
                                <div>
                                    <p class="web-text-body-muted text-sm font-medium">{{ $t('email') }}</p>
                                    <a :href="'mailto:' + props.websiteFooterData.email" 
                                    class="web-text-body-muted text-sm underline underline-offset-2">
                                        {{ props.websiteFooterData.email }}
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start gap-2" v-if="props.websiteFooterData.phone_number">
                                <Phone class="web-text-body-muted mt-0.5 size-4" />
                                <div>
                                    <p class="web-text-body-muted text-sm font-medium">{{ $t('phone') }}</p>
                                    <a :href="'tel:' + props.websiteFooterData.phone_number"
                                     class="web-text-body-muted text-sm underline underline-offset-2">
                                        <p class="[direction:ltr]">{{ props.websiteFooterData.phone_number }}</p>
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start gap-2" v-if="props.websiteFooterData.address">
                                <MapPin class="web-text-body-muted mt-0.5 size-4" />
                                <div>
                                    <p class="web-text-body-muted text-sm font-medium">{{ $t('address') }}</p>
                                    <p class="web-text-body-muted text-sm">{{ props.websiteFooterData.address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Service -->
                    <div class="">
                        <h3 class="web-text-active mb-3 text-lg font-semibold">{{ $t('footer.custom') }}</h3>
                        <div class="flex flex-col gap-3">
                            <Link
                                v-for="item in [$t('footer.help'), $t('footer.privacy'), $t('footer.terms')]"
                                :key="item"
                                :href="item"
                                target="_blank"
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
                            </Link>
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
                            <a href="https://spotly.com" class="text-active-link text-xs">Spotly</a>
                        </p>
                    </div>

                    <!-- Scroll to Top Button -->
                    <button
                        @click="scrollToTop"
                        class="web-bg-primary flex size-10 items-center justify-center rounded-full transition-all duration-300 hover:scale-110"
                    >
                        <ArrowUp class="web-text-for-primary size-5" />
                    </button>
                </div>
            </div>

            <div
                class="flex w-full flex-col items-center justify-between gap-2 pt-30 lg:flex-row"
                :class="
                    !props.websiteNameAndLogo.light_logo || !props.websiteNameAndLogo.dark_logo
                        ? 'justify-center'
                        : page.props.lang === 'ar'
                          ? 'lg:flex-row-reverse'
                          : ''
                "
            >
                <Link :href="route('website.e-commerce.home')" class="hidden cursor-pointer items-center gap-3 md:flex">
                    <img
                        v-if="props.websiteNameAndLogo.light_logo"
                        :src="props.websiteNameAndLogo.light_logo"
                        class="block size-30 rounded-full sm:size-35 md:size-45 dark:hidden"
                    />
                    <img
                        v-if="props.websiteNameAndLogo.dark_logo"
                        :src="props.websiteNameAndLogo.dark_logo"
                        class="hidden size-30 rounded-full sm:size-35 md:size-45 dark:block"
                    />
                </Link>
                <div class="flex items-center gap-2.5 sm:gap-4 [direction:ltr]">
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
    </footer>
</template>
