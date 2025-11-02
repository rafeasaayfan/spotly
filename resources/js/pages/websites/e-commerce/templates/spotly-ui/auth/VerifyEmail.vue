<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Layout from './Layout.vue';

defineProps<{
    status?: string;
    websiteNameAndLogo: Record<string, string>;
    colors: Record<string, string>;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('website.verification.send'));
};
</script>

<template>
    <Layout
        :colors="colors"
        :websiteNameAndLogo="websiteNameAndLogo"
        :title="$t('auth.verifyEmail.title')"
        :description="$t('auth.verifyEmail.subtitle')"
    >
        <Head :title="$t('auth.email.verification')" />

        <div v-if="status === 'verification-link-sent'" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ $t('auth.verifyEmail.verificationLinkSent') }}
        </div>

        <form @submit.prevent="submit" class="space-y-6 text-center">
            <Button :disabled="form.processing" variant="secondary" class="web-bg-secondary">
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                {{ $t('auth.verifyEmail.resendEmail') }}
            </Button>

            <TextLink :href="route('website.logout')" method="post" as="button" class="web-text-body-muted mx-auto block text-sm">
                {{ $t('auth.logout') }}
            </TextLink>
        </form>
    </Layout>
</template>
