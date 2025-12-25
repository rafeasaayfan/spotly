<script setup lang="ts">
import { Carousel } from '@/components/ui/carousel';
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { Ban, Pin } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import Highlight from './Highlight.vue';

const props = defineProps<{
    product: Record<string, any>;
}>();

const page = usePage<SharedData>();

const images = props.product.all_images;

const colors = props.product.all_colors;

const variants = props.product.all_variants;

const badgeRef = ref<HTMLElement | null>(null);
onMounted(() => {
    // Animate the badge with a vibrate effect every 3 seconds
    const vibrate = () => {
        if (!badgeRef.value) return;
        gsap.fromTo(
            badgeRef.value,
            { x: 0 },
            {
                x: 4,
                duration: 0.06,
                repeat: 7,
                yoyo: true,
                ease: 'power1.inOut',
                onComplete: () => {
                    gsap.set(badgeRef.value, { x: 0 });
                },
            },
        );
    };

    setInterval(vibrate, 5000);
});
</script>

<template>
    <Link
        :href="`/product/${encodeURIComponent(props.product.slug)}`"
        class="product-card-item group web-bg-card relative pb-15 md:pb-18 lg:pb-15 flex max-h-[30rem] cursor-pointer flex-col gap-3 
        rounded-md p-2 backdrop-blur-md transition duration-300 border web-border-color"
    >
        <div v-if="props.product.is_discount && props.product.discount_price && props.product.price" class="absolute start-3 -top-1 z-10" ref="badgeRef">
            <div
                class="web-bg-danger relative flex flex-col rounded-t rounded-b px-1 pt-4 pb-2 text-[11px] text-white sm:px-1.5 sm:pt-6 sm:pb-3 sm:text-xs"
            >
                <div class="absolute start-0 -top-1 flex w-full items-center justify-center">
                    <Pin class="size-3.5 fill-black text-black shadow-xl sm:size-4 dark:fill-white dark:text-white" />
                </div>

                <span>
                    {{ Math.round(((Number(props.product.price) - Number(props.product.discount_price)) / Number(props.product.price)) * 100) }}%
                </span>
                <span>{{ $t('off') }}</span>
            </div>
        </div>

        <div class="flex w-full gap-2 sm:flex-col">
            <!-- Product Image -->
            <div class="relative overflow-hidden rounded-md w-58 sm:h-47 sm:w-full">
                <Carousel
                    v-if="images.length > 0"
                    :items="images"
                    @click.prevent.stop
                    parentClass="h-full w-full hover:scale-100 group-hover:scale-130 cursor-default transition-all duration-300 ease-in-out"
                    :showArrows="false"
                    imgClass="object-auto h-full w-full"
                />

                <div v-else class="web-bg-content flex h-40 sm:h-full w-full items-center justify-center text-xs">
                    <p class="web-text-body-muted">{{ $t('noImage') }}</p>
                </div>
            </div>

            <!-- Product Info -->
            <div class="flex flex-col w-full">
                <div class="flex items-center flex-wrap gap-1 sm:gap-2 mb-2" v-if="props.product.category || props.product.brand">
                    <Highlight
                        v-if="props.product.category"
                        :text="page.props.lang === 'ar' ? props.product.category.ar_name : props.product.category.name"
                        type="category"
                        class="px-1 py-1 text-[11px] sm:px-2 sm:py-1.5 sm:text-xs"
                    />
                    <Highlight v-if="props.product.brand" :text="props.product.brand.name" type="brand" class="px-1 py-1 text-[11px] sm:px-2 sm:py-1.5 sm:text-xs" />
                </div>

                <div class="flex w-full gap-1 sm:items-center justify-between">
                    <h3 class="web-text-active text-sm font-semibold sm:text-xl">{{ props.product.name }}</h3>
                    <div class="flex flex-col sm:flex-row items-center gap-1" v-if="colors.length > 0">
                        <div
                            v-for="(item) in colors.slice(0, 3)"
                            :key="item"
                            class="flex items-center justify-center rounded-full text-xs font-bold size-3 sm:size-4 shadow-md dark:shadow-white/3"
                            :style="{ backgroundColor: item.code ?? 'transparent' }"
                        >
                        </div>
                        <span v-if="colors.length > 3" class="text-xs font-bold text-body-muted">+{{ colors.length - 3 }}</span>
                    </div>
                </div>

                <p class="web-text-body-muted line-clamp-2 hidden text-start text-xs sm:block sm:text-sm">{{ props.product.short_description }}</p>

                <div class="flex flex-wrap items-center gap-1 pt-2" v-if="variants.length > 0 && !props.product.short_description">
                    <span v-for="(variant) in variants.slice(0, 3)" :key="variant.id" 
                        class="text-xs font-medium web-text-active px-2 py-1 rounded-full bg-[var(--primary_light)]/10 dark:bg-[var(--primary_dark)]/10"
                    >
                        {{ page.props.lang === 'ar' ? variant.name_ar : variant.name }}
                    </span>
                    <span v-if="variants.length > 3" class="text-xs font-bold web-text-body-muted">+{{ variants.length - 3 }}</span>
                </div>
            </div>
        </div>

        <p class="web-text-body-muted line-clamp-2 block text-start text-xs sm:hidden sm:text-sm">{{ props.product.short_description }}</p>

        <!-- Price and Actions -->
        <div
            class="absolute bottom-0 left-0 right-0 flex w-full flex-wrap items-center justify-between border-t border-[var(--foreground_muted_light)]/15 p-2 dark:border-[var(--foreground_muted_dark)]/15"
        >
            <div class="flex items-center gap-1 sm:gap-1.5">
                <span class="web-text-active text-base font-bold sm:text-lg">
                    {{ props.product.is_discount ? (props.product.price - props.product.discount_price) : props.product.price }}$
                </span>
                <span v-if="props.product.is_discount" class="web-text-body-muted text-xs line-through sm:text-sm"> {{ props.product.price }}$ </span>
            </div>

            <button
                v-if="!props.product.is_out_of_stock"
                type="button"
                class="eco-glow-button web-text-for-primary web-bg-primary flex cursor-pointer items-center gap-2 rounded px-2 py-1.5 font-medium"
            >
                <span class="text-xs">{{ $t('addToCart') }}</span>
            </button>

            <div v-else class="flex items-center gap-1 bg-red-500/25 dark:bg-red-500/20 rounded-md p-2 text-xs">
                <Ban class="web-text-danger size-3.5" />
                <span class="web-text-danger font-medium">{{ $t('out.of.stock') }}</span>
            </div>
        </div>
    </Link>
</template>
