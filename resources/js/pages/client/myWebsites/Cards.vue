<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import Delete from '@/components/ui/table/actions/Delete.vue';
import Edit from '@/components/ui/table/actions/Edit.vue';
import View from '@/components/ui/table/actions/View.vue';
import { Activity, CalendarClock, CalendarIcon, CalendarMinus, CheckCircle, Clock, CopyIcon, DiamondPlus, Globe, XCircle } from 'lucide-vue-next';

const props = defineProps<{
    websites: Record<string, any>;
}>();
</script>

<template>
    <div
        v-for="website in props.websites"
        :key="website.id"
        class="border-muted flex flex-col gap-4 rounded-md border p-4 shadow-lg backdrop-blur-2xl dark:shadow-white/5"
    >
        <div class="border-muted flex w-full items-center justify-between border-b pb-2">
            <div class="flex flex-col">
                <h1 class="text-active text-xl font-bold">{{ website.name }}</h1>
                <span class="text-body-muted text-sm">
                    {{ website.country ?? '' }} {{ website.country && website.city ? ',' : '' }} {{ website.city ?? '' }}
                </span>
            </div>

            <div class="flex items-center gap-2">
                <View />
                <Edit />
                <Delete />
            </div>
        </div>

        <div class="mt-1 flex flex-col gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-2 rounded-xl bg-purple-400 px-3 py-2 dark:bg-purple-950">
                    <!-- <TypeOutline class="size-4" /> -->
                    <span class="text-sm font-medium">{{ website.website_type.type }}</span>
                </div>

                <div
                    class="flex items-center gap-2 rounded-xl px-3 py-2"
                    :class="
                        website.status === 'pending'
                            ? 'bg-gray-300 dark:bg-gray-700'
                            : website.status === 'approved'
                              ? 'bg-success'
                              : 'bg-destructive'
                    "
                >
                    <Clock v-if="website.status === 'pending'" class="size-4" />
                    <CheckCircle v-else-if="website.status === 'approved'" class="size-4" />
                    <XCircle v-else class="size-4" />
                    <span class="text-sm font-medium">{{ website.status }}</span>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <Label>Subdomain</Label>
                <div class="flex items-center">
                    <div class="flex h-full w-10 items-center justify-center rounded-s-md bg-[var(--primary)]/15">
                        <DiamondPlus class="h-4 w-4 text-gray-500" />
                    </div>
                    <div class="flex-1 rounded-e-md bg-[var(--primary)]/20 px-3 py-3 text-sm">{{ website.subdomain }}</div>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <Label>Website URL</Label>
                <div class="flex w-full items-center justify-between">
                    <div class="flex h-full flex-1 items-center">
                        <div class="flex h-full w-10 items-center justify-center rounded-s-md bg-[var(--primary)]/15">
                            <Globe class="h-4 w-4 text-gray-500" />
                        </div>
                        <a
                            :href="`https://${website.subdomain}.spotly.com`"
                            class="text-active-link flex-1 bg-[var(--primary)]/20 px-3 py-3 text-sm font-bold hover:underline"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            {{ website.subdomain }}.spotly.com
                        </a>
                    </div>

                    <button
                        type="button"
                        class="flex h-full w-10 cursor-pointer items-center justify-center rounded-e-md bg-[var(--primary)]/30 hover:bg-[var(--primary)]/35"
                    >
                        <CopyIcon class="size-4" />
                    </button>
                </div>
            </div>

            <div class="mt-1 flex items-center justify-between gap-2">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-1">
                        <div class="flex size-6 items-center justify-center rounded-md bg-yellow-500/30">
                            <CalendarIcon class="size-4" />
                        </div>
                        <span class="text-sm">Created At</span>
                    </div>

                    <span class="text-sm">{{ website.created_at }}</span>
                </div>

                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-1">
                        <div class="flex size-6 items-center justify-center rounded-md bg-[var(--success)]/30">
                            <Activity class="size-4" />
                        </div>
                        <span class="text-sm">Status</span>
                    </div>

                    <span class="text-sm font-bold" :class="website.is_active ? 'text-[var(--success)]' : 'text-active-link-2'">
                        {{ website.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <div class="border-muted border-t pt-3">
                <div v-if="false" class="flex flex-col gap-3">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1">
                                <CalendarClock class="size-4" />
                                <span class="text-sm">Paid At:</span>
                            </div>

                            <span class="text-sm">2025/8/5</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1">
                                <CalendarMinus class="size-4" />
                                <span class="text-sm">End At:</span>
                            </div>

                            <span class="text-sm">2025/9/5</span>
                        </div>
                    </div>

                    <Button type="button" variant="ghost" class="mt-1">Make a Future Paid</Button>
                </div>
                <div v-else class="flex flex-col gap-2">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex size-8 items-center justify-center rounded-full bg-[var(--destructive)]/30 text-sm">!</div>
                            <span class="text-sm">Not Paid</span>
                        </div>
                        <span class="text-body-muted text-xs">The Free 3 Days Finished</span>
                    </div>

                    <Button type="button" class="mt-1">Pay Now</Button>
                </div>
            </div>
        </div>
    </div>
</template>
