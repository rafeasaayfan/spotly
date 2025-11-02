<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input, InputError, Select, Textarea } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { SharedData } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle, Send } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
    type: 'support',
});

const page = usePage<SharedData>();

const submitForm = () => {
};
</script>

<template>
    <section id="contact" class="contact-method-card relative mt-22 pt-15 pb-22">
        <div class="mb-14 w-full text-center">
            <h2 class="web-text-active eco-section-title-underline text-3xl font-bold sm:text-4xl lg:text-5xl">
                {{ $t('landing.contact_dropdown.title_part1') }}
                <span class="eco-gradient-text">{{ $t('landing.contact_dropdown.title_part2') }}</span>
            </h2>
        </div>

        <form @submit.prevent="submitForm" class="mx-auto grid max-w-4xl grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <Label for="name" class="web-text-body-muted mb-1.5">{{ $t('landing.contact_us.your_name_placeholder') }}</Label>
                <Input
                    type="text"
                    id="name"
                    v-model="form.name"
                    required
                    placeholder="John Doe"
                    class="web-bg-field web-text-active web-border-color h-11"
                    :class="form.errors.name ? 'border-[var(--destructive)]' : ''"
                />
                <InputError v-if="form.errors.name" :message="form.errors.name" />
            </div>
            <div>
                <Label for="email" class="web-text-body-muted mb-1.5">{{ $t('landing.contact_us.your_email_placeholder') }}</Label>
                <Input
                    type="email"
                    id="email"
                    v-model="form.email"
                    required
                    placeholder="john.doe@example.com"
                    class="web-bg-field web-text-active web-border-color h-11"
                    :class="form.errors.email ? 'border-[var(--destructive)]' : ''"
                />
                <InputError v-if="form.errors.email" :message="form.errors.email" />
            </div>

            <div>
                <Label for="subject" class="web-text-body-muted mb-1.5">{{ $t('landing.contact_us.subject_label') }}</Label>
                <Input
                    type="text"
                    id="subject"
                    v-model="form.subject"
                    required
                    :placeholder="$t('landing.contact_us.subject_label')"
                    class="web-bg-field web-text-active web-border-color h-11"
                    :class="form.errors.subject ? 'border-[var(--destructive)]' : ''"
                />
                <InputError v-if="form.errors.subject" :message="form.errors.subject" />
            </div>

            <div class="flex flex-col items-start gap-1">
                <Label for="type" class="web-text-body-muted">{{ $t('landing.contact_us.type_label') }}</Label>
                <Select
                    v-model="form.type"
                    :placeholder="$t('landing.contact_us.select_type_placeholder')"
                    parentClass="w-full"
                    class="web-bg-field web-text-active web-border-color h-11 w-full"
                    :class="form.errors.type ? 'border-[var(--destructive)]' : ''"
                >
                    <option value="support">{{ $t('landing.contact_us.type_support') }}</option>
                    <option value="suggestion">{{ $t('landing.contact_us.type_suggestion') }}</option>
                    <option value="complaint">{{ $t('landing.contact_us.type_complaint') }}</option>
                    <option value="other">{{ $t('landing.contact_us.type_other') }}</option>
                </Select>
                <InputError v-if="form.errors.type" :message="form.errors.type" />
            </div>

            <div class="col-span-1 md:col-span-2">
                <Label for="message" class="web-text-body-muted mb-1.5">{{ $t('landing.contact_us.message_label') }}</Label>
                <Textarea
                    id="message"
                    v-model="form.message"
                    rows="6"
                    required
                    class="web-bg-field web-text-active web-border-color z-0"
                    :placeholder="$t('landing.contact_us.your_message_placeholder')"
                    :class="form.errors.message ? 'border-[var(--destructive)]' : ''"
                    :maxlength="255"
                >
                </Textarea>
                <InputError v-if="form.errors.message" :message="form.errors.message" />
            </div>

            <div class="col-span-1 flex justify-end md:col-span-2">
                <Button type="submit" class="web-bg-primary web-text-for-primary eco-glow-button" :disabled="form.processing">
                    <template v-if="form.processing">
                        <LoaderCircle class="size-3.5 animate-spin" />
                        <span>{{ $t('landing.contact_us.sending_button') }}</span>
                    </template>
                    <template v-else>
                        <Send class="size-3.5" :class="page.props.lang === 'ar' ? '-rotate-90' : ''" />
                        <span>{{ $t('landing.contact_us.send_message_button') }}</span>
                    </template>
                </Button>
            </div>
        </form>

        <div class="web-border-color pointer-events-none absolute top-0 left-0 z-0 h-full w-full rounded-full border-t-8 border-double"></div>
    </section>
</template>

