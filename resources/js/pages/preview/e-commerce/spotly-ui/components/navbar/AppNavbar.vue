<script setup lang="ts">
import AppearenceBtn from '@/pages/websites/e-commerce/templates/spotly-ui/components/appearance/AppearanceBtn.vue';
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';

const page = usePage<SharedData>();
</script>

<template>
    <nav class="z-20 w-full">
        <div class="web-bg-nav backdrop-blur-md transition-all duration-300" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
            <div class="mx-auto flex h-15 max-w-7xl items-center justify-between px-4 transition-all duration-300 md:px-10 lg:px-4">
                <div class="cursor-pointer items-center gap-2">
                    <span class="eco-gradient-text text-xl font-bold">E-commerce</span>
                </div>

                <div class="flex items-center space-x-2">
                    <div class="relative cursor-pointer web-bg-content rounded-full p-2 transition-all duration-300 hover:-translate-y-0.5">
                        <div
                            class="web-text-for-danger web-bg-danger absolute -top-1.5 -right-0.5 flex size-4.5 items-center justify-center rounded-full text-[10px]"
                        >
                            3
                        </div>
                        <ShoppingCart class="size-5" />
                    </div>

                    <AppearenceBtn />
                </div>
            </div>
        </div>
    </nav>
</template>
