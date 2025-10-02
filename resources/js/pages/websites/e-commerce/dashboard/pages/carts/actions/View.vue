const columns = [ { key: 'websiteUser_name', label: 'User', type: '' }, { key: 'expires_at', label: 'Expires At', type: 'date' }, { key: 'created_at',
label: 'Created At', type: 'date' }, { key: 'updated_at', label: 'Updated At', type: 'date' }, ];
<script setup lang="ts">
import Image from '@/components/ui/image/Image.vue';
import { formatters } from '@/lib/dataTable';

const props = defineProps<{
    data: Record<string, any>;
}>();
</script>

<template>
    <div class="relative pb-3">
        <div class="border-muted relative z-10 grid grid-cols-1 gap-5 border-b px-4">
            <!-- Cart Details -->
            <div class="grid grid-cols-1 gap-4">
                <!-- General Information -->
                <div class="border-muted flex flex-col gap-1 border-b pb-4">
                    <p v-if="props.data.websiteUser_name" class="text-sm text-body-muted">Cart For:</p>
                    <h3 class="text-active-link text-xl font-bold">{{ props.data.websiteUser_name ?? 'Session Cart' }}</h3>
                </div>

                <div class="border-muted border-muted flex flex-col gap-3 border-b pb-4">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Items Count: <span class="text-active text-base font-medium">{{ props.data.items_count }}</span>
                    </p>
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Total Price:
                        <span class="text-active text-base font-medium">
                            {{
                                Array.isArray(props.data.items)
                                    ? props.data.items.reduce((sum, item) => sum + item.quantity * item.unit_price, 0)
                                    : 0
                            }}$
                        </span>
                    </p>
                </div>

                <div class="border-muted flex flex-col gap-5 border-b pb-4">
                    <p class="flex w-full items-center justify-between text-sm">
                        <span class="text-body-muted">status:</span>
                        <span v-html="formatters.status(props.data.status)"></span>
                    </p>
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Expires At: <span>{{ formatters.date(props.data.expires_at, 'long') }}</span>
                    </p>
                </div>
            </div>

            <!-- Cart Items Section -->
            <div class="mb-3 flex flex-col gap-2 rounded-md bg-[var(--primary)]/10 p-2.5 lg:p-4 dark:bg-[var(--primary)]/5">
                <h4 class="text-body font-bold">Cart Items:</h4>
                <div
                    v-if="props.data.items && props.data.items.length"
                    class="custom-scrollbar grid max-h-[60vh] gap-2.5 overflow-y-auto md:grid-cols-2 lg:gap-4"
                >
                    <div v-for="item in props.data.items" :key="item.id" class="bg-body flex flex-col gap-3 rounded-md p-2.5 lg:p-4">
                        <div class="border-muted flex w-full items-center gap-2 border-b pb-2">
                            <Image v-if="item.imageUrl" :src="item.imageUrl" alt="Item Image" class="size-18 rounded-full object-cover shadow" />
                            <div
                                v-else
                                class="border-muted text-body-muted flex size-18 items-center justify-center rounded-full border text-center text-xs"
                            >
                                No Image
                            </div>
                            <p class="font-bold text-active-link">{{ item.product.name }}</p>
                        </div>
                        <div v-if="item.color" class="flex items-center justify-between gap-2">
                            <span class="text-body-muted text-sm">Color:</span>
                            <div class="flex items-center gap-1">
                                <span :style="{ backgroundColor: item.color }" class="border-muted size-5.5 rounded-full border shadow-sm"></span>
                                <span class="text-body-muted text-xs font-medium">{{ item.color }}</span>
                            </div>
                        </div>
                        <p class="text-body-muted flex items-center justify-between gap-2 text-sm">
                            Quantity: <span class="text-body text-base font-bold">{{ item.quantity }}</span>
                        </p>
                        <p class="text-body-muted flex items-center justify-between gap-2 text-sm">
                            Unit Price: <span class="text-body text-base font-bold">{{ item.unit_price }}$</span>
                        </p>
                        <p class="text-body-muted flex items-center justify-between gap-2 text-sm">
                            Total Price: <span class="text-body text-base font-bold">{{ item.unit_price * item.quantity }}$</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-body-muted relative z-10 flex items-center justify-between gap-1 px-4 pt-4 text-xs">
            Created At: <span class="font-medium">{{ formatters.date(props.data.created_at, 'long') }}</span>
        </p>
    </div>
</template>
