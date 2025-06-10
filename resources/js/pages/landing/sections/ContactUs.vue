<script setup lang="ts">
import { onMounted, ref } from 'vue';

import { gsap } from 'gsap';
import Button from '@/components/ui/button/Button.vue';
import { Input, Textarea } from '@/components/ui/fields';

const formFeedbackText = ref('');
const formFeedbackClass = ref('mt-4 text-sm');

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
    gsap.from("#contact-form button[type='submit']", {
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

    const contactForm = document.getElementById('contact-form') as HTMLFormElement | null;
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            formFeedbackText.value = 'Thank you! Your message has been "sent". (Demo only)';
            formFeedbackClass.value = 'mt-4 text-sm text-green-400';
            contactForm.reset();
            setTimeout(() => {
                formFeedbackText.value = '';
                formFeedbackClass.value = 'mt-4 text-sm';
            }, 5000);
        });
    }
});
</script>

<template>
    <section id="contact-us" class="py-22 px-0 md:px-4">
        <div class="px-4 mx-auto text-center flex flex-col items-center justify-center min-h-[650px] bg-black/3 backdrop-blur-[2px] dark:bg-white/2 rounded-md">
            <div class="flex flex-col gap-3 mb-16">
                <h2 class="section-title section-title-underline text-active text-3xl sm:text-4xl md:text-4xl lg:text-5xl font-bold">
                    Get In <span class="gradient-text">Touch</span>
                </h2>
                <p class="text-body-muted">Have questions or ready to start your Spotly journey? We'd love to hear from you!</p>
            </div>

            <form id="contact-form" class="space-y-6 min-w-full md:min-w-2xl lg:min-w-4xl max-w-full">
                <div>
                    <Input type="text" name="name" placeholder="Your Name" class="h-11" />
                </div>
                <div>
                    <Input type="email" name="email" placeholder="Your Email" class="h-11" />
                </div>
                <div>
                    <Textarea name="message" placeholder="Your Message" />
                </div>
                <div>
                    <Button
                        type="submit"
                        size="lg"
                        class="glow-button w-full"
                    >
                        Send Message
                    </Button>
                </div>
            </form>
            <div :class="formFeedbackClass" v-text="formFeedbackText"></div>
        </div>
    </section>
</template>
