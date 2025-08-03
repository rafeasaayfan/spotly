<script setup lang="ts">
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();
</script>

<template>
    <nav class="z-20 w-full">
        <div
            class="web-bg-nav backdrop-blur-md transition-all duration-300"
            :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'"
        >
            <div class="mx-auto flex h-15 max-w-7xl items-center justify-between px-4 transition-all duration-300 md:px-10 lg:px-4">
                <div class="hidden cursor-pointer items-center gap-2 md:flex">
                    <!-- <AppLogo /> -->
                    <span class="web-gradient-text text-xl font-bold">E-commerce</span>
                </div>
            </div>
        </div>
    </nav>
</template>
