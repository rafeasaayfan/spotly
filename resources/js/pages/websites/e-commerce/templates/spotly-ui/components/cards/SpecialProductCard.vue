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

// const isColorLight = (color: string): boolean => {
//     // Accepts hex color codes: #RRGGBB or #RGB
//     let r, g, b;

//     if (!color) return false;

//     // Remove hash if present
//     if (color[0] === '#') color = color.slice(1);

//     // Expand shorthand form (#RGB) to full form (#RRGGBB)
//     if (color.length === 3) {
//         r = parseInt(color[0] + color[0], 16);
//         g = parseInt(color[1] + color[1], 16);
//         b = parseInt(color[2] + color[2], 16);
//     } else if (color.length === 6) {
//         r = parseInt(color.slice(0, 2), 16);
//         g = parseInt(color.slice(2, 4), 16);
//         b = parseInt(color.slice(4, 6), 16);
//     } else {
//         // Not a valid hex color
//         return false;
//     }

//     // Perceived brightness formula
//     const brightness = (r * 299 + g * 587 + b * 114) / 1000;
//     return brightness > 130;
// };

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
        class="web-bg-card carousel-item group web-border-color relative grid h-full scale-90 cursor-pointer gap-3 rounded-md border p-2 transition-all duration-300 ease-out hover:scale-105 hover:skew-0 hover:opacity-100 lg:opacity-70 dark:opacity-70 dark:hover:opacity-100"
        :class="page.props.lang === 'ar' ? 'lg:-skew-8' : 'lg:skew-8'"
    >
        <div
            v-if="props.product.discount_price && props.product.is_discount && props.product.price"
            class="absolute start-3 -top-1 z-20"
            ref="badgeRef"
        >
            <div class="web-bg-danger relative flex flex-col rounded-t rounded-b px-1.5 pt-6 pb-3 text-xs text-white">
                <div class="absolute start-0 -top-1 flex w-full items-center justify-center">
                    <Pin class="size-4 fill-black text-black shadow-xl dark:fill-white dark:text-white" />
                </div>

                <span>
                    {{ Math.round(((Number(props.product.price) - Number(props.product.discount_price)) / Number(props.product.price)) * 100) }}%
                </span>
                <span>{{ $t('off') }}</span>
            </div>
        </div>

        <div class="web-border-color z-10 w-full overflow-hidden rounded-md border-2 border-dotted md:h-50">
            <Carousel
                v-if="images.length > 0"
                :items="images"
                @click.prevent.stop
                parentClass="w-full h-full object-contain group-hover:scale-130 cursor-default transition-all duration-500 ease-in-out hover:scale-100"
                :showArrows="false"
                imgClass="object-auto min-h-full"
            />
            <div v-else class="web-bg-content flex h-40 w-full items-center justify-center text-xs md:h-full">
                <p class="web-text-body-muted">{{ $t('noImage') }}</p>
            </div>
        </div>

        <div class="z-10 flex flex-col justify-between gap-3">
            <div class="flex flex-col">
                <div class="mb-2 flex items-center gap-2" v-if="props.product.category || props.product.brand">
                    <Highlight
                        v-if="props.product.category"
                        :text="page.props.lang === 'ar' ? props.product.category.ar_name : props.product.category.name"
                        type="category"
                    />
                    <Highlight v-if="props.product.brand" :text="props.product.brand.name" type="brand" />
                </div>

                <div class="flex w-full items-center justify-between gap-1">
                    <h3 class="web-text-active text-xl font-semibold">{{ props.product.name }}</h3>
                    <div class="flex items-center gap-1" v-if="colors.length > 0">
                        <div
                            v-for="item in colors.slice(0, 3)"
                            :key="item"
                            class="flex size-4 items-center justify-center rounded-full text-xs font-bold shadow-md dark:shadow-white/3"
                            :style="{ backgroundColor: item.code ?? 'transparent' }"
                        ></div>
                        <span v-if="colors.length > 3" class="text-body-muted text-xs font-bold">+{{ colors.length - 3 }}</span>
                    </div>
                </div>

                <p class="web-text-body-muted line-clamp-2 text-start text-sm">{{ props.product.short_description }}</p>

                <div class="flex flex-wrap items-center gap-1 pt-2" v-if="variants.length > 0 && !props.product.short_description">
                    <span
                        v-for="variant in variants.slice(0, 4)"
                        :key="variant.id"
                        class="web-text-active rounded-full bg-[var(--primary_light)]/10 px-2 py-1 text-xs font-medium dark:bg-[var(--primary_dark)]/10"
                    >
                        {{ page.props.lang === 'ar' ? variant.name_ar : variant.name }}
                    </span>
                    <span v-if="variants.length > 4" class="web-text-body-muted text-xs font-bold">+{{ variants.length - 4 }}</span>
                </div>
            </div>

            <!-- Price and Actions -->
            <div
                class="flex w-full items-center justify-between border-t border-[var(--foreground_muted_light)]/40 pt-2 dark:border-[var(--foreground_muted_dark)]/40"
            >
                <div class="flex items-center gap-2">
                    <span class="web-text-active text-lg font-bold">
                        {{ props.product.is_discount ? props.product.price - props.product.discount_price : props.product.price }}$
                    </span>
                    <span v-if="props.product.discount_price" class="web-text-body-muted text-sm line-through"> {{ props.product.price }}$ </span>
                </div>

                <button
                    v-if="!props.product.is_out_of_stock"
                    type="button"
                    class="eco-glow-button web-text-for-primary web-bg-primary flex cursor-pointer items-center gap-2 rounded px-2 py-1.5 font-medium"
                >
                    <span class="text-xs">{{ $t('addToCart') }}</span>
                </button>

                <div v-else class="flex items-center gap-1 rounded-md bg-red-500/25 p-2 text-xs dark:bg-red-500/20">
                    <Ban class="web-text-danger size-3.5" />
                    <span class="web-text-danger font-medium">{{ $t('out.of.stock') }}</span>
                </div>
            </div>
        </div>
    </Link>
</template>

<style scoped>
.carousel-item {
    flex: 0 0 350px;
}
@media (max-width: 768px) {
    .carousel-item {
        flex: 0 0 280px;
    }
}
</style>
