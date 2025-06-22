<script setup lang="ts">
import AppLogoIcon from './logo/AppLogoIcon.vue';
import { Input, InputError } from './ui/fields';

import { navbarItems } from '@/config/navigations';
import { useNavigation } from '@/composables/navigation/useNavigation';

import { Link, useForm } from '@inertiajs/vue3';

import { Facebook, Instagram, LoaderCircle } from 'lucide-vue-next';
import { Button } from './ui/button';

const { scrollToSection } = useNavigation(navbarItems);

const form = useForm({ email: '' });

const subscribe = () => {
    form.put(route('subscribe'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <footer class="border-muted relative flex min-h-[500px] w-full items-center justify-center border-t bg-black/4 dark:bg-white/4">
        <div class="absolute top-0 left-0 flex flex-col sm:flex-row h-full w-full items-center justify-center sm:gap-10 md:gap-15 lg:gap-26 xl:gap-30">
            <h1 v-for="letter in ['S', 'p', 'o', 't', 'l', 'y']" :key="letter" class="text-body-muted text-9xl opacity-50">
                {{ letter }}
            </h1>
        </div>

        <div class="z-10 flex h-full w-full flex-col bg-transparent px-6 py-10 backdrop-blur-md lg:px-15 pb-22 xl:pb-5">
            <div class="grid w-full grid-cols-4 gap-22">
                <div class="col-span-4 flex flex-col items-center justify-center gap-4 rounded-lg bg-white/10 shadow xl:col-span-1 dark:bg-black/50 p-2">
                    <AppLogoIcon class="size-42" />

                    <p class="text-body-muted mt-2 text-xs">v1.0.0 • Built with Laravel & Vue</p>
                </div>

                <div class="col-span-4 grid grid-cols-3 gap-18 md:gap-3 xl:col-span-3">
                    <div class="col-span-3 flex items-center justify-center md:col-span-1 md:items-start md:justify-start">
                        <div class="flex flex-col gap-3">
                            <h3 class="text-body font-semibold">Quick Links</h3>
                            <div class="flex flex-col gap-2 ps-2">
                                <p
                                    v-for="(item, index) in navbarItems"
                                    :key="index"
                                    @click="scrollToSection(item.href ?? '')"
                                    class="cursor-pointer text-body-muted flex items-center gap-2 transition-all duration-100 hover:font-medium"
                                >
                                    <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
                                    <span>
                                        {{ item.title }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-3 flex items-center justify-center md:col-span-1 md:items-start md:justify-start">
                        <div class="flex flex-col gap-3">
                            <h3 class="text-body font-semibold">Contact</h3>
                            <div class="flex flex-col gap-2 ps-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-body-muted">Email:</span>
                                    <a href="mailto:support@spotly.com" class="text-body-muted font-bold">support@spotly.com</a>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-body-muted">Phone:</span>
                                    <a href="tel:+961 71 412 898" class="text-body-muted font-bold">+961 71 412 898</a>
                                </div>
                                <Link href="/support" class="text-body-muted hover:underline">Help Center</Link>
                            </div>
                        </div>
                    </div>

                    <!-- Newsletter + social -->
                    <div class="col-span-3 flex items-center justify-center md:col-span-1 md:items-start md:justify-start">
                        <div class="flex flex-col gap-5 md:w-full">
                            <div class="flex flex-col gap-3">
                                <h3 class="text-body font-semibold">Subscribe to Newsletter</h3>
                                <form @submit.prevent="subscribe" class="flex flex-col gap-2">
                                    <Input v-model="form.email" type="email" placeholder="Enter your email" autocomplete="email" required />
                                    <InputError v-if="form.errors" :message="form.errors.email" />
                                    <Button type="submit" :disabled="form.processing">
                                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                        Subscribe
                                    </Button>
                                </form>
                            </div>

                            <div class="flex items-center gap-3">
                                <a href="https://instagram.com" target="_blank" aria-label="Instagram">
                                    <Instagram class="text-body-muted size-6" />
                                </a>
                                <a href="https://tiktok.com" target="_blank" aria-label="TikTok">
                                    <svg viewBox="0 0 256 256" class="stroke-body-muted size-6">
                                        <path
                                            d="M168,106a95.9,95.9,0,0,0,56,18V84a56,56,0,0,1-56-56H128V156a28,28,0,1,1-40-25.3V89.1A68,68,0,1,0,168,156Z"
                                            fill="none"
                                            class=""
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="22"
                                        />
                                    </svg>
                                </a>
                                <a href="https://facebook.com" target="_blank" aria-label="Facebook">
                                    <Facebook class="text-body-muted size-6" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="absolute bottom-5 left-0 z-10 flex w-full items-center justify-center px-6">
            <p class="text-body-muted w-fit text-xs">
                © 2025 Spotly. All rights reserved. | <Link href="/privacy" class="underline hover:text-blue-700">Privacy Policy</Link> •
                <Link href="/terms" class="underline hover:text-blue-700">Terms of Service</Link>
            </p>
        </div>
    </footer>
</template>
