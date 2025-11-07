<script setup lang="ts">
import { formatters } from '@/lib/dataTable';
import { typeColor } from '@/lib/websiteTypes';

const props = defineProps<{
    data: Record<string, any>;
}>();
</script>

<template>
    <div class="relative pb-3">
        <div class="border-muted relative z-10 grid grid-cols-1 gap-5 border-b px-4">
            <!-- Payment Details -->
            <div class="border-muted grid gap-4">
                <!-- General Information -->
                <div class="border-muted flex flex-col gap-2 border-b pb-4">
                    <h3 class="text-active-link text-2xl font-bold">{{ props.data.user.name }}</h3>
                    <div class="flex flex-wrap items-center gap-2">
                        <div
                            v-if="props.data.status"
                            :class="[
                                'rounded px-3 py-1.5 text-sm',
                                props.data.status === 'pending'
                                    ? 'bg-content text-active'
                                    : props.data.status === 'active'
                                      ? 'bg-success text-for-bg-success'
                                      : 'bg-destructive text-for-bg-destructive',
                            ]"
                        >
                            {{ props.data.status }}
                        </div>
                        <div class="text-white rounded bg-gradient-to-br from-amber-600 to-amber-500 px-3 py-1.5 text-sm dark:from-amber-700 dark:to-amber-800">
                            {{ props.data.payment_method?.name ?? 'whish' }}
                        </div>
                    </div>
                </div>

                <div v-if="props.data.user" class="border-muted border-muted flex flex-col gap-3 border-b pb-4">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        User Email:
                        <a :href="`mailto:${props.data.user.email}`" class="text-body text-base font-medium">
                            {{ props.data.user.email }}
                        </a>
                    </p>
                    <p v-if="props.data.user.phone_number" class="text-body-muted flex w-full items-center justify-between text-sm">
                        User Number:
                        <a :href="`tel:${props.data.user.phone_number}`" class="text-body text-base font-medium">
                            {{ props.data.user.phone_number }}
                        </a>
                    </p>
                </div>

                <div class="border-muted flex flex-col gap-3 border-b pb-4">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Website URL:
                        <a :href="props.data.website.subdomain + '.spotly.com'" target="_blank" rel="noopener" class="text-active-link text-base font-medium">
                            {{ props.data.website.subdomain }}.spotly.com
                        </a>
                    </p>
                    <p v-if="props.data.website.email" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Website Email:
                        <a :href="`mailto:${props.data.email}`" class="text-body text-base font-medium">{{ props.data.email }}</a>
                    </p>
                    <p v-if="props.data.website.phone_number" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Website Number:
                        <a :href="`tel:${props.data.website.phone_number}`" class="text-body text-base font-medium">{{ props.data.website.phone_number }}</a>
                    </p>
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Website Activation:
                        <span v-html="formatters.active(props.data.website.active)"></span>
                    </p>
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Website Status:
                        <span v-html="formatters.status(props.data.website.status)"></span>
                    </p>
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Website Type:
                        <span class="px-2 py-1 rounded text-xs" :class="typeColor.bg(props.data.website.website_type.type)">
                            {{ props.data.website.website_type.type }}
                        </span>
                    </p>
                </div>

                <div class="flex flex-col gap-3">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Plan Name: <span class="text-body text-base font-medium">{{ props.data.plan.name }}</span>
                    </p>
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Plan Price: <span class="text-body text-base font-medium">{{ props.data.plan.price }}$</span>
                    </p>
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Plan Duration: <span class="text-body text-base font-medium">{{ props.data.plan.duration }}</span>
                    </p>
                </div>

                <div class="border-muted flex flex-col gap-3 border-t border-b py-4">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Start Date: <span class="text-body text-base font-medium">{{ formatters.date(props.data.start_date, 'long') }}</span>
                    </p>
                    
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        End Date: <span class="text-body text-base font-medium">{{ formatters.date(props.data.end_date, 'long') }}</span>
                    </p>
                </div>
            </div>
        </div>

        <p class="text-body-muted flex items-center justify-between gap-1 px-4 pt-4 text-xs">
            Created At: <span class="font-medium">{{ formatters.date(props.data.created_at, 'long') }}</span>
        </p>
        <p class="text-body-muted flex items-center justify-between gap-1 px-4 pt-4 text-xs">
            Updated At: <span class="font-medium">{{ formatters.date(props.data.updated_at, 'long') }}</span>
        </p>
    </div>
</template>
