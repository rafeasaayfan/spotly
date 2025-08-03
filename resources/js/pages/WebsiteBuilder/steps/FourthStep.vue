<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { Input, InputError, PhoneNumberField } from '@/components/ui/fields';
import { CreditCard } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    form: {
        user_phone_number: string;
        user_email: string;
        errors?: Record<string, string>;
    };
    countries: Record<string, any>;
}>();

const emit = defineEmits<{
    (e: 'update', field: string, value: string | boolean | Record<string, any> | File): void;
}>();

const user_phone_number = computed({
    get: () => props.form.user_phone_number,
    set: (val) => emit('update', 'user_phone_number', val),
});

const user_email = computed({
    get: () => props.form.user_email,
    set: (val) => emit('update', 'user_email', val),
});

// Map country phone codes for phone number field
const mappedCountryPhones = props.countries.map((item: any) => ({
    value: item.phone_code,
    label: item.phone_code,
    icon: item.flag,
}));
</script>

<template>
    <div key="step3" class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2 md:gap-y-10">
        <div class="border-muted col-span-1 w-full border-b pb-3 md:col-span-3">
            <h1 class="flex items-center gap-2 text-xl font-bold sm:text-2xl">
                <CreditCard class="text-active-link size-5 sm:size-6" />
                <span class="gradient-text">Billing & Plan</span>
            </h1>
        </div>

        <!-- Phone and Email -->
        <div class="grid grid-cols-1 gap-6 md:col-span-3 md:grid-cols-2">
            <div class="col-span-1 flex flex-col gap-2">
                <HeadingSmall title="Phone Number*" description="Enter your business contact number." />
                <div class="flex flex-col gap-1 ps-2">
                    <PhoneNumberField v-model="user_phone_number" :options="mappedCountryPhones" selectedCode="+961" />
                    <InputError v-if="props.form.errors?.phone_number" :message="props.form.errors.phone_number" />
                </div>
            </div>

            <div class="col-span-1 flex flex-col gap-2">
                <HeadingSmall title="Email Address*" description="Enter your public contact email address." />
                <div class="flex flex-col gap-1 ps-2">
                    <Input v-model="user_email" type="email" placeholder="contact@example.com" />
                    <InputError v-if="props.form.errors?.user_email" :message="props.form.errors.user_email" />
                </div>
            </div>
        </div>

        <!-- Plans -->
        <div class="grid grid-cols-1 gap-6 md:col-span-3 md:grid-cols-2">

        </div>
    </div>
</template>
