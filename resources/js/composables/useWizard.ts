export interface WizardContent {
    website_type_id: number;
    name: string;
    subdomain: string;
    about_us: string;
    language: string;

    phone_number: string;

    template_id: string,

    acceptSteps: boolean,

    errors?: Record<string, string>,
}

export function useWizard(step: number, form: WizardContent) {
    const errors: Record<string, string> = {};

    if (step === 1) {
        if (!form.website_type_id) errors.website_type_id = 'Website type is required.';
        if (!form.language) errors.language = 'Language is required.';
        if (!form.name) errors.name = 'Business name is required.';
        if (!form.subdomain) errors.subdomain = 'Subdomain is required.';
        if (!form.about_us) errors.about_us = 'About us is required.';

    } else if (step === 2) {
        if (!form.phone_number) errors.phone_number = 'Phone Number is required.';

    } else if (step === 3) {
        if (!form.template_id) errors.template_id = 'Please choose a template.';

    } else if (step === 4) {
        if (!form.acceptSteps) errors.acceptSteps = 'Please accept all steps';
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
