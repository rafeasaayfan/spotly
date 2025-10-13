<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { toast } from '@/lib/sweetAlert';
import { SharedData } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import Layout from '../Layout.vue';
import SettingLayout from './Layout.vue';

const props = defineProps<{
    colors: Record<string, string>;
    websiteNameAndLogo: Record<string, string>;
    websiteFooterData: Record<string, string>;
    cartItemsCount: number;
}>();

const page = usePage<SharedData>();

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('website.password.update'), {
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
                title: page.props.lang === 'ar' ? 'تم تحديث كلمة المرور بنجاح!' : 'Your password updated successfully!',
            });
        }
    },
);
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
                <div class="space-y-6">
                    <HeadingSmall
                        class="web-border-color"
                        titleClass="web-text-active"
                        :title="$t('update.password')"
                        descriptionClass="web-text-body-muted"
                        :description="$t('update.password.subtitle')"
                    />

                    <form @submit.prevent="updatePassword" class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="grid gap-2 md:col-span-2">
                            <div class="flex flex-col gap-1.5">
                                <Label class="web-text-body-muted" for="current_password">{{ $t('current.password') }}</Label>
                                <Input
                                    class="web-bg-field web-text-active web-border-color"
                                    id="current_password"
                                    ref="currentPasswordInput"
                                    v-model="form.current_password"
                                    type="password"
                                    autocomplete="current-password"
                                    :placeholder="$t('current.password')"
                                    required
                                />
                            </div>
                            <InputError :message="form.errors.current_password" />
                        </div>

                        <div class="grid gap-2">
                            <div class="flex flex-col gap-1.5">
                                <Label class="web-text-body-muted" for="password">{{ $t('new.password') }}</Label>
                                <Input
                                    class="web-bg-field web-text-active web-border-color"
                                    id="password"
                                    ref="passwordInput"
                                    v-model="form.password"
                                    type="password"
                                    autocomplete="new-password"
                                    :placeholder="$t('new.password')"
                                    required
                                />
                            </div>
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="grid gap-2">
                            <div class="flex flex-col gap-1.5">
                                <Label class="web-text-body-muted" for="password_confirmation">{{ $t('auth.confirmPassword') }}</Label>
                                <Input
                                    class="web-bg-field web-text-active web-border-color"
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    :placeholder="$t('auth.confirmPassword')"
                                    required
                                />
                            </div>
                            <InputError :message="form.errors.password_confirmation" />
                        </div>

                        <div class="flex items-center justify-end gap-4 md:col-span-2">
                            <Button type="submit" class="web-bg-primary web-text-for-primary eco-glow-button" :disabled="form.processing">
                                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                <span>{{ $t('update.password') }}</span>
                            </Button>
                        </div>
                    </form>
                </div>
            </SettingLayout>
        </section>
    </Layout>
</template>
