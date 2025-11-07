<script setup lang="ts">
import { Label } from '@/components/ui/label';

import { Carousel } from '@/components/ui/carousel';
import { Input } from '@/components/ui/fields';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { formatters } from '@/lib/dataTable';
import { toast } from '@/lib/sweetAlert';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import {
    Activity,
    Building2,
    ChartPie,
    CheckCheckIcon,
    Clock,
    CopyCheck,
    CopyIcon,
    Facebook,
    Flag,
    Globe,
    Info,
    Instagram,
    Languages,
    LayoutTemplate,
    Locate,
    Mail,
    Phone,
    PlusCircle,
    TypeOutline,
    XCircle,
    Youtube,
} from 'lucide-vue-next';
import { ref, watchEffect } from 'vue';
import { Image } from '@/components/ui/image';
import { typeColor } from '@/lib/websiteTypes';

const page = usePage<SharedData>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: page.props.lang === 'ar' ? 'مواقعي' : 'My Websites',
        href: '/dashboard/my-websites',
    },
    {
        title: page.props.lang === 'ar' ? 'عرض' : 'View',
        href: '/dashboard/my-website/View',
    },
];

const props = defineProps<{
    website: Record<string, any>;
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info';
        message: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});

const copied = ref(false);

const copyUrl = async () => {
    try {
        const url = 'https://' + props.website.subdomain + 'spotly.com';

        await navigator.clipboard.writeText(url);
        copied.value = true;

        setTimeout(() => (copied.value = false), 2000);
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
};
</script>

<template>
    <Head title="Website-Edit" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="border-muted relative mx-2 md:mx-4 my-4 grid grid-cols-1 rounded-md border sm:grid-cols-9 md:grid-cols-5 lg:grid-cols-6">
            <div class="flex flex-col rounded-s-md sm:col-span-4 md:col-span-2 lg:col-span-2">
                <div class="border-muted flex flex-col gap-2 border-b p-4 md:p-6">
                    <h1 class="text-active rounded-md bg-gradient-to-br from-blue-500/40 via-blue-500/30 to-blue-500/60 px-4 py-2 font-extrabold">
                        {{ props.website.name }}
                    </h1>
                </div>

                <div class="border-muted flex flex-col gap-2 border-b p-4 md:p-6">
                    <div class="flex flex-col gap-1">
                        <Label for="website_url" class="text-body-muted mb-1 text-xs">{{ $t('myWebsites.website_url') }}</Label>

                        <div class="flex w-full items-center justify-between">
                            <div class="flex h-full flex-1 items-center">
                                <div
                                    class="flex h-full w-10 items-center justify-center rounded-s-md bg-card"
                                >
                                    <Globe class="size-4 text-gray-500" />
                                </div>

                                <a
                                    :href="'https://' + props.website.subdomain + '.spotly.com'"
                                    class="w-full cursor-pointer"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <Input
                                        :defaultValue="'https://' + props.website.subdomain + '.spotly.com'"
                                        class="cursor-pointer rounded-none text-[var(--primary)]"
                                        readonly
                                    />
                                </a>
                            </div>

                            <button
                                type="button"
                                @click="copyUrl"
                                class="flex h-full w-10 cursor-pointer items-center justify-center rounded-e-md bg-primary text-for-bg-primary
                                transition-all duration-200 ease-in-out"
                                :class="copied ? 'pointer-events-none opacity-50' : ''"
                            >
                                <CopyCheck v-if="copied" class="size-4" />
                                <CopyIcon v-else class="size-4" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Logo -->
                <div class="border-muted flex flex-col items-center gap-5 border-b p-4 md:p-6">
                    <template v-if="props.website.light_logo && props.website.dark_logo">
                        <div class="flex flex-col gap-1">
                            <Label for="light_logo" class="text-body-muted mb-1 text-xs">{{ $t('myWebsites.light_logo') }}</Label>
                            <Image id="light_logo" class="h-50 rounded-full" :src="props.website.light_logo" label="Light Logo" />
                        </div>

                        <div class="flex flex-col gap-1">
                            <Label for="dark_logo" class="text-body-muted mb-1 text-xs">{{ $t('myWebsites.dark_logo') }}</Label>
                            <Image id="dark_logo" class="h-50 rounded-full" :src="props.website.dark_logo" label="Dark Logo" />
                        </div>
                    </template>
                    <div v-else class="relative flex h-full w-full items-center justify-center rounded-md">
                        <div class="bg-field absolute inset-0 z-0 h-full w-full rounded-md blur-sm"></div>

                        <p class="z-1 py-2 text-sm md:text-base">This website have a default Logo</p>
                    </div>
                </div>

                <div class="border-muted flex flex-col gap-2 border-b p-4 md:p-6">
                    <Label class="text-body-muted text-xs">
                        <div class="bg-popover border-muted flex size-5.5 items-center justify-center rounded-md border">
                            <Activity class="size-3.5" />
                        </div>
                        {{ $t('myWebsites.active_status') }}
                    </Label>

                    <span
                        class="w-fit rounded-md px-4 py-1.5 text-sm font-medium"
                        :class="
                            props.website.is_active
                                ? 'bg-[var(--success)]/20 text-[var(--success)]'
                                : 'bg-[var(--destructive)]/20 text-[var(--destructive)]'
                        "
                    >
                        {{ props.website.is_active ? $t('active') : $t('inactive') }}
                    </span>
                </div>

                <div class="border-muted flex flex-col gap-2 border-b p-4 md:p-6">
                    <Label class="text-body-muted text-xs">
                        <div class="bg-popover border-muted flex size-5.5 items-center justify-center rounded-md border">
                            <ChartPie class="size-3.5" />
                        </div>
                        {{ $t('myWebsites.status') }}
                    </Label>

                    <span
                        class="flex w-fit items-center gap-1.5 rounded-md px-4 py-1.5 text-sm font-medium"
                        :class="
                            props.website.status === 'pending'
                                ? 'text-active bg-black/10 dark:bg-white/10'
                                : props.website.status === 'approved'
                                  ? 'bg-[var(--success)]/20 text-[var(--success)]'
                                  : 'bg-[var(--destructive)]/20 text-[var(--destructive)]'
                        "
                    >
                        <Clock v-if="props.website.status === 'pending'" class="size-4" />
                        <CheckCheckIcon v-else-if="props.website.status === 'approved'" class="size-4" />
                        <XCircle v-else-if="props.website.status === 'denied'" class="size-4" />
                        {{ $t(props.website.status) }}
                    </span>
                </div>

                <div class="border-muted flex flex-col gap-2 border-b p-4 md:p-6">
                    <Label class="text-body-muted text-xs">
                        <div class="bg-popover border-muted flex size-5.5 items-center justify-center rounded-md border">
                            <TypeOutline class="size-3.5" />
                        </div>
                        {{ $t('myWebsites.website_type') }}
                    </Label>

                    <span
                        class="flex w-fit items-center gap-1.5 rounded-md px-4 py-1.5 text-sm font-medium"
                        :class="typeColor.bg(props.website.website_type.type)"
                    >
                        {{ props.website.website_type.type }}
                    </span>
                </div>

                <div class="border-muted flex flex-wrap justify-between gap-2 border-b p-4 md:p-6">
                    <div class="flex flex-col gap-1">
                        <span class="text-body-muted text-xs">{{ $t('myWebsites.website_viewers') }}</span>
                        <span class="text-active text-sm font-medium">{{ props.website.views_count }}</span>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-body-muted text-xs">{{ $t('myWebsites.currency') }}</span>
                        <span class="text-active text-sm font-medium">{{ props.website.currency }}</span>
                    </div>
                </div>

                <div class="flex flex-wrap justify-between gap-2 p-4 md:p-6">
                    <div class="flex flex-col gap-1">
                        <span class="text-body-muted text-xs">{{ $t('myWebsites.created_at') }}</span>
                        <span class="text-active text-sm font-medium [direction:ltr]">{{ formatters.date(props.website.created_at) }}</span>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-body-muted text-xs">{{ $t('myWebsites.updated_at') }}</span>
                        <span class="text-active text-sm font-medium [direction:ltr]">{{ formatters.date(props.website.updated_at) }}</span>
                    </div>
                </div>
            </div>

            <div class="border-muted flex flex-col gap-6 border-s px-4 py-6 sm:col-span-5 md:col-span-3 md:px-10 lg:col-span-4">
                <div class="grid grid-cols-1 gap-5 rounded-md lg:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <Label for="phone_number" class="text-body-muted mb-1 text-xs">
                            <Phone class="size-3.5" />
                            {{ $t('phone') }}
                        </Label>

                        <a :href="'tel:' + props.website.phone_number" class="w-full cursor-pointer [direction:ltr]" rel="noopener noreferrer">
                            <Input :defaultValue="props.website.phone_number" class="cursor-pointer text-[var(--primary)]" readonly />
                        </a>
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label for="email" class="text-body-muted mb-1 text-xs">
                            <Mail class="size-3.5" />
                            {{ $t('email') }}
                        </Label>

                        <a v-if="props.website.email" :href="'mailto:' + props.website.email" class="w-full cursor-pointer" rel="noopener noreferrer">
                            <Input :defaultValue="props.website.email" class="cursor-pointer text-[var(--primary)]" readonly />
                        </a>
                        <Input v-else :defaultValue="page.props.lang === 'ar' ? 'لا يوجد بريد إلكتروني' : 'No email provided'" readonly />
                    </div>
                </div>

                <div class="border-muted grid grid-cols-1 gap-5 border-t pt-6 lg:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <Label for="country" class="text-body-muted mb-1 text-xs">
                            <Flag class="size-3.5" />
                            {{ $t('country') }}
                        </Label>

                        <Input :defaultValue="props.website.country" readonly />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label for="city" class="text-body-muted mb-1 text-xs">
                            <Building2 class="size-3.5" />
                            {{ $t('city') }}
                        </Label>

                        <Input v-if="props.website.city" :defaultValue="props.website.city" readonly />
                        <Input v-else :defaultValue="page.props.lang === 'ar' ? 'لا توجد مدينة' : 'No city provided'" readonly />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label for="address" class="text-body-muted mb-1 text-xs">
                            <Locate class="size-3.5" />
                            {{ $t('address') }}
                        </Label>

                        <Input v-if="props.website.address" :defaultValue="props.website.address" readonly />
                        <Input v-else :defaultValue="page.props.lang === 'ar' ? 'لا يوجد عنوان' : 'No address provided'" readonly />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label for="language" class="text-body-muted mb-1 text-xs">
                            <Languages class="size-3.5" />
                            {{ $t('default_language') }}
                        </Label>

                        <Input :defaultValue="props.website.language" readonly />
                    </div>
                </div>

                <div class="border-muted grid grid-cols-1 gap-5 border-t pt-6 lg:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <Label for="instagram" class="text-body-muted mb-1 text-xs">
                            <Instagram class="size-3.5" />
                            {{ $t('instagram') }}
                        </Label>

                        <a
                            v-if="props.website.instagram"
                            :href="props.website.instagram"
                            class="w-full cursor-pointer"
                            rel="noopener noreferrer"
                            target="_blank"
                        >
                            <Input :defaultValue="props.website.instagram" class="cursor-pointer text-[var(--primary)]" readonly />
                        </a>
                        <Input
                            v-else
                            :defaultValue="page.props.lang === 'ar' ? 'لا يوجد حساب' : 'No account provided'"
                            readonly
                        />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label
                            for="tiktok"
                            class="text-body-muted mb-1 stroke-black/50 text-xs hover:stroke-black dark:stroke-white/50 hover:dark:stroke-white"
                        >
                            <svg viewBox="0 0 256 256" class="size-3.5">
                                <path
                                    d="M168,106a95.9,95.9,0,0,0,56,18V84a56,56,0,0,1-56-56H128V156a28,28,0,1,1-40-25.3V89.1A68,68,0,1,0,168,156Z"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="22"
                                />
                            </svg>
                            {{ $t('tiktok') }}
                        </Label>

                        <a
                            v-if="props.website.tiktok"
                            :href="props.website.tiktok"
                            class="w-full cursor-pointer"
                            rel="noopener noreferrer"
                            target="_blank"
                        >
                            <Input :defaultValue="props.website.tiktok" class="cursor-pointer text-[var(--primary)]" readonly />
                        </a>
                        <Input
                            v-else
                            :defaultValue="page.props.lang === 'ar' ? 'لا يوجد حساب' : 'No account provided'"
                            readonly
                        />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label for="youtube" class="text-body-muted mb-1 text-xs">
                            <Youtube class="size-3.5" />
                            {{ $t('youtube') }}
                        </Label>

                        <a
                            v-if="props.website.youtube"
                            :href="props.website.youtube"
                            class="w-full cursor-pointer"
                            rel="noopener noreferrer"
                            target="_blank"
                        >
                            <Input :defaultValue="props.website.youtube" class="cursor-pointer text-[var(--primary)]" readonly />
                        </a>
                        <Input
                            v-else
                            :defaultValue="page.props.lang === 'ar' ? 'لا يوجد حساب' : 'No account provided'"
                            readonly
                        />
                    </div>
                    <div class="flex flex-col gap-2">
                        <Label for="facebook" class="text-body-muted text-xs">
                            <Facebook class="size-3.5" />
                            {{ $t('facebook') }}
                        </Label>

                        <a
                            v-if="props.website.facebook"
                            :href="props.website.facebook"
                            class="w-full cursor-pointer"
                            rel="noopener noreferrer"
                            target="_blank"
                        >
                            <Input :defaultValue="props.website.facebook" class="cursor-pointer text-[var(--primary)]" readonly />
                        </a>
                        <Input
                            v-else
                            :defaultValue="page.props.lang === 'ar' ? 'لا يوجد حساب' : 'No account provided'"
                            readonly
                        />
                    </div>
                </div>

                <div class="border-muted flex w-full flex-col border-t pt-6">
                    <Label for="about_us" class="text-body-muted mb-2 text-xs">
                        <Info class="size-3.5" />
                        {{ $t('about_us_section') }}
                    </Label>

                    <Input :defaultValue="props.website.about_us" readonly />
                </div>

                <div class="border-muted flex w-full flex-col border-t pt-6">
                    <Label for="about_us" class="text-body-muted mb-2 text-xs">
                        <LayoutTemplate class="size-3.5" />
                        {{ page.props.lang === 'ar' ? 'قالب الواجهة النشط' : 'Active UI Template' }}
                    </Label>

                    <div v-if="props.website.active_website_template.template_images" 
                        class="bg-field flex flex-col gap-3 px-2 md:px-4 py-4 rounded-md border border-muted"
                    >
                        <div class="flex items-center gap-2">
                            <span
                                class="rounded-md text-sm text-active px-3 py-1.5 bg-card border border-muted"
                            >
                                {{ props.website.active_website_template.template.name }}
                            </span>
                            <PlusCircle class="size-3.5" />
                            <span
                                class="rounded-md text-sm text-active px-3 py-1.5 bg-card border border-muted"
                            >
                                {{ props.website.active_website_template.template_color.name }}
                            </span>
                        </div>
                        <Carousel
                            v-if="Array.isArray(props.website.active_website_template.template_images)"
                            :items="props.website.active_website_template.template_images"
                            class="h-60 md:h-80 lg:h-90 w-full"
                            :showArrows="false"
                        />
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
