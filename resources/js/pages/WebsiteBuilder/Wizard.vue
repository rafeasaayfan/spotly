<script setup lang="ts">
import '../../../css/landing.css';

import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
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
import { SharedData } from '@/types';
// import Icons from './Icons.vue';

const props = defineProps<{
    websiteTypes: Record<string, any>;
    type: string;
    typeId: number;
    countries: Record<string, any>;
    cities: Record<string, any>;
    templateTemplateColors: Record<string, any>;
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

const page = usePage<SharedData>();

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
        toast.fire({
            icon: 'error',
            title: page.props.lang === 'ar' ? 'الرجاء إصلاح الأخطاء قبل المتابعة.' : 'Please fix the errors before proceeding.',
        });
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
    <Head :title="$t('websiteBuilder.wizard.head_title')" />

    <section
        class="landing-body wizard-background landing-background-decor relative flex min-h-screen w-full items-center justify-center overflow-hidden px-4 py-12 xl:px-0"
        :dir="page.props.lang === 'ar' ? 'rtl' : 'ltr'"
    >
        <!-- <Icons :website_type_id="form.website_type_id" /> -->

        <div class="flex w-full max-w-7xl flex-col gap-10">
            <!-- Form Card -->
            <div class="border-muted relative z-10 border-e-3 border-dashed px-3 py-5 md:px-5">
                <form class="flex flex-col gap-8">
                    <Transition name="slide-fade" mode="out-in">
                        <div v-if="currentStep === 1" class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-3 md:gap-y-10">
                            <div
                                class="border-muted relative col-span-1 flex w-full flex-wrap items-center justify-between gap-1 border-b pb-2 md:col-span-3"
                            >
                                <div
                                    class="pointer-events-none absolute start-0 top-0 h-full w-full rounded-full bg-gradient-to-br from-transparent via-black to-transparent opacity-15 blur-xl dark:via-white dark:opacity-5"
                                ></div>

                                <h1 class="text-active flex items-center gap-2 text-xl font-bold sm:text-2xl">
                                    <Store class="size-5 sm:size-6" />
                                    <span>{{ $t('websiteBuilder.wizard.business_information') }}</span>
                                </h1>

                                <div class="flex items-center space-x-2">
                                    <AppearenceBtn />

                                    <LanguagesMenu />
                                </div>
                            </div>

                            <div
                                class="border-muted col-span-1 flex h-full w-full items-center justify-center rounded-md border bg-black/2 py-10 backdrop-blur-[1px] dark:bg-white/2"
                            >
                                <Link :href="route('landing')">
                                    <AppLogoIcon class="size-40 md:size-50" />
                                </Link>
                            </div>

                            <div class="col-span-1 grid grid-cols-1 gap-x-5 gap-y-8 md:col-span-2 md:grid-cols-3 md:gap-y-10">
                                <FirstStep :form="form" @update="updateField" :websiteTypes="props.websiteTypes" :type="type" />
                            </div>
                        </div>
                        <div v-else-if="currentStep === 2" class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-3 md:gap-y-10">
                            <div
                                class="border-muted relative col-span-1 flex w-full flex-wrap items-center justify-between gap-1 border-b pb-2 md:col-span-3"
                            >
                                <div
                                    class="pointer-events-none absolute start-0 top-0 h-full w-full rounded-full bg-gradient-to-br from-transparent via-black to-transparent opacity-15 blur-xl dark:via-white dark:opacity-5"
                                ></div>

                                <h1 class="text-active flex items-center gap-2 text-xl font-bold sm:text-2xl">
                                    <Phone class="size-5 sm:size-6" />
                                    <span>{{ $t('websiteBuilder.wizard.contact_information') }}</span>
                                </h1>

                                <div class="flex items-center space-x-2">
                                    <AppearenceBtn />
                                </div>
                            </div>

                            <div
                                class="border-muted col-span-1 flex h-full w-full items-center justify-center rounded-md border bg-black/2 py-10 backdrop-blur-[1px] dark:bg-white/2"
                            >
                                <Link :href="route('landing')">
                                    <AppLogoIcon class="size-40 md:size-50" />
                                </Link>
                            </div>

                            <div class="col-span-1 grid grid-cols-1 gap-x-5 gap-y-8 md:col-span-2 md:grid-cols-3 md:gap-y-10">
                                <SecondStep :form="form" @update="updateField" :countries="props.countries" :cities="props.cities" />
                            </div>
                        </div>
                        <div v-else-if="currentStep === 3" class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-3 md:gap-y-10">
                            <div
                                class="border-muted relative col-span-1 flex w-full flex-wrap items-center justify-between gap-1 border-b pb-2 md:col-span-3"
                            >
                                <div
                                    class="pointer-events-none absolute start-0 top-0 h-full w-full rounded-full bg-gradient-to-br from-transparent via-black to-transparent opacity-15 blur-xl dark:via-white dark:opacity-5"
                                ></div>

                                <h1 class="text-active flex items-center gap-2 text-xl font-bold sm:text-2xl">
                                    <LayoutTemplate class="size-5 sm:size-6" />
                                    <span>{{ $t('websiteBuilder.wizard.templates_ui') }}</span>
                                </h1>

                                <div class="flex items-center space-x-2">
                                    <AppearenceBtn />
                                </div>
                            </div>

                            <div
                                class="border-muted col-span-1 flex h-full w-full items-center justify-center rounded-md border bg-black/2 py-10 backdrop-blur-[1px] dark:bg-white/2"
                            >
                                <Link :href="route('landing')">
                                    <AppLogoIcon class="size-40 md:size-50" />
                                </Link>
                            </div>

                            <div class="col-span-1 grid grid-cols-1 gap-x-5 gap-y-8 md:col-span-2 md:grid-cols-3 md:gap-y-10">
                                <ThirdStep
                                    :form="form"
                                    :type="props.type"
                                    :templateTemplateColors="props.templateTemplateColors"
                                    @update="updateField"
                                />
                            </div>
                        </div>
                        <div v-else-if="currentStep === 4" class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-3 md:gap-y-10">
                            <div
                                class="border-muted relative col-span-1 flex w-full flex-wrap items-center justify-between gap-1 border-b pb-2 md:col-span-3"
                            >
                                <div
                                    class="pointer-events-none absolute start-0 top-0 h-full w-full rounded-full bg-gradient-to-br from-transparent via-black to-transparent opacity-15 blur-xl dark:via-white dark:opacity-5"
                                ></div>

                                <h1 class="text-active flex items-center gap-2 text-xl font-bold sm:text-2xl">
                                    <CreditCard class="size-5 sm:size-6" />
                                    <span>{{ $t('websiteBuilder.wizard.billing_plan') }}</span>
                                </h1>

                                <div class="flex items-center space-x-2">
                                    <AppearenceBtn />
                                </div>
                            </div>

                            <div
                                class="border-muted col-span-1 flex h-full w-full items-center justify-center rounded-md border bg-black/2 py-10 backdrop-blur-[1px] dark:bg-white/2"
                            >
                                <Link :href="route('landing')">
                                    <AppLogoIcon class="size-40 md:size-50" />
                                </Link>
                            </div>

                            <div class="col-span-1 grid grid-cols-1 gap-x-5 gap-y-8 md:col-span-2 md:grid-cols-3 md:gap-y-10">
                                <FourthStep :form="form" @update="updateField" />
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
                            {{ $t('websiteBuilder.wizard.previous') }}
                        </Button>
                        <Button
                            type="button"
                            @click="submitForm"
                            :class="currentStep < totalSteps ? '' : 'glow-button'"
                            :disabled="(currentStep === totalSteps && !form.acceptSteps) || form.processing"
                        >
                            <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                            <template v-if="currentStep < totalSteps">{{ $t('websiteBuilder.wizard.next_step') }}</template>
                            <template v-else>{{ $t('websiteBuilder.wizard.create_website') }}</template>
                        </Button>
                    </div>
                </form>

                <div class="absolute -start-42 top-42 w-full">
                    <div class="grid w-80 rotate-90 grid-cols-4 gap-2 [direction:ltr]">
                        <div
                            v-for="step in totalSteps"
                            :key="step"
                            class="h-[5.3px] rounded-full backdrop-blur transition-all duration-500"
                            :class="step <= currentStep ? 'bg-primary w-full' : 'w-full bg-black/15 dark:bg-white/15'"
                        ></div>
                    </div>
                </div>

                <div
                    v-if="form.website_type_id"
                    class="absolute -bottom-5 flex h-fit w-full items-center justify-center xl:top-1/2 xl:bottom-0 xl:block xl:w-fit xl:translate-x-0 xl:-translate-y-1/2"
                    :class="page.props.lang === 'ar' ? 'xl:start-[96%]' : 'xl:start-[90%]'"
                >
                    <div
                        class="text-body-muted h-full text-sm font-bold text-nowrap uppercase sm:text-lg"
                        :class="page.props.lang === 'ar' ? 'xl:-rotate-90 xl:text-4xl' : 'tracking-[1rem] xl:rotate-90 xl:text-2xl'"
                    >
                        {{ $t(props.websiteTypes.find((type: { id: number; title: string }) => type.id === form.website_type_id)?.title) }}
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
