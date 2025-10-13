<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input, InputError, PhoneNumberField } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { toast } from '@/lib/sweetAlert';
import { SharedData, User } from '@/types';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { computed, watch } from 'vue';
import Layout from '../Layout.vue';
import SettingLayout from './Layout.vue';
import DeleteUser from '../../components/user/DeleteUser.vue';

const props = defineProps<{
    mustVerifyEmail: boolean;
    status?: string;
    phone_number: string;
    countries: Record<string, any>;
    colors: Record<string, string>;
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
    cartItemsCount: number;
}>();

const page = usePage<SharedData>();
const user = page.props.auth.website_user as User;

const form = useForm({
    name: user.name,
    email: user.email,
    phone_number: props.phone_number,
});

const submit = () => {
    form.patch(route('website.profile.update'), {
        preserveScroll: true,
    });
};

watch(
    () => form.recentlySuccessful,
    (val) => {
        if (val) {
            toast.fire({
                icon: 'success',
                title: page.props.lang === 'ar' ? '!تم تحديث معلومات ملفك الشخصي بنجاح' : 'Your profile infos updated successfully!',
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
                title: page.props.lang === 'ar' ? 'تم إرسال رابط التحقق الجديد إلى بريدك الإلكتروني' : 'A new verification link has been sent to your email address',
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
    <Layout
        :colors="props.colors"
        :websiteNameAndLogo="props.websiteNameAndLogo"
        :websiteFooterData="props.websiteFooterData"
        :cartItemsCount="props.cartItemsCount"
    >
        <section class="pt-28 pb-22">
            <SettingLayout>
                <div class="web-border-color flex flex-col gap-6 border-b pb-4">
                    <HeadingSmall
                        class="web-border-color"
                        titleClass="web-text-active"
                        :title="$t('profile.information')"
                        descriptionClass="web-text-body-muted"
                        :description="$t('update.email.name')"
                    />

                    <form @submit.prevent="submit" class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="grid gap-2">
                            <div class="flex flex-col gap-1.5">
                                <Label class="web-text-body-muted" for="name">{{ $t('name') }}</Label>
                                <Input
                                    class="web-bg-field web-text-active web-border-color"
                                    id="name"
                                    v-model="form.name"
                                    required
                                    autocomplete="name"
                                    :placeholder="$t('full.name')"
                                />
                            </div>
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <div class="flex flex-col gap-1.5">
                                <Label class="web-text-body-muted" for="email">{{ $t('email') }}</Label>
                                <Input
                                    class="web-bg-field web-text-active web-border-color"
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                    :placeholder="$t('email')"
                                />
                            </div>
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="grid gap-2">
                            <div class="flex flex-col gap-1.5">
                                <Label class="web-text-body-muted" for="phone_number">{{ $t('phone') }}</Label>
                                <PhoneNumberField
                                    inputClass="web-bg-field web-text-active web-border-color"
                                    selectClass="web-bg-field web-text-active web-border-color"
                                    selectDropdownClass="web-bg-dropdown web-border-color"
                                    id="phone_number"
                                    v-model="form.phone_number"
                                    :options="mappedCountries"
                                    required
                                    autocomplete="username"
                                    :placeholder="$t('phone')"
                                />
                            </div>
                            <InputError :message="form.errors.phone_number" />
                        </div>

                        <div class="md:col-span-2" v-if="mustVerifyEmail && !user.email_verified_at">
                            <p class="web-text-body-muted -mt-4 text-sm">
                                {{ $t('email.unverified') }}
                                <Link
                                    :href="route('verification.send')"
                                    method="post"
                                    as="button"
                                    class="web-text-body cursor-pointer underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                                >
                                    {{ $t('click.to.resend') }}
                                </Link>
                            </p>

                            <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                                {{ $t('new.verification.sent') }}
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 md:col-span-2">
                            <Button type="submit" :disabled="form.processing" class="web-bg-primary web-text-for-primary eco-glow-button">
                                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                <span>{{ $t('update') }}</span>
                            </Button>
                        </div>
                    </form>
                </div>

                <DeleteUser />
            </SettingLayout>
        </section>
    </Layout>
</template>
