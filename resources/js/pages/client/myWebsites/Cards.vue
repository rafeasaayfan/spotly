<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuGroup, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Label } from '@/components/ui/label';
import Edit from '@/components/ui/table/actions/Edit.vue';
import View from '@/components/ui/table/actions/View.vue';
import { formatters } from '@/lib/dataTable';
import { typeColor } from '@/lib/websiteTypes';
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    Activity,
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
    cities: Record<string, any>;
}>();

const page = usePage<SharedData>();

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

const calculateTime = (end_date: string) => {
    const now = new Date();
    const endDate = new Date(end_date);
    const diff = endDate.getTime() - now.getTime();

    if (diff <= 0) {
        return page.props.lang === 'ar' ? 'منتهية الصلاحية' : 'Expired';
    }

    const diffInHours = diff / (1000 * 60 * 60);

    if (diffInHours >= 24) {
        const diffInDays = Math.ceil(diffInHours / 24);
        return `${diffInDays} ${page.props.lang === 'ar' ? 'أيام' : 'days'}`;
    } else if (diffInHours >= 1) {
        const roundedHours = Math.ceil(diffInHours);
        return `${roundedHours} ${page.props.lang === 'ar' ? 'ساعات' : 'hours'}`;
    } else {
        return page.props.lang === 'ar' ? 'أقل من ساعة' : 'less 1 hour';
    }
};

const subscriptionStatus = (status: string) => {
    const result = {
        class: '',
        text: '',
    };

    switch (status) {
        case 'free_trial':
            result.class = 'text-body-muted border-muted';
            result.text = page.props.lang === 'ar' ? 'تجربة مجانية' : 'Free Trial';
            break;

        case 'active':
            result.class = 'text-active-link border-[var(--primary)]/20';
            result.text = page.props.lang === 'ar' ? 'مدفوع' : 'Paid';
            break;

        case 'cancelled':
            result.class = 'text-active-link-2 border-[var(--destructive)]/20';
            result.text = page.props.lang === 'ar' ? 'ملغاة' : 'Cancelled';
            break;

        case 'expired':
            result.class = 'text-active-link-2 border-[var(--destructive)]/20';
            result.text = page.props.lang === 'ar' ? 'منتهية الصلاحية' : 'Expired';
            break;

        case 'pending':
            result.class = 'text-body-muted border-muted';
            result.text = page.props.lang === 'ar' ? 'معلقة' : 'Pending';
            break;

        default:
            break;
    }
    return result;
};

const translatedCountry = (item: any): string => {
    return page.props.lang === 'ar' ? 'لبنان' : item.country;
}

const translatedCity = (cityEn: string): string => {
    const city = props.cities[cityEn];
    if (!city) return cityEn;  
    return page.props.lang === 'ar' ? city.ar : city.en;
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
                    {{ translatedCountry(website) ?? '' }} {{ translatedCountry(website) && translatedCity(website.city) ? ',' : '' }} {{ translatedCity(website.city) ?? '' }}
                </span>
            </div>

            <DropdownMenu>
                <DropdownMenuTrigger :as-child="true">
                    <Button variant="ghost" size="icon" class="relative flex cursor-pointer items-center justify-center rounded-md">
                        <EllipsisVertical class="size-5" />
                    </Button>
                </DropdownMenuTrigger>

                <DropdownMenuContent class="w-fit p-0 py-1">
                    <DropdownMenuGroup>
                        <div class="grid h-full w-full gap-1">
                            <div v-for="action in ['edit', 'show', 'ui']" :key="action">
                                <Link
                                    :href="route('client.myWebsite.' + action, website.id)"
                                    class="bg-content-2 text-body flex cursor-pointer items-center gap-2 rounded-md px-2 py-2 text-sm transition-all duration-100 ease-in-out"
                                    v-if="website.status === 'denied' ? (action === 'show' ? true : false) : true"
                                >
                                    <template v-if="action === 'edit' && website.status !== 'denied'">
                                        <Edit class="size-5" />
                                        <span>{{ $t('edit') }}</span>
                                    </template>
                                    <template v-else-if="action === 'show'">
                                        <View class="size-5" />
                                        <span>{{ $t('view') }}</span>
                                    </template>
                                    <template v-else-if="action === 'ui' && website.status !== 'denied'">
                                        <div
                                            class="flex size-5 cursor-pointer items-center justify-center rounded-md bg-yellow-700/25 text-green-700 hover:bg-yellow-700/35 dark:bg-yellow-600/25 dark:text-yellow-600 hover:dark:bg-yellow-600/35"
                                        >
                                            <LayoutTemplate class="size-3.5" />
                                        </div>
                                        <span>{{ $t('ui') }}</span>
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
                <Label class="text-body-muted text-xs">{{ $t('myWebsites.website_url') }}</Label>
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
                        class="bg-[var(--primary)]/80 dark:bg-[var(--primary)]/50 hover:bg-[var(--primary)] hover:dark:bg-[var(--primary)] text-for-bg-primary flex h-full w-9 cursor-pointer items-center 
                        justify-center rounded-e-md transition-all duration-200 ease-in-out"
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
                        <span class="text-body-muted text-xs">{{ $t('myWebsites.website_type') }}</span>

                        <span class="text-sm font-medium" :class="typeColor.text(website.website_type.type)">
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
                        <span class="text-body-muted text-xs">{{ $t('myWebsites.status') }}</span>
                        <span
                            class="text-sm font-medium"
                            :class="
                                website.status === 'pending' ? '' : website.status === 'approved' ? 'text-[var(--success)]' : 'text-active-link-2'
                            "
                        >
                            {{ $t(website.status) }}
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
                        <span class="text-body-muted text-xs">{{ $t('myWebsites.active_status') }}</span>

                        <span class="text-sm font-medium" :class="website.is_active ? 'text-[var(--success)]' : 'text-active-link-2'">
                            {{ website.is_active ? $t('active') : $t('inactive') }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="bg-popover border-muted flex size-5.5 items-center justify-center rounded-md border">
                        <CalendarIcon class="text-body-muted size-3.5" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-body-muted text-xs">{{ $t('myWebsites.created_at') }}</span>
                        <span class="text-sm font-medium [direction:ltr]">{{ formatters.date(website.created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="website.status === 'approved' && website.subscription?.end_date" class="border-muted border-t pt-3">
            <div class="flex flex-col gap-3">
                <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <CalendarClock class="text-body-muted size-3.5" />
                        <div class="flex flex-col">
                            <span class="text-body-muted text-xs">{{ website.subscription.status === 'free_trial' ? $t('myWebsites.start_at') : $t('myWebsites.paid_at') }}</span>
                            <span class="text-sm [direction:ltr]">{{ formatters.date(website.subscription.start_date) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <CalendarMinus class="text-body-muted size-3.5" />
                        <div class="flex flex-col">
                            <span class="text-body-muted text-xs">{{ $t('myWebsites.end_at') }}</span>
                            <span class="text-sm [direction:ltr]">{{ formatters.date(website.subscription.end_date) }}</span>
                        </div>
                    </div>
                </div>

                <div
                    class="border-muted flex w-full items-center justify-between gap-2 border-t pt-3"
                >
                    <div class="flex items-center gap-1" 
                    :class="new Date(website.subscription.end_date) < new Date() ? 'w-full justify-between' : ''">
                        <p class="bg-content border px-2 py-1 text-xs font-medium" 
                            :class="subscriptionStatus(website.subscription.status).class">
                            {{ subscriptionStatus(website.subscription.status).text }} 
                        </p>

                        <Link
                            :href="route('client.makePayment', {search: website.name})"
                            v-if="['free_trial', 'expired', 'cancelled'].includes(website.subscription.status) || 
                            (new Date(website.subscription.end_date).getTime() - Date.now()) / (1000 * 60 * 60 * 24) <= 3"
                            class="text-white bg-primary border border-[var(--primary)] px-2 py-1 text-xs"
                        >
                            {{ $t('myWebsites.pay_now') }}
                        </Link>
                    </div>

                    <div class="flex items-end gap-1" v-if="new Date(website.subscription.end_date) > new Date()">
                        <span class="text-body-muted text-xs">{{ $t('myWebsites.time_left') }}</span>
                        <span class="text-sm">{{ calculateTime(website.subscription.end_date) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
