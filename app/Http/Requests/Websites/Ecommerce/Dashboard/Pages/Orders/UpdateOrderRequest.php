<?php

namespace App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Orders;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'delivery_address' => ['required', 'min:3', 'max:80'],
            'city' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $cities = config('cities.lebanon');
                    if (!array_key_exists($value, $cities)) {
                        $fail('The selected city is invalid.');
                    }
                }
            ],

            'note' => ['nullable', 'min:3', 'max:255'],
            
            'cancellation_reason' => ['nullable', 'min:3', 'max:255'],
        ];
    }
}
