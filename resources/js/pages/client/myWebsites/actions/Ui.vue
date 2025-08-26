<script setup lang="ts">
// import { Button } from '@/components/ui/button';
// import { Label } from '@/components/ui/label';
// import { Dialog, DialogDescription, DialogHeader, DialogScrollContent, DialogTitle, DialogTrigger } from '@/components/ui/dialog';

import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { toast } from '@/lib/sweetAlert';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { watchEffect } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Websites',
        href: '/dashboard/my-websites',
    },
    {
        title: 'UI',
        href: '/dashboard/my-website/ui',
    },
];

const props = defineProps<{
    website: Record<string, any>;
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info',
        message: string,
    }
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});

const form = useForm<Record<string, any>>({
    ...props.website,
});

const submit = () => {
};
</script>

<template>
    <Head title="Website-UI" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <h1
            class="text-active mx-4 mt-4 mb-2 w-fit rounded-md bg-gradient-to-br from-blue-500/40 via-blue-500/30 to-blue-500/60 px-3 py-2 font-extrabold"
        >
            {{ props.website.name }}
        </h1>

        <form class="border-muted mx-4 mb-4 flex rounded-md border" @submit.prevent="submit">
        </form>

        <!-- <div class="flex w-full justify-end px-4 pb-4">
            <Button @click="submit" :disabled="form.processing">
                <LoaderCircle v-if="form.processing" class="size-4.5 animate-spin" />
                Save Change
            </Button>
        </div> -->
    </DashboardLayout>
</template>
