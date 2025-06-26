export interface WizardContent {
    website_type: string;
    logo: string;
    name: string;
    subdomain: string;
    description: string;
    language: string;
    country: string;
    city: string;
    address: string;
    phone_number: string;
    email: string;
    instagram: string;
    facebook: string;
    tiktok: string;
    errors?: Record<string, string>;
}

export function useWizard(step: number, form: WizardContent) {
    const errors: Record<string, string> = {};

    if (step === 1) {
        if (!form.website_type) errors.website_type = 'Website type is required.';
        if (!form.logo) errors.logo = 'Logo is required.';
        if (!form.name) errors.name = 'Business name is required.';
        if (!form.subdomain) errors.subdomain = 'Subdomain is required.';
        if (!form.language) errors.language = 'Language is required.';
        if (!form.description) errors.description = 'Description is required.';
    }

    if (step === 2) {
        if (!form.country) errors.country = 'Country is required.';
        if (!form.city) errors.city = 'City is required.';
        if (!form.address) errors.address = 'Address is required.';
    }

    if (step === 3) {
        if (!form.phone_number) errors.phone_number = 'Phone number is required.';
        if (!form.email) errors.email = 'Email is required.';
        // You can add more validations as needed
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
