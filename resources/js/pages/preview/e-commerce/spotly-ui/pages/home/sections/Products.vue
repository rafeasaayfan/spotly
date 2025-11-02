<script setup lang="ts">
import { ChevronRight } from 'lucide-vue-next';
import ProductCard from '../../../components/cards/ProductCard.vue';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const products = [
    {
        id: 4,
        name: 'Wireless Gaming Headset',
        short_description: 'Immerse yourself in gaming with high-fidelity audio and comfort.',
        long_description: 'Experience unparalleled gaming audio with this wireless headset. Featuring 7.1 surround sound, a retractable noise-canceling microphone, and plush earcups for hours of comfortable gaming.',
        price: 150.00,
        sale_price: 129.99,
        category: {
            name: 'Gaming Accessories',
            ar_name: 'ملحقات الألعاب'
        },
        brand: {
            name: 'GameTech'
        },
        is_special: false,
        in_stock_variants: [
            {
                color: '#000000',
                stock_quantity: 60,
                reserved_quantity: 8,
            },
            {
                color: '#FFFFFF',
                stock_quantity: 25,
                reserved_quantity: 3,
            }
        ]
    },
    {
        id: 5,
        name: 'Portable Bluetooth Speaker',
        short_description: 'Enjoy your music on the go with powerful sound and long battery life.',
        long_description: 'Take your music anywhere with this compact and powerful Bluetooth speaker. It delivers crisp highs and deep bass, with up to 15 hours of playtime on a single charge. Waterproof design for outdoor adventures.',
        price: 70.00,
        sale_price: null,
        category: {
            name: 'Audio',
            ar_name: 'صوتيات'
        },
        brand: {
            name: 'SoundLink'
        },
        is_special: false,
        in_stock_variants: [
            {
                color: '#228B22',
                stock_quantity: 40,
                reserved_quantity: 4,
            },
            {
                color: '#800080',
                stock_quantity: 20,
                reserved_quantity: 1,
            }
        ]
    },
    {
        id: 6,
        name: 'Ergonomic Office Chair',
        short_description: 'Work in comfort and style with this adjustable office chair.',
        long_description: 'Upgrade your workspace with an ergonomic office chair designed for ultimate comfort and support. Features adjustable lumbar support, armrests, and seat height to fit your needs.',
        price: 250.00,
        sale_price: 220.00,
        category: {
            name: 'Office Furniture',
            ar_name: 'أثاث مكتبي'
        },
        brand: {
            name: 'ComfyDesk'
        },
        is_special: false,
        in_stock_variants: [
            {
                color: '#808080',
                stock_quantity: 30,
                reserved_quantity: 2,
            },
            {
                color: '#000000',
                stock_quantity: 10,
                reserved_quantity: 0,
            }
        ]
    },
    {
        id: 4,
        name: 'Wireless Gaming Headset',
        short_description: 'Immerse yourself in gaming with high-fidelity audio and comfort.',
        long_description: 'Experience unparalleled gaming audio with this wireless headset. Featuring 7.1 surround sound, a retractable noise-canceling microphone, and plush earcups for hours of comfortable gaming.',
        price: 150.00,
        sale_price: 129.99,
        category: {
            name: 'Gaming Accessories',
            ar_name: 'ملحقات الألعاب'
        },
        brand: {
            name: 'GameTech'
        },
        is_special: false,
        in_stock_variants: [
            {
                color: '#000000',
                stock_quantity: 60,
                reserved_quantity: 8,
            },
            {
                color: '#FFFFFF',
                stock_quantity: 25,
                reserved_quantity: 3,
            }
        ]
    },
    {
        id: 5,
        name: 'Portable Bluetooth Speaker',
        short_description: 'Enjoy your music on the go with powerful sound and long battery life.',
        long_description: 'Take your music anywhere with this compact and powerful Bluetooth speaker. It delivers crisp highs and deep bass, with up to 15 hours of playtime on a single charge. Waterproof design for outdoor adventures.',
        price: 70.00,
        sale_price: null,
        category: {
            name: 'Audio',
            ar_name: 'صوتيات'
        },
        brand: {
            name: 'SoundLink'
        },
        is_special: false,
        in_stock_variants: [
            {
                color: '#228B22',
                stock_quantity: 40,
                reserved_quantity: 4,
            },
            {
                color: '#800080',
                stock_quantity: 20,
                reserved_quantity: 1,
            }
        ]
    },
    {
        id: 6,
        name: 'Ergonomic Office Chair',
        short_description: 'Work in comfort and style with this adjustable office chair.',
        long_description: 'Upgrade your workspace with an ergonomic office chair designed for ultimate comfort and support. Features adjustable lumbar support, armrests, and seat height to fit your needs.',
        price: 250.00,
        sale_price: 220.00,
        category: {
            name: 'Office Furniture',
            ar_name: 'أثاث مكتبي'
        },
        brand: {
            name: 'ComfyDesk'
        },
        is_special: false,
        in_stock_variants: [
            {
                color: '#808080',
                stock_quantity: 30,
                reserved_quantity: 2,
            },
            {
                color: '#000000',
                stock_quantity: 10,
                reserved_quantity: 0,
            }
        ]
    }
];

const page = usePage<SharedData>();
</script>

<template>
    <section v-if="products && products.length > 0" class="py-22" id="eco-products-section">
        <div class="mb-4 flex w-full items-end justify-between gap-3 border-b-4 border-double web-border-color pb-2">
            <h2 class="web-text-active text-3xl font-bold sm:text-4xl lg:text-5xl">
                {{ $t('our') }} <span class="eco-gradient-text">{{ $t('our.products') }}</span>
            </h2>
            <div class="group web-text-body flex items-center gap-0.5 text-sm font-medium cursor-pointer">
                <span>{{ $t('seeAll') }}</span>
                <ChevronRight 
                    class="size-4 transition-all duration-300 ease-in-out"
                    :class="page.props.lang === 'ar' ? 'rotate-180 group-hover:-translate-x-1' : 'group-hover:translate-x-1'" 
                />
            </div>
        </div>

        <div class="relative grid gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            <ProductCard v-for="product in products" :key="product.id" :product="product" />
        </div>

        <div class="flex w-full items-center justify-end border-t-4 border-double web-border-color mt-4">
        </div>
    </section>
</template>
