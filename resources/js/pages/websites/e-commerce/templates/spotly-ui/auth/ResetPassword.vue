<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { Head, useForm } from '@inertiajs/vue3';

import { LoaderCircle } from 'lucide-vue-next';
import Layout from './Layout.vue';

interface Props {
    token: string;
    email: string;
    websiteNameAndLogo: Record<string, string>;
    colors: Record<string, string>;
}

const props = defineProps<Props>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('website.password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Layout
        :colors="colors"
        :websiteNameAndLogo="websiteNameAndLogo"
        :title="$t('auth.resetPassword.title')"
        :description="$t('auth.resetPassword.subtitle')"
    >
        <Head :title="$t('auth.resetPassword')" />

        <form @submit.prevent="submit">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label class="web-text-body-muted" for="email">{{ $t('email') }}</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="email"
                        v-model="form.email"
                        readonly
                        class="web-bg-field web-text-active web-border-color"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label class="web-text-body-muted" for="password">{{ $t('password') }}</Label>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        v-model="form.password"
                        autofocus
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
                        name="password_confirmation"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        :placeholder="$t('password.confirm')"
                        class="web-bg-field web-text-active web-border-color"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="web-bg-primary web-text-for-primary mt-4 w-full" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    {{ $t('auth.resetPassword') }}
                </Button>
            </div>
        </form>
    </Layout>
</template>
