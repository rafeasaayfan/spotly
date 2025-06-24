<script setup lang="ts">
import { onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

import { gsap } from 'gsap';

import { Button } from '@/components/ui/button';
import { Input, InputError, Textarea } from '@/components/ui/fields';

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

            <form @submit.prevent="submit()" id="contact-form" class="flex flex-col gap-6 max-w-full min-w-full md:min-w-2xl lg:min-w-4xl">
                <div class="flex flex-col gap-1 items-start">
                    <Input type="text" v-model="form.name" placeholder="Your Name" class="h-11" />
                    <InputError v-if="form.errors.name" :message="form.errors.name" />
                </div>
                <div class="flex flex-col gap-1 items-start">
                    <Input type="email" v-model="form.email" placeholder="Your Email" class="h-11" />
                    <InputError v-if="form.errors.email" :message="form.errors.email" />
                </div>
                <div class="text-start">
                    <Textarea v-model="form.message" placeholder="Your Message" :maxlength="255" />
                    <InputError v-if="form.errors.message" :message="form.errors.message" />
                </div>
                <div class="w-full flex items-end justify-end" id="button-div">
                    <Button type="submit" size="lg" class="glow-button" :disabled="form.processing">
                        <span v-if="form.processing">Sending...</span>
                        <span v-else>Send Message</span>
                    </Button>
                </div>
            </form>
        </div>
    </section>
</template>
