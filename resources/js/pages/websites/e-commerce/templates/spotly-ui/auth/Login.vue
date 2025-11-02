<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';

import { Button } from '@/components/ui/button';
import { Checkbox, Input, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import SocialAuth from './SocialAuth.vue';

import { Head, useForm } from '@inertiajs/vue3';

import { toast } from '@/lib/sweetAlert';
import { LoaderCircle } from 'lucide-vue-next';
import { watchEffect } from 'vue';

import Layout from './Layout.vue';

const props = defineProps<{
    status?: string;
    canResetPassword: boolean;
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
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('website.login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Layout
        :colors="colors"
        :websiteNameAndLogo="props.websiteNameAndLogo"
        :title="$t('guest.login.title')"
        :description="$t('guest.login.subtitle')"
    >
        <Head :title="$t('guest.login')" />

        <SocialAuth />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label class="web-text-body-muted" for="email">{{ $t('email') }}</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="email@example.com"
                        class="web-bg-field web-text-active web-border-color"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label class="web-text-body-muted" for="password">{{ $t('password') }}</Label>
                        <TextLink
                            v-if="props.canResetPassword"
                            :href="route('website.password.request')"
                            class="web-text-body-muted text-sm"
                            :tabindex="5"
                        >
                            {{ $t('guest.forgotPassword') }}
                        </TextLink>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        v-model="form.password"
                        :placeholder="$t('password')"
                        class="web-bg-field web-text-active web-border-color"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label for="remember" class="web-text-body-muted flex cursor-pointer items-center" :class="form.remember ? 'text-body' : ''">
                        <Checkbox
                            id="remember"
                            v-model="form.remember"
                            :tabindex="3"
                            class="bg-[var(--bg_field_light)] shadow data-[state=checked]:bg-[var(--primary_light)] dark:bg-[var(--bg_field_dark)] data-[state=checked]:dark:bg-[var(--primary_dark)]"
                        />
                        <span>{{ $t('guest.remember') }}</span>
                    </Label>
                </div>

                <Button type="submit" class="web-bg-primary web-text-for-primary mt-2 w-full" :tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    {{ $t('guest.login') }}
                </Button>
            </div>

            <div class="flex items-center justify-center gap-2 text-center text-sm">
                <span class="web-text-body-muted">{{ $t('guest.login.noAccount') }}</span>
                <TextLink :href="route('website.register')" :tabindex="5" class="web-text-body-muted">{{ $t('guest.signup') }}</TextLink>
            </div>
        </form>
    </Layout>
</template>
