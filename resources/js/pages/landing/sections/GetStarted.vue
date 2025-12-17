<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';

import { typeColor, typeIcon } from '@/lib/websiteTypes';
import { SharedData } from '@/types';

const props = defineProps<{
    websiteTypes: Record<string, any>;
}>();

const page = usePage<SharedData>();
</script>

<template>
    <section id="get-started" class="relative px-4 py-22">
        <div class="mx-auto">
            <div class="mb-14 flex w-full flex-col items-start gap-3">
                <h2 class="section-title section-title-underline text-active text-3xl font-bold sm:text-4xl md:text-4xl lg:text-5xl">
                    {{ $t('landing.get_started.title_part1') }} <span class="gradient-text">{{ $t('landing.get_started.title_part2') }}</span>
                </h2>
                <p class="text-body-muted max-w-3xl">{{ $t('landing.get_started.subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <Link
                    v-for="item in props.websiteTypes"
                    :key="item.type"
                    :href="`/website-builder?type=${encodeURIComponent(item.type)}`"
                    class="flex flex-col gap-3 rounded-md border-muted p-4 rounded-md border-double border-6
                    transition-all duration-300 relative"
                    :class="item.is_active ? 'opacity-100 hover:bg-black/1 dark:hover:bg-white/1 hover:translate-y-[-2px]' : 'blur-[0.5px] opacity-50 pointer-events-none'"
                >
                    <div class="flex items-center gap-2" :class="typeColor.text(item.type)">
                        <component :is="typeIcon(item.type)" class="size-5" />
                        <h3 class="text-lg font-bold">{{ $t(item.title) }}</h3>
                    </div>

                    <p class="text-[var(--foreground-muted)] text-sm">{{ $t(item.description) }}</p>

                    <div v-if="!item.is_active" class="absolute top-0 end-0 p-2" :class="page.props.lang === 'ar' ? 'rotate-5' : '-rotate-5'">
                        <span class="text-xs font-medium text-active">{{ $t('landing.get_started.coming_soon') }}</span>
                    </div>
                </Link>
            </div>
        </div>
    </section>
</template>
