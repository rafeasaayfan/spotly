<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuGroup, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Label } from '@/components/ui/label';
import Edit from '@/components/ui/table/actions/Edit.vue';
import View from '@/components/ui/table/actions/View.vue';
import { formatters } from '@/lib/dataTable';
import { Link } from '@inertiajs/vue3';
import {
    Activity,
    AlertCircle,
    CalendarClock,
    CalendarIcon,
    CalendarMinus,
    CheckCircle,
    Clock,
    CopyCheck,
    CopyIcon,
    EllipsisVertical,
    Globe,
    LayoutTemplate,
    TypeOutline,
    XCircle,
} from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    websites: Record<string, any>;
}>();

const copied = ref(false);

const copyUrl = async (subdomain: string) => {
    try {
        const url = 'https://' + subdomain + 'spotly.com';

        await navigator.clipboard.writeText(url);
        copied.value = true;

        setTimeout(() => (copied.value = false), 2000);
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
};
</script>

<template>
    <div
        v-for="website in props.websites"
        :key="website.id"
        class="border-muted flex h-fit flex-col gap-4 rounded-md border bg-white p-3 shadow-lg shadow-black/5 backdrop-blur sm:p-4 dark:bg-black dark:shadow-white/2"
    >
        <div class="border-muted flex w-full items-center justify-between border-b pb-2">
            <div class="flex flex-col">
                <h1 class="text-active text-lg font-bold sm:text-xl">{{ website.name }}</h1>
                <span class="text-body-muted text-xs">
                    {{ website.country ?? '' }} {{ website.country && website.city ? ',' : '' }} {{ website.city ?? '' }}
                </span>
            </div>

            <DropdownMenu>
                <DropdownMenuTrigger :as-child="true">
                    <Button variant="ghost" size="icon" class="relative flex cursor-pointer items-center justify-center rounded-md">
                        <EllipsisVertical class="size-5" />
                    </Button>
                </DropdownMenuTrigger>

                <DropdownMenuContent align="end" class="w-28 p-0 py-1">
                    <DropdownMenuGroup>
                        <div class="grid h-full w-full gap-1">
                            <div v-for="action in ['edit', 'ui', 'show']" :key="action">
                                <Link
                                    :href="route('client.myWebsite.' + action, website.id)"
                                    class="bg-content-2 text-body flex cursor-pointer items-center gap-2 rounded-md px-2 py-2 text-sm transition-all duration-100 ease-in-out"
                                    v-if="website.status === 'denied' ? (action === 'show' ? true : false) : true"
                                >
                                    <template v-if="action === 'edit' && website.status !== 'denied'">
                                        <Edit class="size-5" />
                                        <span>Edit</span>
                                    </template>
                                    <template v-else-if="action === 'ui' && website.status !== 'denied'">
                                        <div
                                            class="flex size-5 cursor-pointer items-center justify-center rounded-md bg-yellow-700/25 text-green-700 hover:bg-yellow-700/35 dark:bg-yellow-600/25 dark:text-yellow-600 hover:dark:bg-yellow-600/35"
                                        >
                                            <LayoutTemplate class="size-3.5" />
                                        </div>
                                        <span>UI</span>
                                    </template>
                                    <template v-else-if="action === 'show'">
                                        <View class="size-5" />
                                        <span>View</span>
                                    </template>
                                </Link>
                            </div>
                        </div>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>

        <div class="flex flex-col gap-5">
            <div class="flex flex-col gap-1">
                <Label class="text-body-muted text-xs">Website URL</Label>
                <div class="flex h-9 w-full items-center justify-between">
                    <div class="flex h-full flex-1 items-center">
                        <div class="bg-card flex h-full w-9 items-center justify-center rounded-s-md">
                            <Globe class="text-body-muted h-4 w-4" />
                        </div>
                        <a
                            :href="`https://${website.subdomain}.spotly.com`"
                            class="text-active-link bg-field border-muted flex h-full flex-1 items-center border-s border-t border-b px-3 text-sm font-bold hover:underline"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            {{ website.subdomain }}.spotly.com
                        </a>
                    </div>

                    <button
                        type="button"
                        @click="copyUrl(website.subdomain)"
                        class="bg-primary text-for-bg-primary flex h-full w-9 cursor-pointer items-center justify-center rounded-e-md transition-all duration-200 ease-in-out"
                        :class="copied ? 'pointer-events-none opacity-50' : ''"
                    >
                        <CopyCheck v-if="copied" class="size-3.5 sm:size-4" />
                        <CopyIcon v-else class="size-3.5 sm:size-4" />
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="bg-popover border-muted flex size-5.5 items-center justify-center rounded-md border">
                        <TypeOutline class="text-body-muted size-3.5" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-body-muted text-xs">Website Type</span>

                        <span class="text-sm font-medium">
                            {{ website.website_type.type }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="bg-popover border-muted flex size-5.5 items-center justify-center rounded-md border">
                        <Clock v-if="website.status === 'pending'" class="text-body-muted size-3.5" />
                        <CheckCircle v-else-if="website.status === 'approved'" class="size-3.5 text-[var(--success)]" />
                        <XCircle v-else-if="website.status === 'denied'" class="text-active-link-2 size-3.5" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-body-muted text-xs">Status</span>
                        <span
                            class="text-sm font-medium"
                            :class="
                                website.status === 'pending' ? '' : website.status === 'approved' ? 'text-[var(--success)]' : 'text-active-link-2'
                            "
                        >
                            {{ website.status }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="bg-popover border-muted flex size-5.5 items-center justify-center rounded-md border">
                        <Activity class="size-3.5" :class="website.is_active ? 'text-[var(--success)]' : 'text-active-link-2'" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-body-muted text-xs">Active Status</span>

                        <span class="text-sm font-medium" :class="website.is_active ? 'text-[var(--success)]' : 'text-active-link-2'">
                            {{ website.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="bg-popover border-muted flex size-5.5 items-center justify-center rounded-md border">
                        <CalendarIcon class="text-body-muted size-3.5" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-body-muted text-xs">Created At</span>
                        <span class="text-sm font-medium">{{ formatters.date(website.created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="website.status !== 'denied'" class="border-muted border-t pt-3">
            <div v-if="false" class="flex flex-col gap-3">
                <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <CalendarClock class="text-body-muted size-3.5" />
                        <div class="flex flex-col">
                            <span class="text-body-muted text-xs">Paid At:</span>
                            <span class="text-sm">2025/8/5</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <CalendarMinus class="text-body-muted size-3.5" />
                        <div class="flex flex-col">
                            <span class="text-body-muted text-xs">End At:</span>
                            <span class="text-sm">2025/9/5</span>
                        </div>
                    </div>
                </div>

                <Button type="button" variant="ghost" class="mt-1">Make a Future Paid</Button>
            </div>
            <div v-else class="flex flex-col gap-2">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex size-6.5 items-center justify-center rounded-full bg-[var(--destructive)]/70 text-sm text-white sm:size-7.5">
                            <AlertCircle class="size-3.5 sm:size-4.5" />
                        </div>
                        <span class="text-xs sm:text-sm">Not Paid</span>
                    </div>
                    <span class="text-body-muted text-xs">The Free 3 Days Finished</span>
                </div>

                <Button type="button" class="mt-1">Pay Now</Button>
            </div>
        </div>
    </div>
</template>
