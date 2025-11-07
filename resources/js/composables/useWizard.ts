import { SharedData } from "@/types";
import { usePage } from "@inertiajs/vue3";

export interface WizardContent {
    website_type_id: number;
    name: string;
    subdomain: string;
    about_us: string;
    language: string;

    email: string;

    template_id: string,

    acceptSteps: boolean,

    errors?: Record<string, string>,
}

export function useWizard(step: number, form: WizardContent) {
    const errors: Record<string, string> = {};
    const page = usePage<SharedData>();

    if (step === 1) {
        if (!form.website_type_id) errors.website_type_id = page.props.lang === 'ar'
            ? 'نوع الموقع مطلوب.'
            : 'Website type is required.';
        if (!form.language) errors.language = page.props.lang === 'ar'
            ? 'اللغة مطلوبة.'
            : 'Language is required.';
        if (!form.name) errors.name = page.props.lang === 'ar'
            ? 'اسم النشاط التجاري مطلوب.'
            : 'Business name is required.';
        if (!form.subdomain) errors.subdomain = page.props.lang === 'ar'
            ? 'النطاق الفرعي مطلوب.'
            : 'Subdomain is required.';
        if (!form.about_us) errors.about_us = page.props.lang === 'ar'
            ? 'النبذة عن موقعك مطلوبة'
            : 'About us is required.';

    } else if (step === 2) {
        if (!form.email) errors.email = page.props.lang === 'ar'
            ? 'البريد الإلكتروني مطلوب.'
            : 'Email address is required.';

    } else if (step === 3) {
        if (!form.template_id) errors.template_id = page.props.lang === 'ar'
            ? 'يرجى اختيار قالب.'
            : 'Please choose a template.';

    } else if (step === 4) {
        if (!form.acceptSteps) errors.acceptSteps = page.props.lang === 'ar'
            ? 'يرجى قبول جميع الخطوات.'
            : 'Please accept all steps';
    }

    // Set errors into form
    form.errors = errors;

    // Return if step is valid
    const isValid = Object.keys(errors).length === 0;
    return {
        isValid,
        errors,
    };
}
