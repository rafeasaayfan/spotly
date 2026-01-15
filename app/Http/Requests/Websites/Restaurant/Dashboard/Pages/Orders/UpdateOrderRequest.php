<?php

namespace App\Http\Requests\Websites\Restaurant\Dashboard\Pages\Orders;

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

            'phone_number' => [
                'required',
                'string',
                'max:255',
                'regex:/^(?:\+961)?(03\d{6}|70\d{6}|71\d{6}|76\d{6}|78\d{6}|79\d{6}|81\d{6})$/',
            ],

            'note' => ['nullable', 'min:3', 'max:255'],
            
            'status_reason' => ['nullable', 'min:3', 'max:255'],
        ];
    }
}
