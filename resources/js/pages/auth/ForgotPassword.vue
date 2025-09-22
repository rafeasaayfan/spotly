<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';

import AuthLayout from '@/layouts/AuthLayout.vue';

import { Head, useForm } from '@inertiajs/vue3';

import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <AuthLayout :title="$t('guest.forgot.passowrd')" :description="$t('guest.forgotPassowrd.subtitle')">
        <Head :title="$t('guest.forgot.passowrd')" />

        <div class="space-y-6 mt-3">
            <form @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label for="email">{{ $t('email') }}</Label>
                    <Input id="email" type="email" name="email" autocomplete="off" v-model="form.email" autofocus placeholder="email@example.com" required />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="my-6 flex items-center justify-start">
                    <Button class="w-full" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        {{ $t('guest.forgotPassowrd.link') }}
                    </Button>
                </div>
            </form>

            <p v-if="status" class="text-[var(--success)]">{{ status }}</p>

            <div class="flex items-center justify-center text-sm gap-2">
                <span class="text-body-muted">{{ $t('guest.forgotPassowrd.return') }}</span>
                <TextLink :href="route('login')">{{ $t('guest.login') }}</TextLink>
            </div>
        </div>
    </AuthLayout>
</template>
