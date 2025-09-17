<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import { Input, InputError, Select, Textarea } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { LoaderCircle, Send } from 'lucide-vue-next';

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
</script>

<template>
    <form @submit.prevent="submit()" id="contact-form" class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3 px-2 py-4">
        <div class="flex flex-col items-start gap-1.5">
            <Label for="name" class="text-xs">Name</Label>
            <Input
                type="text"
                v-model="form.name"
                placeholder="Your Name"
                class="min-w-1 text-xs"
                :class="form.errors.name ? 'border-[var(--destructive)]' : ''"
            />
            <InputError v-if="form.errors.name" :message="form.errors.name" class="text-xs" />
        </div>

        <div class="flex flex-col items-start gap-1.5">
            <Label for="email" class="text-xs">Email</Label>
            <Input
                type="email"
                v-model="form.email"
                placeholder="Your Email"
                class="min-w-1 text-xs"
                :class="form.errors.email ? 'border-[var(--destructive)]' : ''"
            />
            <InputError v-if="form.errors.email" :message="form.errors.email" class="text-xs" />
        </div>

        <div class="flex flex-col items-start gap-1.5">
            <Label for="subject" class="text-xs">Subject</Label>
            <Input
                type="text"
                v-model="form.subject"
                placeholder="Subject"
                class="min-w-1 text-xs"
                :class="form.errors.subject ? 'border-[var(--destructive)]' : ''"
            />
            <InputError v-if="form.errors.subject" :message="form.errors.subject" class="text-xs" />
        </div>

        <div class="flex flex-col items-start gap-1.5">
            <Label for="type" class="text-xs">Type</Label>
            <Select v-model="form.type" :class="form.errors.type ? 'border-[var(--destructive)]' : ''" :placeholder="'Select Type'" class="text-xs">
                <option value="support">Support</option>
                <option value="suggestion">Suggestion</option>
                <option value="complaint">Complaint</option>
                <option value="other">Other</option>
            </Select>
            <InputError v-if="form.errors.type" :message="form.errors.type" class="text-xs" />
        </div>

        <div class="sm:col-span-2 text-start">
            <Label for="message" class="pb-1.5 text-xs">Message</Label>
            <Textarea
                v-model="form.message"
                class="text-xs"
                placeholder="Your Message"
                :maxlength="255"
                :class="form.errors.message ? 'border-[var(--destructive)]' : ''"
            />
            <InputError v-if="form.errors.message" :message="form.errors.message" class="text-xs" />
        </div>

        <div class="sm:col-span-2 mt-4 flex w-full items-end justify-end" id="button-div">
            <Button type="submit" size="sm" class="glow-button gap-1.5 font-normal sm:text-xs" :disabled="form.processing">
                <template v-if="form.processing">
                    <LoaderCircle class="size-3.5 animate-spin" />
                    <span>Sending...</span>
                </template>
                <template v-else>
                    <Send class="size-3" />
                    <span>Send Message</span>
                </template>
            </Button>
        </div>
    </form>
</template>
