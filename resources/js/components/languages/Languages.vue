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
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { CheckCheck, LucideGlobe } from 'lucide-vue-next';
import Image from '../ui/image/Image.vue';

const languages = [
    { key: 'العربية', value: 'ar', src: '/images/flags/ar.avif' },
    { key: 'English', value: 'en', src: '/images/flags/en.png' },
    { key: 'Germany', value: 'de', src: '/images/flags/de.png' },
    { key: 'Frensh', value: 'fr', src: '/images/flags/de.png' },
];

const page = usePage<SharedData>();
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="icon" class="rounded-full">
                <LucideGlobe class="size-5" />
                <!-- <span class="block rounded-md px-3 py-2 md:hidden">Languages</span> -->
            </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent align="end" class="w-56">
            <DropdownMenuShortcut class="mb-2 px-2 py-2 text-xs tracking-wide uppercase">Languages</DropdownMenuShortcut>

            <DropdownMenuSeparator />

            <DropdownMenuGroup>
                <DropdownMenuItem v-for="lang in languages" :key="lang.key"
                    :class="page.props.lang == lang.value ? 'bg-content-active text-active font-medium' : ''">
                    <Link class="flex w-full items-center justify-between gap-2" :href="route('setLang', { lang: lang.value })">
                        <div class="flex items-center gap-2">
                            <Image :src="lang.src" class="border-muted h-6 w-6 !rounded-full border" />
                            <span>{{ lang.key }}</span>
                        </div>

                        <div v-if="page.props.lang == lang.value">
                            <CheckCheck class="size-4.5 font-semibold text-active-link" />
                        </div>
                    </Link>
                </DropdownMenuItem>
            </DropdownMenuGroup>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
