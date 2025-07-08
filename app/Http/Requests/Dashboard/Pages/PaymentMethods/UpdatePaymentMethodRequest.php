<?php

namespace App\Http\Requests\Dashboard\Pages\PaymentMethods;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                Rule::unique('payment_methods', 'name')->ignore($this->route('paymentMethod')->id)
            ],
            'code' => [
                'required',
                'string',
                'min:2',
                'max:20',
                Rule::unique('payment_methods', 'code')->ignore($this->route('paymentMethod')->id)
            ],
            'description' => ['nullable', 'string', 'max:150'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
