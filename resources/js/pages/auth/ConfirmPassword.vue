<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';

import AuthLayout from '@/layouts/AuthLayout.vue';

import { Head, useForm } from '@inertiajs/vue3';

import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <AuthLayout :title="$t('auth.confirmPassword.title')" :description="$t('auth.confirmPassword.subtitle')">
        <Head :title="$t('auth.confirmPassword')" />

        <form @submit.prevent="submit">
            <div class="space-y-6">
                <div class="grid gap-2">
                    <Label htmlFor="password">{{ $t('auth.confirmPassword.title') }}</Label>
                    <Input
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        autofocus
                    />

                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center">
                    <Button class="w-full" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        {{ $t('auth.confirmPassword') }}
                    </Button>
                </div>
            </div>
        </form>
    </AuthLayout>
</template>
