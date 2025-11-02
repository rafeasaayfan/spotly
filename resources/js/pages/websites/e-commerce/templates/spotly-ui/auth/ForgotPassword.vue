<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';

import { Head, useForm } from '@inertiajs/vue3';

import { LoaderCircle } from 'lucide-vue-next';
import Layout from './Layout.vue';

defineProps<{
    status?: string;
    websiteNameAndLogo: Record<string, string>;
    colors: Record<string, string>;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post('website.forgot-password');
};
</script>

<template>
    <Layout :colors="colors" :websiteNameAndLogo="websiteNameAndLogo" :title="$t('guest.forgot.passowrd')" :description="$t('guest.forgotPassowrd.subtitle')">
        <Head :title="$t('guest.forgot.passowrd')" />

        <div class="mt-3 space-y-6">
            <form @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label class="web-text-body-muted" for="email">{{ $t('email') }}</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="off"
                        v-model="form.email"
                        autofocus
                        placeholder="email@example.com"
                        required
                        class="web-bg-field web-text-active web-border-color"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="my-6 flex items-center justify-start">
                    <Button class="w-full web-bg-primary web-text-for-primary" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        {{ $t('guest.forgotPassowrd.link') }}
                    </Button>
                </div>
            </form>

            <p v-if="status" class="text-[var(--success)]">{{ status }}</p>

            <div class="flex items-center justify-center gap-2 text-sm">
                <span class="web-text-body-muted">{{ $t('guest.forgotPassowrd.return') }}</span>
                <TextLink class="web-text-body-muted" :href="route('website.login')">{{ $t('guest.login') }}</TextLink>
            </div>
        </div>
    </Layout>
</template>
