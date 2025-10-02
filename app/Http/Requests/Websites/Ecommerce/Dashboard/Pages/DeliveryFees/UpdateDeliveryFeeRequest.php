<?php

namespace App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\DeliveryFees;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryFeeRequest extends FormRequest
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
                    if (!is_array($cities) || !in_array($value, $cities)) {
                        $fail('The selected city is invalid.');
                    }
                }
            ],
            'amount' => ['required', 'numeric', 'min:0']
        ];
    }
}
