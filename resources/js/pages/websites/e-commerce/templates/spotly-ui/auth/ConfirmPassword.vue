<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';

import { Head, useForm } from '@inertiajs/vue3';

import { LoaderCircle } from 'lucide-vue-next';
import Layout from './Layout.vue';

defineProps<{
    websiteNameAndLogo: Record<string, string>;
    colors: Record<string, string>;
}>();

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('website.password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Layout :colors="colors" :websiteNameAndLogo="websiteNameAndLogo" :title="$t('auth.confirmPassword.title')" :description="$t('auth.confirmPassword.subtitle')">
        <Head :title="$t('auth.confirmPassword')" />

        <form @submit.prevent="submit">
            <div class="space-y-6">
                <div class="grid gap-2">
                    <Label class="web-text-body-muted" htmlFor="password">{{ $t('auth.confirmPassword.title') }}</Label>
                    <Input
                        id="password"
                        type="password"
                        class="web-bg-field web-text-active web-border-color"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        autofocus
                    />

                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center">
                    <Button class="w-full web-bg-primary web-text-for-primary" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        {{ $t('auth.confirmPassword') }}
                    </Button>
                </div>
            </div>
        </form>
    </Layout>
</template>
