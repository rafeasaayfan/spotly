<script setup lang="ts">
import '../../../css/landing.css';

import { Head, useForm } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';

import { Button } from '@/components/ui/button';
import FirstStep from './steps/FirstStep.vue';
import SecondStep from './steps/SecondStep.vue';
import ThirdStep from './steps/ThirdStep.vue';

import { useWizard } from '@/composables/useWizard';
import { toast } from '@/lib/sweetAlert';
import Icons from './Icons.vue';

const props = defineProps<{
    websiteTypes: Record<string, any>;
    type: string;
    typeId: string;
    countries: Record<string, any>;
    cities: Array<string>;
    templates: Record<string, any>;
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
    about_us: '',
    country: '',
    city: '',
    address: '',
    language: '',
    phone_number: '',
    email: '',
    instagram: '',
    facebook: '',
    tiktok: '',
    youtube: '',
    template_id: '',
    template_color_id: '',
});

const currentStep = ref(1);
const totalSteps = 4;

const nextStep = () => {
    const { isValid } = useWizard(currentStep.value, form);

    if (!isValid) {
        // toast.fire({ icon: 'error', title: 'Please fix the errors before proceeding.' });
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
    // toast.fire({ icon: 'success', title: 'Website creation process started!' });
};

const updateField = (field: string, value: string) => {
    (form as any)[field] = value;
};
</script>

<template>
    <Head title="Create Your Website" />

    <section class="relative flex min-h-screen w-full items-center justify-center py-12 overflow-hidden px-4 md:px-0">

        <Icons :website_type="form.website_type" />

        <div class="flex w-full max-w-6xl flex-col gap-10">
            <!-- Form Card -->
            <div class="border-muted relative rounded-xl bg-black/2 px-3 py-5 md:px-5 dark:bg-white/2 z-10 backdrop-blur-[2px]">
                <form @submit.prevent="submitForm" class="flex flex-col gap-8">
                    <Transition name="slide-fade" mode="out-in">
                        <FirstStep v-if="currentStep === 1" :form="form" @update="updateField" :websiteTypes="props.websiteTypes" />

                        <SecondStep
                            v-else-if="currentStep === 2"
                            :form="form"
                            @update="updateField"
                            :countries="props.countries"
                            :cities="props.cities"
                        />

                        <ThirdStep v-else-if="currentStep === 3" :form="form" :templates="props.templates" @update="updateField" />

                        <!-- <FourthStep v-else-if="currentStep === 4" :form="form" @update="updateField" /> -->
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
                        <Button v-else type="submit" class="glow-button">Create Website</Button>
                    </div>
                </form>

                <div class="absolute start-0 top-1 flex h-1 w-full -translate-y-1/2 justify-end">
                    <div class="grid w-55 grid-cols-4 gap-2">
                        <div v-for="step in totalSteps" :key="step"
                            class="transition-all duration-500 rounded-full" :class="step <= currentStep ? 'bg-primary w-full' : 'bg-black/5 dark:bg-white/5 w-full'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
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
