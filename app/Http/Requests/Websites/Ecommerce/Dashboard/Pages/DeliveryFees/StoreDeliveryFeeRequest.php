<?php

namespace App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\DeliveryFees;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
            'amount' => ['required', 'numeric', 'min:0']
        ];
    }
}
