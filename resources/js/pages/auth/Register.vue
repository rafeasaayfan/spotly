<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';

import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import SocialAuth from './SocialAuth.vue';

import AuthLayout from '@/layouts/AuthLayout.vue';

import { Head, useForm } from '@inertiajs/vue3';

import { LoaderCircle } from 'lucide-vue-next';
import { watchEffect } from 'vue';
import { toast } from '@/lib/sweetAlert';

const props = defineProps<{
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
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout :title="$t('guest.register.title')" :description="$t('guest.register.subtitle')">
        <Head :title="$t('guest.register')" />

        <SocialAuth />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">{{ $t('name') }}</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" :placeholder="$t('guest.fullName')" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">{{ $t('email') }}</Label>
                    <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">{{ $t('password') }}</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        :placeholder="$t('password')"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">{{ $t('password.confirm') }}</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        :placeholder="$t('password.confirm')"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    {{ $t('guest.register.submit') }}
                </Button>
            </div>

            <div class="flex items-center justify-center gap-2 text-center text-sm">
                <span class="text-body-muted">{{ $t('guest.hasAccount') }}</span>
                <TextLink :href="route('login')" :tabindex="6">{{ $t('guest.login') }}</TextLink>
            </div>
        </form>

    </AuthLayout>
</template>
