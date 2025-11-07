<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';

import { type BreadcrumbItem } from '@/types';

import { LoaderCircle } from 'lucide-vue-next';

import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { toast } from '@/lib/sweetAlert';
import { watch } from 'vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Settings',
        href: '/settings/profile',
    },
    {
        title: 'Password',
        href: '/settings/password',
    },
];

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (errors: any) => {
            if (errors.password) {
                form.reset('password', 'password_confirmation');
                if (passwordInput.value instanceof HTMLInputElement) {
                    passwordInput.value.focus();
                }
            }

            if (errors.current_password) {
                form.reset('current_password');
                if (currentPasswordInput.value instanceof HTMLInputElement) {
                    currentPasswordInput.value.focus();
                }
            }
        },
    });
};

watch(
    () => form.recentlySuccessful,
    (val) => {
        if (val) {
            toast.fire({
                icon: 'success',
                title: 'Your password updated successfully!',
            });
        }
    },
);
</script>

<template>
    <DashboardLayout :breadcrumbs="breadcrumbItems">
        <Head title="Password settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Update password" description="Ensure your account is using a long, random password to stay secure" />

                <form @submit.prevent="updatePassword" class="grid grid-cols-1 gap-5">
                    <div class="grid gap-2">
                        <div class="flex flex-col gap-1">
                            <Label for="current_password">Current password</Label>
                            <Input
                                id="current_password"
                                ref="currentPasswordInput"
                                v-model="form.current_password"
                                type="password"
                                autocomplete="current-password"
                                placeholder="Current password"
                            />
                        </div>
                        <InputError :message="form.errors.current_password" />
                    </div>

                    <div class="grid gap-2">
                        <div class="flex flex-col gap-1">
                            <Label for="password">New password</Label>
                            <Input
                                id="password"
                                ref="passwordInput"
                                v-model="form.password"
                                type="password"
                                autocomplete="new-password"
                                placeholder="New password"
                            />
                        </div>
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <div class="flex flex-col gap-1">
                            <Label for="password_confirmation">Confirm password</Label>
                            <Input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                placeholder="Confirm password"
                            />
                        </div>
                        <InputError :message="form.errors.password_confirmation" />
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <Button :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            <span>Update password</span>
                        </Button>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </DashboardLayout>
</template>
