<?php

namespace App\Http\Requests\Websites\Ecommerce;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'exists:ecommerce_cart_items,id'],

            'phone_number' => [
                'required',
                'string',
                'max:255',
                'regex:/^(?:\+961)?(3\d{6}|70\d{6}|71\d{6}|76\d{6}|78\d{6}|79\d{6}|81\d{6})$/'
            ],
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
            'delivery_address' => ['required', 'string', 'min:4', 'max:255'],
            'note' => ['nullable', 'string', 'min:5', 'max:255'],
        ];
    }
}
