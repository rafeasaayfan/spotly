<script setup lang="ts">
// import { Monitor, Moon, Sun } from 'lucide-vue-next';

import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();

const languages = [
    { key: 'English', value: 'en', src: '/images/flags/en.png' },
    { key: 'العربية', value: 'ar', src: '/images/flags/ar.avif' },
];
</script>

<template>
    <div class="border-4 border-double web-border-color web-bg-body flex items-center justify-between gap-4 rounded max-w-full overflow-x-auto" :dir="page.props.lang == 'ar' ? 'rtl' : 'ltr'">
        <button
            v-for="lang in languages"
            :key="lang.value"
            :class="[
                'web-text-body-muted cursor-pointer transition-colors',
                page.props.lang === lang.value ? 'web-bg-primary web-text-for-primary font-bold shadow-xs' : 
                'bg-transparent hover:bg-[var(--bg_content_light)] dark:hover:bg-[var(--bg_content_dark)]',
            ]"
        >
            <Link class="flex w-full items-center px-3 py-1.5 md:px-3.5" :href="route('setLang', { lang: lang.value })">
                <div class="flex items-center gap-1 md:gap-2">
                    <!-- <component :is="Icon" class="w-3 h-3 md:h-4 md:w-4" /> -->
                    <span class="text-xs md:text-sm">{{ lang.key }}</span>
                </div>
            </Link>
        </button>
    </div>
</template>
