<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import Image from '@/components/ui/image/Image.vue';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { CheckCheck, LucideGlobe } from 'lucide-vue-next';

const languages = [
    { key: 'العربية', value: 'ar', src: '/images/flags/ar.avif' },
    { key: 'English', value: 'en', src: '/images/flags/en.png' },
];

const page = usePage<SharedData>();

const changeLanguage = (lang: string) => {
    window.location.href = route('setLang', { lang: lang });
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="icon" class="rounded-full web-bg-content web-text-muted-body">
                <LucideGlobe class="size-5" />
            </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent class="w-56 web-bg-dropdown">
            <DropdownMenuShortcut class="web-text-muted-body mb-2 px-2 py-2 text-xs tracking-wide uppercase">{{ $t('languages') }}</DropdownMenuShortcut>

            <DropdownMenuSeparator class="web-bg-content" />

            <DropdownMenuGroup>
                <DropdownMenuItem v-for="lang in languages" :key="lang.key" @click="changeLanguage(lang.value)"
                    class="bg-transparent hover:bg-[var(--bg_content_light)] dark:hover:bg-[var(--bg_content_dark)] web-text-body"
                    :class="page.props.lang == lang.value ? 'web-bg-content-active web-text-active font-medium' : ''">
                    <div class="flex w-full items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <Image :src="lang.src" class="web-border-color h-6 w-6 !rounded-full border" />
                            <span>{{ lang.key }}</span>
                        </div>

                        <div v-if="page.props.lang == lang.value">
                            <CheckCheck class="size-4.5 font-semibold text-[var(--success)]" />
                        </div>
                    </div>
                </DropdownMenuItem>
            </DropdownMenuGroup>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
