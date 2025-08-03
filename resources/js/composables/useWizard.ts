export interface WizardContent {
    website_type_id: number;
    lightLogo: null,
    darkLogo: null,
    name: string;
    subdomain: string;
    about_us: string;
    language: string;
    country: string;
    city: string;
    address: string;
    phone_number: string;
    email: string;
    instagram: string;
    facebook: string;
    tiktok: string;
    youtube: string;
    template_id: string,
    template_color_id: string,
    custom_template_color: boolean,
    colors: Record<string, any>,
    errors?: Record<string, string>;
}

export function useWizard(step: number, form: WizardContent) {
    const errors: Record<string, string> = {};

    if (step === 1) {
        if (!form.website_type_id) errors.website_type_id = 'Website type is required.';
        if (!form.language) errors.language = 'Language is required.';
        if (!form.name) errors.name = 'Business name is required.';
        if (!form.subdomain) errors.subdomain = 'Subdomain is required.';
        if (!form.about_us) errors.about_us = 'About us is required.';
    }

    if (step === 2) {
        if (!form.phone_number) errors.phone_number = 'Phone Number is required.';
    }

    if (step === 3) {
        if (!form.template_id) errors.template_id = 'Please choose a template';
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
