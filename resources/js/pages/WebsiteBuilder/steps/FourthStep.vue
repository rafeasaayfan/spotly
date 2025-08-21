<script setup lang="ts">
import { Checkbox, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { CreditCard, HelpCircle } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    form: {
        acceptSteps: boolean
        errors?: Record<string, string>;
    };
}>();

const emit = defineEmits<{
    (e: 'update', field: string, value: string | boolean | Record<string, any> | File): void;
}>();

const acceptSteps = computed({
    get: () => props.form.acceptSteps,
    set: (val) => emit('update', 'acceptSteps', val),
});

const creationSteps = [
    {
        text: 'Once you finish this form, you’ll register with your name, email, and password (or Google)',
    },
    {
        text: 'Your request will be sent to our team for approval. You’ll receive an email once approved',
    },
    {
        text: 'Your website will be deployed with a free subdomain and activated for a',
        specialText: '3-day free trial',
    },
    {
        text: 'To keep your site online after the trial, you’ll need to make a payment. One month payment = 30 days extra',
    },
];
</script>

<template>
    <div key="step3" class="grid grid-cols-1 gap-x-6 gap-y-8 md:gap-y-10">
        <div class="border-muted w-full border-b pb-3">
            <h1 class="flex items-center gap-2 text-xl font-bold sm:text-2xl">
                <CreditCard class="text-active-link size-5 sm:size-6" />
                <span class="gradient-text">Billing & Plan</span>
            </h1>
        </div>

        <div class="bg-gradient-to-br from-black/3 dark:from-white/3 to-transparent border-muted flex w-full flex-col rounded-md
           border px-2 md:px-5 py-4 gap-4">
            <!-- Title -->
            <div class="flex items-center gap-2 text-xs md:text-sm font-bold mb-2 bg-blue-600/15 dark:bg-blue-500/10 rounded-md w-fit px-3 py-2">
                <HelpCircle class="size-3.5 md:size-4.5 text-yellow-500" />                
                <span>How Your Website Will Be Created</span>
            </div>

            <!-- Steps -->
            <div class="flex flex-col gap-3">
                <div v-for="(step, index) in creationSteps" :key="index" class="flex items-center gap-3">
                    <div class="flex size-5 md:size-6.5 flex-shrink-0 items-center justify-center rounded-full 
                        bg-red-500/15 dark:bg-red-700/15 text-xs font-bold text-red-500/70">
                        {{ index + 1 }}
                    </div>
                    <p class="text-xs md:text-sm">
                        {{ step.text }} <strong v-if="step.specialText" class="text-active">{{ step.specialText }}</strong>
                    </p>
                </div>
            </div>

            <!-- Terms Checkbox -->
            <div class="border-muted mt-2 border-t pt-4 flex flex-col gap-2">
                <Label for="accept" class="text-xs md:text-sm hover:font-bold">
                    <Checkbox id="accept" v-model="acceptSteps" class="min-w-[19px]" />
                    <span>I have read and understood how the website creation process works and I accept the terms.</span>
                </Label>

                <InputError v-if="props.form.errors?.acceptSteps" :message="props.form.errors.acceptSteps" />
            </div>
        </div>
    </div>
</template>
