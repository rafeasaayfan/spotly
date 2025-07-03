<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input, Textarea } from '@/components/ui/fields';
import Label from '@/components/ui/label/Label.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Mail, MapPin, Phone, Send } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';

const form = ref({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const submitForm = () => {
    // Implement your form submission logic here
    console.log('Form submitted:', form.value);
    alert('Thank you for your message! We will get back to you soon.');
    // Reset form
    form.value = { name: '', email: '', subject: '', message: '' };
};

onMounted(() => {
    gsap.registerPlugin(ScrollTrigger);

    // Animate contact methods
    gsap.from('.contact-method-card', {
        opacity: 0,
        y: 50,
        stagger: 0.15,
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.contact-methods-grid',
            start: 'top 80%',
            toggleActions: 'play none none none',
        },
    });

    // Animate form elements
    gsap.from('.contact-form > *', {
        opacity: 0,
        x: 50,
        stagger: 0.1,
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.contact-form',
            start: 'top 80%',
            toggleActions: 'play none none none',
        },
    });
});

onUnmounted(() => {
    ScrollTrigger.getAll().forEach((trigger) => trigger.kill());
});
</script>

<template>
    <section id="contact" class="section-animate py-22 flex flex-col gap-4">
        <!-- Header -->
        <div class="">
            <h2 class="text-active text-3xl font-bold md:text-4xl">Get In Touch</h2>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Contact Information -->
            <div class="lg:col-span-1">
                <div
                    class="border-muted contact-methods-grid rounded-xl border bg-gradient-to-br from-black/2 to-black/6 px-6 py-8 shadow dark:from-white/2 dark:to-white/6"
                >
                    <h3 class="text-active border-muted mb-5 border-b pb-3 text-2xl font-bold">Contact Information</h3>

                    <div class="space-y-4">
                        <a href="mailto:support@spotly.com" class="contact-method-card flex items-start space-x-4 cursor-pointer px-2 py-2 rounded-md hover:bg-white/8">
                            <div
                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-purple-600"
                            >
                                <Mail class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h4 class="font-semibold text-active">Email</h4>
                                <p class="text-active-link text-sm">support@spotly.com</p>
                            </div>
                        </a>

                        <a href="tel:+1 (555) 123-4567" class="contact-method-card flex items-start space-x-4 cursor-pointer px-2 py-2 rounded-md hover:bg-white/8">
                            <div
                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-green-500 to-blue-600"
                            >
                                <Phone class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h4 class="font-semibold text-active">Phone</h4>
                                <p class="text-active-link text-sm">+1 (555) 123-4567</p>
                            </div>
                        </a>

                        <div class="contact-method-card flex items-start space-x-4 px-2 py-2 rounded-md hover:bg-white/8">
                            <div
                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-purple-500 to-pink-600"
                            >
                                <MapPin class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h4 class="font-semibold text-active">Address</h4>
                                <p class="text-sm">123 Business Street, New York, NY 10001</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-muted bg-gradient-to-br from-black/2 to-black/6 px-6 py-8 shadow dark:from-white/2 dark:to-white/6">
                    <h3 class="text-active border-muted mb-5 border-b pb-3 text-2xl font-bold">Send us a Message</h3>

                    <form @submit.prevent="submitForm" class="contact-form space-y-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <Label for="name" class="mb-1">Your Name</Label>
                                <Input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    required
                                    placeholder="John Doe"
                                />
                            </div>
                            <div>
                                <Label for="email" class="mb-1">Your Email</Label>
                                <Input
                                    type="email"
                                    id="email"
                                    v-model="form.email"
                                    required
                                    placeholder="john.doe@example.com"
                                />
                            </div>
                        </div>

                        <div>
                            <Label for="subject" class="mb-1">Subject</Label>
                            <Input
                                type="text"
                                id="subject"
                                v-model="form.subject"
                                required
                                placeholder="How can we help you?"
                            />
                        </div>

                        <div>
                            <Label for="message" class="mb-1">Your Message</Label>
                            <Textarea
                                id="message"
                                v-model="form.message"
                                rows="6"
                                required
                                placeholder="Tell us more about your inquiry..."
                            ></Textarea>
                        </div>

                        <div class="text-end">
                            <Button type="submit">
                                <Send class="size-4" />
                                <span>Send Message</span>
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
