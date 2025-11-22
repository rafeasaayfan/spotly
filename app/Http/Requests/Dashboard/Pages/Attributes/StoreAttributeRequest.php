<?php

namespace App\Http\Requests\Dashboard\Pages\Attributes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_id' => ['required', 'integer', 'exists:websites,id'],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('ecommerce_product_attributes', 'name')->where('website_id', $this->website_id),
                Rule::in(array_column(config('ecommerce_attributes.attributes'), 'value'))
            ],

            'values' => ['nullable', 'array'],
            'values.*' => ['required', 'string'],

            'description' => ['nullable', 'string', 'max:200'],
            'is_required' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
