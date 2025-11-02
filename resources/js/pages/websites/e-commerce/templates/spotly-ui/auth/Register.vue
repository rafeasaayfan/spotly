<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';

import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import SocialAuth from './SocialAuth.vue';

import { Head, useForm } from '@inertiajs/vue3';

import { toast } from '@/lib/sweetAlert';
import { LoaderCircle } from 'lucide-vue-next';
import { watchEffect } from 'vue';
import Layout from './Layout.vue';

const props = defineProps<{
    websiteNameAndLogo: Record<string, string>;
    colors: Record<string, string>;

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

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('website.register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Layout
        :colors="colors"
        :websiteNameAndLogo="props.websiteNameAndLogo"
        :title="$t('guest.register.title')"
        :description="$t('guest.register.subtitle')"
    >
        <Head :title="$t('guest.register')" />

        <SocialAuth />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label class="web-text-body-muted" for="name">{{ $t('name') }}</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        v-model="form.name"
                        :placeholder="$t('guest.fullName')"
                        class="web-bg-field web-text-active web-border-color"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label class="web-text-body-muted" for="email">{{ $t('email') }}</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="email@example.com"
                        class="web-bg-field web-text-active web-border-color"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label class="web-text-body-muted" for="password">{{ $t('password') }}</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        :placeholder="$t('password')"
                        class="web-bg-field web-text-active web-border-color"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label class="web-text-body-muted" for="password_confirmation">{{ $t('password.confirm') }}</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        :placeholder="$t('password.confirm')"
                        class="web-bg-field web-text-active web-border-color"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="web-bg-primary web-text-for-primary mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    {{ $t('guest.register.submit') }}
                </Button>
            </div>

            <div class="flex items-center justify-center gap-2 text-center text-sm">
                <span class="web-text-body-muted">{{ $t('guest.hasAccount') }}</span>
                <TextLink class="web-text-body-muted" :href="route('website.login')" :tabindex="6">{{ $t('guest.login') }}</TextLink>
            </div>
        </form>
    </Layout>
</template>
