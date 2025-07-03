<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { PaginationBtn } from '@/components/ui/pagination';
import { formatPaginationLinks } from '@/lib/pagination';
import { Link } from '@inertiajs/vue3';
import { GridIcon, ListIcon, Pin, ShoppingCart } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// Mock product data with more fields
const allProducts = [
    {
        id: 1,
        name: 'Nike Air Max 270',
        description: 'Experience seamless connectivity and style with this cutting-edge smartwatch.',
        image: '/images/img.jpg',
        price: 120,
        oldPrice: 150,
        brand: 'Nike',
        category: 'Shoes',
        discount: 20,
        color: 'red',
        rating: 4.5,
        inStock: true,
        freeShipping: true,
        onSale: true,
    },
    {
        id: 2,
        name: 'Samsung Galaxy Watch',
        description: 'Smartwatch with advanced health tracking and long battery life.',
        image: '/images/img2.jpeg',
        price: 199,
        oldPrice: 249,
        brand: 'Samsung',
        category: 'Watches',
        discount: 20,
        color: 'black',
        rating: 4.0,
        inStock: false,
        freeShipping: false,
        onSale: false,
    },
    {
        id: 3,
        name: 'Adidas Ultraboost',
        description: 'High-performance running shoes for comfort and speed.',
        image: '/images/img.jpg',
        price: 140,
        oldPrice: 180,
        brand: 'Adidas',
        category: 'Shoes',
        discount: 22,
        color: 'blue',
        rating: 5.0,
        inStock: true,
        freeShipping: true,
        onSale: false,
    },
    {
        id: 4,
        name: 'Apple Watch Series 8',
        description: 'The latest Apple Watch with new health features.',
        image: '/images/img2.jpeg',
        price: 399,
        oldPrice: 429,
        brand: 'Apple',
        category: 'Watches',
        discount: 7,
        color: 'white',
        rating: 4.8,
        inStock: true,
        freeShipping: false,
        onSale: true,
    },
    {
        id: 5,
        name: 'Sony WH-1000XM4',
        description: 'Industry-leading noise canceling headphones.',
        image: '/images/img.jpg',
        price: 299,
        oldPrice: 349,
        brand: 'Sony',
        category: 'Headphones',
        discount: 14,
        color: 'gray',
        rating: 4.2,
        inStock: false,
        freeShipping: true,
        onSale: false,
    },
    {
        id: 6,
        name: 'JBL Flip 6',
        description: 'Portable waterproof speaker with powerful sound.',
        image: '/images/img2.jpeg',
        price: 99,
        oldPrice: 129,
        brand: 'JBL',
        category: 'Speakers',
        discount: 23,
        color: 'green',
        rating: 3.8,
        inStock: true,
        freeShipping: true,
        onSale: true,
    },
    // Add more products as needed
];

const selectedCategory = ref('');
const selectedBrand = ref('');
const selectedColor = ref('');
const selectedRating = ref('');
const selectedAvailability = ref('');
const search = ref('');
const minPrice = ref('');
const maxPrice = ref('');
const freeShipping = ref(false);
const onSale = ref(false);

// Pagination
const perPage = 6;
const currentPage = ref(1);

const filteredProducts = computed(() => {
    let products = allProducts;
    if (search.value) {
        products = products.filter(
            (p) => p.name.toLowerCase().includes(search.value.toLowerCase()) || p.description.toLowerCase().includes(search.value.toLowerCase()),
        );
    }
    if (selectedCategory.value) {
        products = products.filter((p) => p.category === selectedCategory.value);
    }
    if (selectedBrand.value) {
        products = products.filter((p) => p.brand === selectedBrand.value);
    }
    if (minPrice.value) {
        products = products.filter((p) => p.price >= Number(minPrice.value));
    }
    if (maxPrice.value) {
        products = products.filter((p) => p.price <= Number(maxPrice.value));
    }
    if (selectedColor.value) {
        products = products.filter((p) => p.color === selectedColor.value);
    }
    if (selectedRating.value) {
        const min = Number(selectedRating.value);
        products = products.filter((p) => p.rating >= min);
    }
    if (selectedAvailability.value) {
        if (selectedAvailability.value === 'in') products = products.filter((p) => p.inStock);
        else if (selectedAvailability.value === 'out') products = products.filter((p) => !p.inStock);
    }
    if (freeShipping.value) {
        products = products.filter((p) => p.freeShipping);
    }
    if (onSale.value) {
        products = products.filter((p) => p.onSale);
    }
    return products;
});

const paginatedProducts = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    return filteredProducts.value.slice(start, start + perPage);
});

const totalPages = computed(() => Math.ceil(filteredProducts.value.length / perPage));

const paginationLinks = computed(() => {
    const links = [];
    for (let i = 1; i <= totalPages.value; i++) {
        links.push({ url: '#', label: i.toString(), active: i === currentPage.value });
    }
    return formatPaginationLinks(links);
});

function goToPage(page: number) {
    currentPage.value = page;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<template>
    <div class="col-span-3 flex flex-col items-center justify-center gap-5">
        <div class="flex w-full items-center justify-between gap-2 border-b border-muted pb-3">
            <h3 class="text-active text-lg font-bold">Products</h3>

            <div class="flex items-center gap-2">
                <!-- grid view -->
                <Button variant="outline" size="sm">
                    <GridIcon class="size-4" />
                    Grid View
                </Button>

                <!-- list view -->
                <Button variant="outline" size="sm">
                    <ListIcon class="size-4" />
                    List View
                </Button>

                <!-- sort by -->
                <SelectWithSearch :options="['Newest', 'Oldest', 'Price: Low to High', 'Price: High to Low']" placeholder="Sort by" class="w-full" />
            </div>
        </div>

        <div v-if="paginatedProducts.length === 0" class="text-body-muted py-20 text-center text-lg">No products found.</div>

        <div v-else class="relative grid grid-cols-1 gap-x-5 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
            <Link
                v-for="product in paginatedProducts"
                :key="product.id"
                href="#"
                class="product-card-item ethereal-card group relative flex max-h-[30rem] flex-col gap-5 rounded-xl bg-black/5 p-4 backdrop-blur-md transition duration-300 dark:bg-white/5"
            >
                <div class="absolute start-2 -top-1 z-10" ref="badgeRef">
                    <div class="bg-destructive relative flex flex-col rounded-t rounded-b px-1.5 pt-6 pb-3 text-xs text-white shadow">
                        <div class="absolute start-0 -top-1 flex w-full items-center justify-center">
                            <Pin class="size-4 fill-white shadow-xl" />
                        </div>

                        <span>20%</span>
                        <span>OFF</span>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-xl shadow">
                    <img
                        :src="'/images/img.jpg'"
                        class="w-full min-w-[20rem] rounded-xl bg-center object-cover transition-all duration-300 ease-in-out group-hover:scale-120"
                        alt="Product"
                    />

                    <div class="absolute start-3 bottom-2">
                        <span class="text-active-link rounded-full bg-white/90 px-2 py-1 text-base font-bold backdrop-blur"> Samsung </span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="flex flex-col">
                        <h3 class="text-active text-lg font-semibold">Nike Air Max 270</h3>
                        <p class="line-clamp-2 text-sm text-gray-500">
                            Experience seamless connectivity and style with this cutting-edge smartwatch.
                        </p>
                    </div>

                    <p class="text-sm font-medium">Men's Running Shoes</p>
                </div>

                <div class="flex w-full items-center justify-between border-t border-gray-500/30 pt-3">
                    <div class="flex items-center gap-2">
                        <span class="text-active text-lg font-bold">$120</span>
                        <span class="text-body-muted text-sm line-through">$150</span>
                    </div>

                    <Link
                        href="#"
                        class="bg-primary flex items-center gap-2 rounded px-4 py-2 text-sm text-white shadow transition duration-300 ease-in-out hover:scale-103 active:scale-98"
                    >
                        <ShoppingCart class="size-4" />
                        <span>Add to Cart</span>
                    </Link>
                </div>
            </Link>
        </div>

        <div v-if="totalPages > 1" class="mt-10 flex justify-center gap-2">
            <PaginationBtn
                v-for="link in paginationLinks"
                :key="link.label"
                :active="link.active"
                @click="!link.active && goToPage(Number(link.label))"
            >
                {{ link.label }}
            </PaginationBtn>
        </div>
    </div>
</template>

<style>
.ethereal-card:hover {
    transform: translateY(-10px) !important;
}
</style>