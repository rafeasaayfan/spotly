<script setup lang="ts">
import '../../../css/landing.css';

import { Head, useForm } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';

import { Button } from '@/components/ui/button';
import { Check, MapPin, Phone, Store } from 'lucide-vue-next';
import FirstStep from './steps/FirstStep.vue';
import SecondStep from './steps/SecondStep.vue';
import ThirdStep from './steps/ThirdStep.vue';

import { toast } from '@/lib/sweetAlert';
import { useWizard } from '@/composables/useWizard';

const props = defineProps<{
    websiteTypes: Record<string, any>;
    type: string;
    typeId: string;
    countries: Record<string, any>;
    flash?: {
        message?: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: 'success', title: message });
    }
});

const form = useForm({
    website_type: props.typeId ?? '',
    logo: '',
    name: '',
    subdomain: '',
    description: '',
    country: '',
    city: '',
    address: '',
    language: '',
    phone_number: '',
    email: '',
    instagram: '',
    facebook: '',
    tiktok: '',
});

const currentStep = ref(1);
const totalSteps = 3;

const steps = [
    { id: 1, name: 'Business Info', icon: Store },
    { id: 2, name: 'Contact Info', icon: Phone },
    { id: 3, name: '', icon: MapPin },
];

const nextStep = () => {
    const { isValid } = useWizard(currentStep.value, form);

    if (!isValid) {
        toast.fire({ icon: 'error', title: 'Please fix the errors before proceeding.' });
        // return;
    }

    if (currentStep.value < totalSteps) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const submitForm = () => {
    // Here you would typically post the form
    // form.post('/your-submission-route', { ... });
    console.log('Form submitted!', form.data());
    toast.fire({ icon: 'success', title: 'Website creation process started!' });
};

const updateField = (field: string, value: string) => {
    (form as any)[field] = value;
};
</script>

<template>
    <Head title="Create Your Website" />

    <section class="flex min-h-screen w-full items-center justify-center py-12">
        <div class="flex w-full max-w-6xl flex-col gap-10">
            <!-- Step Indicator -->
            <div class="relative flex h-12 items-center justify-between">
                <!-- Progress Line -->
                <div class="bg-black-5 absolute top-1/2 left-0 h-1 w-full -translate-y-1/2 dark:bg-white/5"></div>

                <div
                    class="bg-primary absolute top-1/2 left-0 h-1 -translate-y-1/2 transition-all duration-500"
                    :style="{ width: `${((currentStep - 1) / (totalSteps - 1)) * 100}%` }"
                ></div>

                <!-- Step Points -->
                <div v-for="step in steps" :key="step.id" class="relative z-10 flex flex-col items-center gap-1 text-center">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full border-2 transition-all duration-300 backdrop-blur-lg"
                        :class="currentStep >= step.id ? (currentStep > step.id ? 'bg-primary text-active border-transparent' : 'bg-destructive text-active border-transparent') : 'bg-content border-muted text-body-muted'"
                    >
                        <Check v-if="currentStep > step.id" class="h-6 w-6" />
                        <component v-else :is="step.icon" class="h-6 w-6" />
                    </div>
                    <!-- <span
                        class="text-xs font-semibold transition-colors duration-300 sm:text-sm"
                        :class="currentStep >= step.id ? 'text-active' : 'text-body-muted'"
                    >
                        {{ step.name }}
                    </span> -->
                </div>
            </div>

            <!-- Form Card -->
            <div class="border-muted relative rounded-xl bg-black/3 p-5 dark:bg-white/2">
                <form @submit.prevent="submitForm" class="flex flex-col gap-8">
                    <Transition name="slide-fade" mode="out-in">
                        <FirstStep v-if="currentStep === 1" :form="form" @update="updateField" :websiteTypes="props.websiteTypes" />

                        <SecondStep v-else-if="currentStep === 2" :form="form" @update="updateField" :countries="props.countries" />

                        <ThirdStep v-else-if="currentStep === 3" :form="form" @update="updateField" />
                    </Transition>

                    <!-- Navigation Buttons -->
                    <div class="border-muted flex justify-between border-t pt-4">
                        <Button
                            type="button"
                            @click="prevStep"
                            :disabled="currentStep === 1"
                            variant="secondary"
                            :class="currentStep === 1 ? 'cursor-not-allowed' : ''"
                        >
                            Previous
                        </Button>
                        <Button v-if="currentStep < totalSteps" type="button" @click="nextStep"> Next Step </Button>
                        <Button v-else type="submit" class="glow-button"> Create Website </Button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<style>
/* Transition for the form steps */
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-fade-enter-from {
    opacity: 0;
    transform: translateX(10px);
}
.slide-fade-leave-to {
    opacity: 0;
    transform: translateX(-10px);
}
</style>
