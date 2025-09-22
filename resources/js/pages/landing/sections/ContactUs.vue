<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import { Input, InputError, Select, Textarea } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { LoaderCircle, Send } from 'lucide-vue-next';
import { SharedData } from '@/types';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    type: 'support',
    message: '',
});

const submit = () => {
    form.put(route('contactMessages'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const page = usePage<SharedData>();
</script>

<template>
    <form @submit.prevent="submit()" id="contact-form" class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3 px-2 py-4">
        <div class="flex flex-col items-start gap-1.5">
            <Label for="name" class="text-xs">{{ $t('name') }}</Label>
            <Input
                type="text"
                v-model="form.name"
                :placeholder="$t('landing.contact_us.your_name_placeholder')"
                class="min-w-1 text-xs"
                :class="form.errors.name ? 'border-[var(--destructive)]' : ''"
            />
            <InputError v-if="form.errors.name" :message="form.errors.name" class="text-xs" />
        </div>

        <div class="flex flex-col items-start gap-1.5">
            <Label for="email" class="text-xs">{{ $t('email') }}</Label>
            <Input
                type="email"
                v-model="form.email"
                :placeholder="$t('landing.contact_us.your_email_placeholder')"
                class="min-w-1 text-xs"
                :class="form.errors.email ? 'border-[var(--destructive)]' : ''"
            />
            <InputError v-if="form.errors.email" :message="form.errors.email" class="text-xs" />
        </div>

        <div class="flex flex-col items-start gap-1.5">
            <Label for="subject" class="text-xs">{{ $t('landing.contact_us.subject_label') }}</Label>
            <Input
                type="text"
                v-model="form.subject"
                :placeholder="$t('landing.contact_us.subject_placeholder')"
                class="min-w-1 text-xs"
                :class="form.errors.subject ? 'border-[var(--destructive)]' : ''"
            />
            <InputError v-if="form.errors.subject" :message="form.errors.subject" class="text-xs" />
        </div>

        <div class="flex flex-col items-start gap-1.5">
            <Label for="type" class="text-xs">{{ $t('landing.contact_us.type_label') }}</Label>
            <Select v-model="form.type" :class="form.errors.type ? 'border-[var(--destructive)]' : ''" :placeholder="$t('landing.contact_us.select_type_placeholder')" 
            class="text-xs" parentClass="w-full">
                <option value="support">{{ $t('landing.contact_us.type_support') }}</option>
                <option value="suggestion">{{ $t('landing.contact_us.type_suggestion') }}</option>
                <option value="complaint">{{ $t('landing.contact_us.type_complaint') }}</option>
                <option value="other">{{ $t('landing.contact_us.type_other') }}</option>
            </Select>
            <InputError v-if="form.errors.type" :message="form.errors.type" class="text-xs" />
        </div>

        <div class="sm:col-span-2 text-start">
            <Label for="message" class="pb-1.5 text-xs">{{ $t('landing.contact_us.message_label') }}</Label>
            <Textarea
                v-model="form.message"
                class="text-xs"
                :placeholder="$t('landing.contact_us.your_message_placeholder')"
                :maxlength="255"
                :class="form.errors.message ? 'border-[var(--destructive)]' : ''"
            />
            <InputError v-if="form.errors.message" :message="form.errors.message" class="text-xs" />
        </div>

        <div class="sm:col-span-2 mt-4 flex w-full items-end justify-end" id="button-div">
            <Button type="submit" size="sm" class="glow-button gap-1.5 font-normal sm:text-xs" :disabled="form.processing">
                <template v-if="form.processing">
                    <LoaderCircle class="size-3.5 animate-spin" />
                    <span>{{ $t('landing.contact_us.sending_button') }}</span>
                </template>
                <template v-else>
                    <Send class="size-3" :class="page.props.lang === 'ar' ? '-rotate-90' : ''" />
                    <span>{{ $t('landing.contact_us.send_message_button') }}</span>
                </template>
            </Button>
        </div>
    </form>
</template>
