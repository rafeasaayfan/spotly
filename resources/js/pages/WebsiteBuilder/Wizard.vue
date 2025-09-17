<script setup lang="ts">
import '../../../css/landing.css';

import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';

import { Button } from '@/components/ui/button';
import FirstStep from './steps/FirstStep.vue';
import FourthStep from './steps/FourthStep.vue';
import SecondStep from './steps/SecondStep.vue';
import ThirdStep from './steps/ThirdStep.vue';

import AppLogoIcon from '@/components/logo/AppLogoIcon.vue';
import AppearenceBtn from '@/components/appearance/AppearanceBtn.vue';
import LanguagesMenu from '@/components/languages/Languages.vue';

import { useWizard } from '@/composables/useWizard';
import { toast } from '@/lib/sweetAlert';
import { CreditCard, LayoutTemplate, LoaderCircle, Phone, Store } from 'lucide-vue-next';
// import Icons from './Icons.vue';

const props = defineProps<{
    websiteTypes: Record<string, any>;
    type: string;
    typeId: number;
    countries: Record<string, any>;
    cities: Array<string>;
    templates: Record<string, any>;
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info';
        message: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
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
    country: 'Lebanon',
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
    template_images: [],

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

    form.post(route('websiteBuilder.wizard', { step: currentStep.value }), {
        onSuccess() {
            if (currentStep.value < totalSteps) {
                currentStep.value++;
            }
        },
    });
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const typeRef = ref(props.type);

const updateField = (field: string, value: any) => {
    if (field === 'type') {
        typeRef.value = value;
        fetchNewType();
    } else {
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

    <section class="relative flex min-h-screen w-full items-center justify-center overflow-hidden px-4 py-12 xl:px-0 wizard-background landing-background-decor">
        <!-- <Icons :website_type_id="form.website_type_id" /> -->

        <div class="flex w-full max-w-7xl flex-col gap-10">
            <!-- Form Card -->
            <div class="border-muted relative z-10 border-e-3 border-dashed px-3 py-5 md:px-5">
                <form class="flex flex-col gap-8">
                    <Transition name="slide-fade" mode="out-in" class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-3 md:gap-y-10">
                        <div v-if="currentStep > 0">
                            <div class="border-muted relative col-span-1 flex w-full items-center gap-1 flex-wrap justify-between border-b pb-2 md:col-span-3">
                                <div
                                    class="pointer-events-none absolute start-0 top-0 h-full w-full rounded-full bg-gradient-to-br from-transparent via-black
                                    dark:via-white to-transparent opacity-15 dark:opacity-5 blur-xl"
                                ></div>

                                <h1 class="flex items-center gap-2 text-xl font-bold sm:text-2xl text-active">
                                    <template v-if="currentStep === 1">
                                        <Store class="size-5 sm:size-6" />
                                        <span>Business Information</span>
                                    </template>
                                    <template v-else-if="currentStep === 2">
                                        <Phone class="size-5 sm:size-6" />
                                        <span>Contact Information</span>
                                    </template>
                                    <template v-else-if="currentStep === 3">
                                        <LayoutTemplate class="size-5 sm:size-6" />
                                        <span>Templates UI</span>
                                    </template>
                                    <template v-else-if="currentStep === 4">
                                        <CreditCard class="size-5 sm:size-6" />
                                        <span>Billing & Plan</span>
                                    </template>
                                </h1>

                                <div class="flex items-center space-x-2">
                                    <AppearenceBtn />

                                    <LanguagesMenu />
                                </div>
                            </div>

                            <div class="border-muted col-span-1 flex h-full w-full items-center justify-center rounded-md border bg-black/2 dark:bg-white/2
                            backdrop-blur-[1px] py-10">
                                <Link :href="route('landing')">
                                    <AppLogoIcon class="size-40 md:size-50" />
                                </Link>
                            </div>

                            <div class="col-span-1 md:col-span-2 grid grid-cols-1 gap-x-5 gap-y-8 md:grid-cols-3 md:gap-y-10">
                                <FirstStep
                                    v-if="currentStep === 1"
                                    :form="form"
                                    @update="updateField"
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
                                    :form="form"
                                    :type="props.type"
                                    :templates="props.templates"
                                    @update="updateField"
                                />

                                <FourthStep v-else-if="currentStep === 4" :form="form" @update="updateField" />
                            </div>
                        </div>
                    </Transition>

                    <!-- Navigation Buttons -->
                    <div class="border-muted flex justify-between rounded-full border-t-6 border-double pt-4">
                        <Button
                            type="button"
                            @click="prevStep"
                            :disabled="currentStep === 1"
                            variant="secondary"
                            :class="currentStep === 1 ? 'cursor-not-allowed' : ''"
                        >
                            Previous
                        </Button>
                        <Button
                            type="button"
                            @click="submitForm"
                            :class="currentStep < totalSteps ? '' : 'glow-button'"
                            :disabled="(currentStep === totalSteps && !form.acceptSteps) || form.processing"
                        >
                            <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                            <template v-if="currentStep < totalSteps">Next Step</template>
                            <template v-else>Create Website</template>
                        </Button>
                    </div>
                </form>

                <div class="absolute -start-42 top-42 w-full">
                    <div class="grid w-80 rotate-90 grid-cols-4 gap-2">
                        <div
                            v-for="step in totalSteps"
                            :key="step"
                            class="h-[5.3px] rounded-full transition-all duration-500 backdrop-blur"
                            :class="step <= currentStep ? 'bg-primary w-full' : 'w-full bg-black/15 dark:bg-white/15'"
                        ></div>
                    </div>
                </div>

                <div class="absolute -bottom-5 start-1/2 -translate-x-1/2 xl:bottom-0 xl:start-[90%] xl:top-1/2 x w-fit h-fit xl:translate-x-0 xl:-translate-y-1/2">
                    <div class="font-bold h-full text-nowrap text-sm sm:text-lg xl:text-2xl tracking-[1rem] xl:rotate-90 uppercase text-body-muted">
                        {{ props.websiteTypes.find((type: { id: number; title: string }) => type.id === form.website_type_id)?.title }}
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
