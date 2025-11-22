<?php

namespace App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Attributes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeRequest extends FormRequest
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
                'max:50',
                Rule::unique('ecommerce_product_attributes', 'name')
                    ->ignore($this->route('attribute')->id),
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
