<script setup lang="ts">
import Image from '@/components/ui/image/Image.vue';
import { formatters } from '@/lib/dataTable';
import { typeColor } from '@/lib/websiteTypes';
import { PlusCircle } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
}>();
</script>

<template>
    <div class="relative pb-3">
        <div class="border-muted relative z-10 grid grid-cols-1 gap-5 border-b px-4">
            <!-- Website Details -->
            <div class="border-muted grid gap-4">
                <!-- General Information -->
                <div class="border-muted flex flex-col gap-2 border-b pb-4">
                    <h3 class="text-active-link text-2xl font-bold">{{ props.data.name }}</h3>
                    <div class="flex flex-wrap items-center gap-2">
                        <div v-if="props.data.websiteType_type" :class="['rounded px-3 py-1.5 text-sm', typeColor.bg(props.data.websiteType_type)]">
                            {{ props.data.websiteType_type }}
                        </div>

                        <div
                            :class="[
                                'rounded px-3 py-1.5 text-sm',
                                props.data.is_active ? 'bg-primary text-for-bg-primary' : 'bg-destructive text-for-bg-destructive',
                            ]"
                        >
                            {{ props.data.is_active ? 'Active' : 'Inactive' }}
                        </div>

                        <div
                            v-if="props.data.status"
                            :class="[
                                'rounded px-3 py-1.5 text-sm',
                                props.data.status === 'pending'
                                    ? 'bg-content text-active'
                                    : props.data.status === 'denied'
                                      ? 'bg-destructive text-for-bg-destructive'
                                      : 'bg-success text-for-bg-success',
                            ]"
                        >
                            {{ props.data.status }}
                        </div>
                    </div>
                </div>

                <div v-if="props.data.owner" class="border-muted border-muted flex flex-col gap-3 border-b pb-4">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Owner Name: <span class="text-body text-base font-medium">{{ props.data.owner.name }}</span>
                    </p>
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Owner Email:
                        <a :href="`mailto:${props.data.owner.email}`" class="text-body text-base font-medium">
                            {{ props.data.owner.email }}
                        </a>
                    </p>
                    <p v-if="props.data.owner.phone_number" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Owner Number:
                        <a :href="`tel:${props.data.owner.phone_number}`" class="text-body text-base font-medium">
                            {{ props.data.owner.phone_number }}
                        </a>
                    </p>
                </div>

                <div v-if="props.data.approvedOrDeniedBy_name" class="border-muted border-muted flex flex-col gap-3 border-b pb-4">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        {{ props.data.status === 'approved' ? 'Approved' : 'Denied' }} By:
                        <span class="text-body text-base font-medium">{{ props.data.approvedOrDeniedBy_name }}</span>
                    </p>
                </div>

                <div class="border-muted flex flex-col gap-3 border-b pb-4">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Subdomain: <span class="text-active-link text-base font-medium">{{ props.data.subdomain }}</span>
                    </p>
                    <p v-if="props.data.email" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Email:
                        <a :href="`mailto:${props.data.email}`" class="text-body text-base font-medium">{{ props.data.email }}</a>
                    </p>
                    <p v-if="props.data.phone_number" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Phone Number:
                        <a :href="`tel:${props.data.phone_number}`" class="text-body text-base font-medium">{{ props.data.phone_number }}</a>
                    </p>
                </div>

                <div class="flex flex-col gap-3">
                    <p v-if="props.data.country" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Country: <span class="text-body text-base font-medium">{{ props.data.country }}</span>
                    </p>
                    <p v-if="props.data.city" class="text-body-muted flex w-full items-center justify-between text-sm">
                        City: <span class="text-body text-base font-medium">{{ props.data.city }}</span>
                    </p>

                    <p v-if="props.data.address" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Address: <span class="text-body text-base font-medium">{{ props.data.address }}</span>
                    </p>
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Default Language: <span class="text-body text-base font-medium">{{ props.data.language }}</span>
                    </p>
                    <p v-if="props.data.views_count" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Website Viewer: <span class="text-body text-base font-medium">{{ props.data.views_count }}</span>
                    </p>
                </div>

                <div class="border-muted flex flex-col gap-3 border-t border-b py-4">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Website URL:
                        <a :href="props.data.subdomain + '.spotly.com'" target="_blank" rel="noopener" class="text-active-link text-base font-medium">
                            {{ props.data.subdomain }}.spotly.com
                        </a>
                    </p>
                    <p v-if="props.data.instagram" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Instagram:
                        <a :href="props.data.instagram" target="_blank" rel="noopener" class="text-active-link text-base font-medium">
                            {{ props.data.instagram }}
                        </a>
                    </p>
                    <p v-if="props.data.facebook" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Facebook:
                        <a :href="props.data.facebook" target="_blank" rel="noopener" class="text-active-link text-base font-medium">
                            {{ props.data.facebook }}
                        </a>
                    </p>
                    <p v-if="props.data.tiktok" class="text-body-muted flex w-full items-center justify-between text-sm">
                        TikTok:
                        <a :href="props.data.tiktok" target="_blank" rel="noopener" class="text-active-link text-base font-medium">
                            {{ props.data.tiktok }}
                        </a>
                    </p>
                    <p v-if="props.data.youtube" class="text-body-muted flex w-full items-center justify-between text-sm">
                        YouTube:
                        <a :href="props.data.youtube" target="_blank" rel="noopener" class="text-active-link text-base font-medium">
                            {{ props.data.youtube }}
                        </a>
                    </p>
                </div>

                <div v-if="props.data.light_logo && props.data.dark_logo" class="border-muted flex flex-col gap-3 border-b pb-4">
                    <span class="text-body-muted text-sm">Logo:</span>
                    <div class="flex items-center gap-3">
                        <Image :src="props.data.light_logo" class="size-30 rounded-full shadow" />
                        <Image :src="props.data.dark_logo" class="size-30 rounded-full shadow" />
                    </div>
                </div>

                <div class="border-muted flex flex-col gap-3 border-b pb-4">
                    <!-- about_us -->
                    <div v-if="props.data.about_us" class="flex flex-col gap-2">
                        <h4 class="text-body-muted text-sm">About Us (en):</h4>
                        <p class="text-body text-base font-medium whitespace-pre-line">{{ props.data.about_us }}</p>
                    </div>

                    <!-- about_us_ar -->
                    <div v-if="props.data.about_us_ar" class="flex flex-col gap-2">
                        <h4 class="text-body-muted text-sm font-semibold">About Us (ar):</h4>
                        <p class="text-body text-base font-medium whitespace-pre-line">{{ props.data.about_us }}</p>
                    </div>
                </div>

                <div
                    v-if="props.data.active_website_template.template && props.data.active_website_template.template_color"
                    class="item-center flex w-full justify-between gap-3 pb-4"
                    :class="props.data.subscription ? 'border-muted border-b' : ''"
                >
                    <span class="text-body-muted text-sm">Active Template:</span>
                    <div class="flex items-center gap-2">
                        <div class="bg-content border-muted text-active rounded border px-2 py-1 text-xs shadow">
                            {{ props.data.active_website_template.template.name }}
                        </div>
                        <PlusCircle class="text-body size-4" />
                        <div class="bg-content border-muted text-active rounded border px-2 py-1 text-xs shadow">
                            {{ props.data.active_website_template.template_color.name }}
                        </div>
                    </div>
                </div>

                <div v-if="props.data.subscription" class="flex flex-col gap-3 pb-4">
                    <div class="text-body-muted flex w-full items-center justify-between text-sm">
                        Subscription Status:
                        <div v-html="formatters.status(props.data.subscription.status)"></div>
                    </div>
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Start Date:
                        <span class="text-body text-base font-medium">{{ formatters.date(props.data.subscription.start_date, 'long') }}</span>
                    </p>
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        End Date: <span class="text-body text-base font-medium">{{ formatters.date(props.data.subscription.end_date, 'long') }}</span>
                    </p>

                    <template v-if="props.data.subscription.plan">
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Plan Name: <span class="text-body text-base font-medium">{{ props.data.subscription.plan.name }}</span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Plan Price: <span class="text-body text-base font-medium">{{ props.data.subscription.plan.price }}$</span>
                        </p>
                        <p class="text-body-muted flex w-full items-center justify-between text-sm">
                            Plan Duration: <span class="text-body text-base font-medium">{{ props.data.subscription.plan.duration }}</span>
                        </p>
                    </template>
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
