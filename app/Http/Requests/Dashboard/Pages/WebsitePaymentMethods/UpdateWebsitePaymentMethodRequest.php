<?php

namespace App\Http\Requests\Dashboard\Pages\WebsitePaymentMethods;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsitePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_id' => ['required', 'exists:websites,id'],
            'payment_method_id' => [
                'required',
                'exists:payment_methods,id',
                Rule::unique('website_payment_methods', 'payment_method_id')->where('website_id', $this->input('website_id'))
                ->ignore($this->route('websitePaymentMethod')->id),
            ],
            'settings' => ['nullable', 'array'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
