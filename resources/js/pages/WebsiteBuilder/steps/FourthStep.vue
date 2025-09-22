<script setup lang="ts">
import { Checkbox, InputError } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { HelpCircle } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    form: {
        acceptSteps: boolean;
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

const page = usePage<SharedData>();

const creationSteps = () => {
    if (page.props.lang === 'ar') {
        return [
            {
                text: 'بمجرد إكمال هذا النموذج، ستسجل باسمك وبريدك الإلكتروني وكلمة المرور (أو عبر Google)',
            },
            {
                text: 'سيتم إرسال طلبك إلى فريقنا للمراجعة. ستتلقى بريداً إلكترونياً عند الموافقة',
            },
            {
                text: 'سيتم نشر موقعك مع نطاق فرعي مجاني وتفعيله لمدة',
                specialText: 'تجربة مجانية لمدة 3 أيام',
            },
            {
                text: 'للاحتفاظ بموقعك بعد انتهاء الفترة التجريبية، ستحتاج إلى إجراء الدفع. دفع شهر واحد = 30 يوماً إضافية',
            },
        ];
    }

    return [
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
};
</script>

<template>
    <div
        class="border-muted col-span-1 flex w-full flex-col gap-4 rounded-md border bg-gradient-to-br from-black/3 to-transparent px-2 py-4 backdrop-blur-[2px] md:col-span-3 md:px-5 dark:from-white/3"
    >
        <!-- Title -->
        <div class="mb-2 flex w-fit items-center gap-2 rounded-md bg-blue-600/15 px-3 py-2 text-xs font-bold md:text-sm dark:bg-blue-500/10">
            <HelpCircle class="size-3.5 text-yellow-500 md:size-4.5" />
            <span>{{ $t('websiteBuilder.fourthStep.how_website_created_title') }}</span>
        </div>

        <!-- Steps -->
        <div class="flex flex-col gap-3">
            <div v-for="(step, index) in creationSteps()" :key="index" class="flex items-center gap-3">
                <div
                    class="flex size-5 flex-shrink-0 items-center justify-center rounded-full bg-red-500/15 text-xs font-bold text-red-500/70 md:size-6.5 dark:bg-red-700/15"
                >
                    {{ index + 1 }}
                </div>
                <p class="text-xs md:text-sm">
                    {{ step.text }} <strong v-if="step.specialText" class="text-active">{{ step.specialText }}</strong>
                </p>
            </div>
        </div>

        <!-- Terms Checkbox -->
        <div class="border-muted mt-2 flex flex-col gap-2 border-t pt-4">
            <Label for="accept" class="text-xs hover:font-bold md:text-sm" :class="acceptSteps ? 'text-active' : ''">
                <Checkbox id="accept" v-model="acceptSteps" class="min-w-[19px]" />
                <span>{{ $t('websiteBuilder.fourthStep.accept_terms_text') }}</span>
            </Label>

            <InputError v-if="props.form.errors?.acceptSteps" :message="props.form.errors.acceptSteps" />
        </div>
    </div>
</template>
