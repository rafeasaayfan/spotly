<script setup lang="ts">
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { PackageX, SearchX } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    status: string;
    noOrdersFor: string;
}>();

const condition = computed(() => props.noOrdersFor);

const page = usePage<SharedData>();
    
const translatedStatus = (): string => {
    if (props.status === 'confirmed') {
        return page.props.lang === 'ar' ? 'تم تأكيدها' : 'confirmed';
    }
    if (props.status === 'delivered') {
        return page.props.lang === 'ar' ? 'تم توصيلها' : 'delivered';
    }
    if (props.status === 'cancelled') {
        return page.props.lang === 'ar' ? 'تم الغاءها' : 'cancelled';
    }
    if (props.status === 'refunded') {
        return page.props.lang === 'ar' ? 'تم استردادها' : 'refunded';
    }
    return page.props.lang === 'ar' ? 'قيد الانتظار' : 'pending';
};
</script>

<template>
    <div class="web-border-color col-span-2 flex min-h-full flex-col items-center justify-center bg-black/1 dark:bg-white/1 shadow rounded-md">
        <template v-if="condition === 'noStatusData'">
            <PackageX class="web-text-body-muted mb-4 size-12 lg:mb-6 lg:size-15" />

            <h2 class="mb-1 text-base font-semibold lg:text-lg">{{ $t('dont.have.any') }} {{ translatedStatus() }} {{ $t('yet') }}</h2>
            <p v-if="props.status === 'pending'" class="web-text-body-muted mb-6 max-w-md text-center text-xs lg:text-sm">
                {{ $t('no.order.message') }}
            </p>

            <Link v-if="props.status === 'pending'" href="/shop" class="web-bg-primary web-text-for-primary rounded-md px-4 py-2 text-sm font-medium">
                {{ $t('go.to.shop') }}
            </Link>
        </template>

        <template v-else>
            <SearchX class="web-text-body-muted mb-4 size-12 lg:mb-6 lg:size-15" />
            <h2 class="mb-1 text-base font-semibold lg:text-lg">{{ $t('no.order') }}</h2>
        </template>
    </div>
</template>
