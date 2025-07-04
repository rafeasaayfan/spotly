<script setup lang="ts">
import { onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

import { gsap } from 'gsap';

import { Button } from '@/components/ui/button';
import { Input, InputError, Textarea, Select } from '@/components/ui/fields';
import { Send } from 'lucide-vue-next';
import { Label } from '@/components/ui/label';

onMounted(() => {
    // --- Contact Form Input Animation ---
    gsap.utils.toArray<HTMLElement>('#contact-form .form-input').forEach((input, i) => {
        gsap.from(input, {
            opacity: 0,
            x: -40,
            duration: 0.3,
            delay: i * 0.1,
            scrollTrigger: {
                trigger: input,
                start: 'top 90%',
                toggleActions: 'play none none none',
                once: true,
            },
        });
    });
    gsap.from("#button-div", {
        opacity: 0,
        scale: 0.8,
        duration: 0.4,
        delay: 0.2,
        scrollTrigger: {
            trigger: "#contact-form button[type='submit']",
            start: 'top 95%',
            toggleActions: 'play none none none',
            once: true,
        },
    });
});

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
        }
    });
};
</script>

<template>
    <section id="contact-us" class="px-0 py-22 md:px-4">
        <div
            class="mx-auto flex min-h-[650px] flex-col items-center justify-center rounded-md bg-black/3 px-4 text-center backdrop-blur-[2px] dark:bg-white/2"
        >
            <div class="mb-16 flex flex-col gap-3">
                <h2 class="section-title section-title-underline text-active text-3xl font-bold sm:text-4xl md:text-4xl lg:text-5xl">
                    Get In <span class="gradient-text">Touch</span>
                </h2>
                <p class="text-body-muted">Have questions or ready to start your Spotly journey? We'd love to hear from you!</p>
            </div>

            <form @submit.prevent="submit()" id="contact-form"
                class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-full min-w-full md:min-w-2xl lg:min-w-4xl">
                <div class="flex flex-col gap-1 items-start">
                    <Label for="name">Name</Label>
                    <Input type="text" v-model="form.name" placeholder="Your Name" class="h-11" :class="form.errors.name ? 'border-[var(--destructive)]' : ''" />
                    <InputError v-if="form.errors.name" :message="form.errors.name" />
                </div>
                <div class="flex flex-col gap-1 items-start">
                    <Label for="email">Email</Label>
                    <Input type="email" v-model="form.email" placeholder="Your Email" class="h-11" :class="form.errors.email ? 'border-[var(--destructive)]' : ''" />
                    <InputError v-if="form.errors.email" :message="form.errors.email" />
                </div>
                <div class="flex flex-col gap-1 items-start">
                    <Label for="subject">Subject</Label>
                    <Input type="text" v-model="form.subject" placeholder="Subject" class="h-11" :class="form.errors.subject ? 'border-[var(--destructive)]' : ''" />
                    <InputError v-if="form.errors.subject" :message="form.errors.subject" />
                </div>
                <div class="flex flex-col gap-1 items-start">
                    <Label for="type">Type</Label>
                    <Select v-model="form.type" class="h-11" :class="form.errors.type ? 'border-[var(--destructive)]' : ''"
                        :placeholder="'Select Type'">
                        <option value="support">Support</option>
                        <option value="suggestion">Suggestion</option>
                        <option value="complaint">Complaint</option>
                        <option value="other">Other</option>
                    </Select>
                    <InputError v-if="form.errors.type" :message="form.errors.type" />
                </div>
                <div class="text-start col-span-1 md:col-span-2">
                    <Label for="message" class="pb-1">Message</Label>
                    <Textarea v-model="form.message" placeholder="Your Message" :maxlength="255" :class="form.errors.message ? 'border-[var(--destructive)]' : ''" />
                    <InputError v-if="form.errors.message" :message="form.errors.message" />
                </div>
                <div class="w-full flex items-end justify-end col-span-1 md:col-span-2" id="button-div">
                    <Button type="submit" size="lg" class="glow-button" :disabled="form.processing">
                        <span v-if="form.processing">Sending...</span>
                        <template v-else>
                            <Send class="w-4 h-4" />
                            <span>Send Message</span>
                        </template>
                    </Button>
                </div>
            </form>
        </div>
    </section>
</template>
