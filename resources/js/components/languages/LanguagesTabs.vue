<script setup lang="ts">
// import { Monitor, Moon, Sun } from 'lucide-vue-next';

import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();

const languages = [
    { key: 'العربية', value: 'ar', src: '/images/flags/ar.avif' },
    { key: 'English', value: 'en', src: '/images/flags/en.png' },
];
</script>

<template>
    <div class="bg-card inline-flex gap-2 rounded-md p-1 max-w-full overflow-x-auto" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
        <button
            v-for="lang in languages"
            :key="lang.value"
            :class="[
                'text-body-muted cursor-pointer rounded-md transition-colors',
                page.props.lang === lang.value ? 'bg-primary text-for-bg-primary font-semibold shadow-xs' : 'bg-content-3',
            ]"
        >
            <Link class="flex w-full items-center px-2 py-1.5 md:px-3.5" :href="route('setLang', { lang: lang.value })">
                <div class="flex items-center gap-1 md:gap-2">
                    <!-- <component :is="Icon" class="w-3 h-3 md:h-4 md:w-4" /> -->
                    <span class="text-xs md:text-sm">{{ lang.key }}</span>
                </div>
            </Link>
        </button>
    </div>
</template>
