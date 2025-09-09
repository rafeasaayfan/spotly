<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import { Input, InputError, PhoneNumberField } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';

import { type BreadcrumbItem, type SharedData, type User } from '@/types';

import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import DeleteUser from '@/components/user/DeleteUser.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

import { LoaderCircle } from 'lucide-vue-next';

import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { toast } from '@/lib/sweetAlert';
import { computed, watch } from 'vue';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    phone_number?: string;
    countries: Record<string, any>
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Settings',
        href: '',
    },
    {
        title: 'Profile',
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    email: user.email,
    phone_number: props.phone_number
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};

watch(
    () => form.recentlySuccessful,
    (val) => {
        if (val) {
            toast.fire({
                icon: 'success',
                title: 'Your profile infos updated successfully!',
            });
        }
    },
);

const status = computed(() => props.status);

watch(
    () => status,
    (val) => {
        if (val.value == 'verification-link-sent') {
            toast.fire({
                icon: 'success',
                title: 'A new verification link has been sent to your email address',
            });
        }
    },
);

const mappedCountries = props.countries.map((item: Record<string, any>) => ({
    value: item.phone_code,
    label: item.phone_code,
    icon: item.flag,
}));
</script>

<template>
    <DashboardLayout :breadcrumbs="breadcrumbs">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col gap-6 border-b border-muted pb-4">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="grid gap-2">
                        <div class="flex flex-col gap-1">
                            <Label for="name">Name</Label>
                            <Input id="name" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                        </div>
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <div class="flex flex-col gap-1">
                            <Label for="email">Email address</Label>
                            <Input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                placeholder="Email address"
                            />
                        </div>
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2 md:col-span-2">
                        <div class="flex flex-col gap-1">
                            <Label for="phone_number">Phone Number</Label>
                            <PhoneNumberField
                                id="phone_number"
                                v-model="form.phone_number"
                                :options="mappedCountries"
                                required
                                autocomplete="username"
                                placeholder="Email address"
                            />
                        </div>
                        <InputError :message="form.errors.phone_number" />
                    </div>

                    <div class="md:col-span-2" v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="text-body-muted -mt-4 text-sm">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-body cursor-pointer underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="md:col-span-2 flex items-center justify-end gap-4">
                        <Button :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            <span>Update</span>
                        </Button>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </DashboardLayout>
</template>
