<script setup lang="ts">
import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import { Link, usePage } from '@inertiajs/vue3';
import '../../../css/landing.css';

// interface Quote {
//     message: string;
//     author: string;
// }

const page = usePage();
const name = page.props.name;
// const quote = page.props.quote as Quote | undefined;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="landing-body relative grid h-screen flex-col items-center justify-center px-4 sm:px-0 lg:max-w-none lg:grid-cols-3 lg:px-0">
        <div class="relative hidden h-full lg:flex flex-col items-center justify-center border-muted border-e-5 border-double col-span-1">
            <div :class="page.props.lang === 'ar' ? 'bg-gradient-to-l' : 'bg-gradient-to-r'" class="from-[var(--secondary-background)] dark:from-[var(--background)] absolute inset-0" />

                <Link href="/" class="z-10">
                    <AppLogoIcon class="size-65 fill-current" />
                </Link>

                <!-- <div class="bg-[var(--background)] dark:bg-gray-950 rounded-full size-130 blur-xl opacity-60"></div> -->

                <Link href="/" class="border-muted rounded-md border-double z-10">
                    <span class="text-active text-5xl font-bold tracking-widest ">{{
                        page.props.lang === 'ar' ? 'سبوتلي' : name
                    }}</span>
                </Link>

            <!-- <div v-if="quote" class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <p class="text-lg">&ldquo;{{ quote.message }}&rdquo;</p>
                    <footer class="text-sm text-neutral-300">{{ quote.author }}</footer>
                </blockquote>
            </div> -->
        </div>

        <div class="pb-5 lg:p-8 col-span-2">
            <div class="mx-auto flex w-full flex-col justify-center space-y-5 sm:w-[350px]">
                <Link
                    :href="route('landing')"
                    class="bg-body relative z-20 flex h-full items-center justify-center gap-2 rounded-b-md py-3 font-medium lg:hidden"
                >
                    <AppLogoIcon class="size-24 fill-current" />
                </Link>

                <div class="flex flex-col space-y-1 text-center">
                    <h1 class="text-active text-xl font-medium tracking-tight" v-if="title">{{ title }}</h1>
                    <p class="text-body-muted text-sm" v-if="description">{{ description }}</p>
                </div>

                <slot />
            </div>
        </div>
    </div>
</template>
