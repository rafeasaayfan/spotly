<script setup lang="ts">
import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import { Link, usePage } from '@inertiajs/vue3';

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
    <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div class="border-muted relative hidden h-full flex-col border-e p-10 lg:flex items-center justify-center">
            <div class="bg-navs absolute inset-0" />
            <div class="border border-muted rounded-full relative z-20 size-100
                flex flex-col items-center justify-center gap-2 font-medium bg-body pb-10">
                <AppLogoIcon class="size-86 fill-current" />
                <span class="text-active text-5xl font-semibold">{{ name }}</span>
            </div>
            <!-- <div v-if="quote" class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <p class="text-lg">&ldquo;{{ quote.message }}&rdquo;</p>
                    <footer class="text-sm text-neutral-300">{{ quote.author }}</footer>
                </blockquote>
            </div> -->
        </div>

        <div class="lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <Link :href="route('home')" class="relative z-20 flex lg:hidden h-full items-center justify-center gap-2 font-medium">
                    <AppLogoIcon class="size-24 fill-current" />
                </Link>

                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="text-active text-xl font-medium tracking-tight" v-if="title">{{ title }}</h1>
                    <p class="text-body-muted text-sm" v-if="description">{{ description }}</p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
