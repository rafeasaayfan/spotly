<script setup lang="ts">
import '../../../css/landing.css';

import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';

import { Button } from '@/components/ui/button';
import FirstStep from './steps/FirstStep.vue';
import SecondStep from './steps/SecondStep.vue';
import ThirdStep from './steps/ThirdStep.vue';
import FourthStep from './steps/FourthStep.vue';

import { useWizard } from '@/composables/useWizard';
import { toast } from '@/lib/sweetAlert';
import Icons from './Icons.vue';

const props = defineProps<{
    websiteTypes: Record<string, any>;
    type: string;
    typeId: number;
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
    website_type_id: props.typeId ?? null,
    name: '',
    subdomain: '',
    about_us: '',
    language: '',

    phone_number: '',
    email: '',
    address: '',
    country: '',
    city: '',
    instagram: '',
    facebook: '',
    tiktok: '',
    youtube: '',

    light_logo: null,
    dark_logo: null,
    template_id: '',
    template_color_id: '',
    custom_template_color: false,
    colors: [],

    acceptSteps: false,
});

const currentStep = ref(1);
const totalSteps = 4;

const submitForm = () => {
    const { isValid } = useWizard(currentStep.value, form);

    if (!isValid) {
        toast.fire({ icon: 'error', title: 'Please fix the errors before proceeding.' });
        return;
    }

    form.post(
        route('websiteBuilder.wizard', { step: currentStep.value }),
        {
            onSuccess() {
                if (currentStep.value < totalSteps) {
                    currentStep.value++;
                }
            },
        },
    );
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const typeRef = ref(props.type);

const updateField = (field: string, value: any) => {
    if(field === 'type') {
        typeRef.value = value;
        fetchNewType();

    }  else {
        (form as any)[field] = value;
    }
};

const fetchNewType = async () => {
    if (typeRef.value === props.type) return;

    try {
        router.get(route('websiteBuilder.index', { type: typeRef.value }), {}, { preserveState: true, replace: true });
    } catch (error) {
        console.error('Failed to fetch website type data:', error);
    }
};
</script>

<template>
    <Head title="Create Your Website" />

    <section class="relative flex min-h-screen w-full items-center justify-center overflow-hidden px-4 py-12 md:px-0">
        <Icons :website_type_id="form.website_type_id" />

        <div class="flex w-full max-w-6xl flex-col gap-10">
            <!-- Form Card -->
            <div class="border-muted relative z-10 rounded-xl bg-black/2 px-3 py-5 backdrop-blur-[2px] md:px-5 dark:bg-white/2">
                <form class="flex flex-col gap-8">
                    <Transition name="slide-fade" mode="out-in">
                        <FirstStep 
                            v-if="currentStep === 1" 
                            :form="form" @update="updateField" 
                            :websiteTypes="props.websiteTypes" 
                            :type="type"
                        />

                        <SecondStep
                            v-else-if="currentStep === 2"
                            :form="form"
                            @update="updateField"
                            :countries="props.countries"
                            :cities="props.cities"
                        />

                        <ThirdStep
                            v-else-if="currentStep === 3" 
                            :form="form" :type="props.type" 
                            :templates="props.templates" 
                            @update="updateField"
                        />

                        <FourthStep 
                            v-else-if="currentStep === 4" 
                            :form="form"
                            @update="updateField"
                        />
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
                        <Button type="button" @click="submitForm" :class="currentStep < totalSteps ? '' : 'glow-button'"
                            :disabled="currentStep === totalSteps && !form.acceptSteps">
                            <template v-if="currentStep < totalSteps">Next Step</template>
                            <template v-else>Create Website</template>
                        </Button>
                    </div>
                </form>

                <div class="absolute -start-2 -top-2 flex h-1 w-full -translate-y-1/2 justify-end">
                    <div class="grid w-55 grid-cols-4 gap-2">
                        <div
                            v-for="step in totalSteps"
                            :key="step"
                            class="rounded-full transition-all duration-500"
                            :class="step <= currentStep ? 'bg-primary w-full' : 'w-full bg-black/5 dark:bg-white/5'"
                        ></div>
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
