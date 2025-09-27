<?php

namespace App\Http\Requests\Dashboard\Pages\Subscriptions\PaymentMethods;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:30', 'unique:payment_methods,name'],
            'code' => ['required', 'string', 'min:2', 'max:20', 'unique:payment_methods,code'],
            'description' => ['nullable', 'string', 'max:150'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
