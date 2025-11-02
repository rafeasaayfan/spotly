<script setup lang="ts">
import StyleLayout from '@/pages/websites/StyleLayout.vue';
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';

const props = defineProps<{
    title?: string;
    description?: string;
    websiteNameAndLogo: Record<string, any>;
    colors: Record<string, string>;
}>();

// interface Quote {
//     message: string;
//     author: string;
// }
// const quote = page.props.quote as Quote | undefined;

const page = usePage<SharedData>();

const reactiveColors = reactive({ ...props.colors });

watch(
    () => props.colors,
    (newColors) => {
        // for reset
        Object.keys(reactiveColors).forEach((key) => {
            delete reactiveColors[key];
        });
        Object.assign(reactiveColors, newColors);
    },
    { deep: true, immediate: true },
);
</script>

<template>
    <StyleLayout :colors="reactiveColors">
        <div
            class="relative grid h-screen flex-col items-center justify-center px-4 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0"
            :dir="page.props.lang === 'ar' ? 'rtl' : 'ltr'"
        >
            <div class="web-border-color relative hidden h-full flex-col items-center justify-center border-e p-10 lg:flex">
                <div class="absolute inset-0 web-bg-nav" />
                <div class="absolute start-0 top-0 flex h-full w-full items-center justify-center overflow-hidden">
                    <div class="web-bg-body size-150 rounded-full opacity-50 blur-xl"></div>
                </div>
                <Link href="/" class="z-20 flex flex-col items-center justify-center gap-2 pb-10 font-medium">
                    <img
                        v-if="props.websiteNameAndLogo.light_logo"
                        :src="props.websiteNameAndLogo.light_logo"
                        class="block size-86 rounded-full shadow dark:hidden"
                    />
                    <img
                        v-if="props.websiteNameAndLogo.dark_logo"
                        :src="props.websiteNameAndLogo.dark_logo"
                        class="hidden size-86 rounded-full shadow dark:block"
                    />
                    <span class="web-text-active text-6xl font-bold tracking-widest">{{ props.websiteNameAndLogo.name }}</span>
                </Link>
                <!-- <div v-if="quote" class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <p class="text-lg">&ldquo;{{ quote.message }}&rdquo;</p>
                    <footer class="text-sm text-neutral-300">{{ quote.author }}</footer>
                </blockquote>
            </div> -->
            </div>

            <div class="pb-5 lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-5 sm:w-[350px]">
                    <Link
                        :href="route('landing')"
                        class="relative z-20 flex h-full items-center justify-center gap-2 rounded-b-md web-bg-body py-1 font-medium lg:hidden"
                    >
                        <img
                            v-if="props.websiteNameAndLogo.light_logo"
                            :src="props.websiteNameAndLogo.light_logo"
                            class="block size-24 rounded-full shadow dark:hidden"
                        />
                        <img
                            v-if="props.websiteNameAndLogo.dark_logo"
                            :src="props.websiteNameAndLogo.dark_logo"
                            class="hidden size-24 rounded-full shadow dark:block"
                        />
                        <span v-if="!props.websiteNameAndLogo.dark_logo" class="web-text-active text-6xl font-bold tracking-widest">{{
                            props.websiteNameAndLogo.name
                        }}</span>
                    </Link>

                    <div class="flex flex-col space-y-1 text-center">
                        <h1 class="web-text-active text-xl font-medium tracking-tight" v-if="title">{{ title }}</h1>
                        <p class="web-text-body-muted text-sm" v-if="description">{{ description }}</p>
                    </div>

                    <slot />
                </div>
            </div>
        </div>
    </StyleLayout>
</template>
