<?php

namespace App\Http\Requests\Dashboard\Pages\Attributes;

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
            'website_id' => ['required', 'integer', 'exists:websites,id'],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('attributes', 'name')->where('website_id', $this->route('website')->id)
                ->ignore($this->route('attribute')->id),
                Rule::in(array_column(config('attributes.ecommerce_attributes'), 'value'))
            ],
            'name_ar' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('attributes', 'name_ar')->where('website_id', $this->route('website')->id)
                ->ignore($this->route('attribute')->id),
                Rule::in(array_column(config('attributes.ecommerce_attributes'), 'value_ar'))
            ],

            'values' => ['nullable', 'array'],
            'values.*.id' => [
                'sometimes', 
                'integer', 
                'exists:attribute_values,id'
            ],
            'values.*.value' => [
                'required', 
                'string', 
                'max:50',
            ],
            'values.*.value_ar' => [
                'required', 
                'string', 
                'max:50',
            ],

            'description' => ['nullable', 'string', 'max:200'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            // Values array validation messages
            'values.*.value.required' => 'Each attribute value is required.',
            'values.*.value.string' => 'Each attribute value must be a valid string.',
            'values.*.value.max' => 'Each attribute value must not exceed :max characters.',

            // Arabic values validation messages
            'values.*.value_ar.required' => 'Each Arabic attribute value is required.',
            'values.*.value_ar.string' => 'Each Arabic attribute value must be a valid string.',
            'values.*.value_ar.max' => 'Each Arabic attribute value must not exceed :max characters.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $values = $this->input('values', []);
            
            // Check for duplicate values within the same request
            $valueCounts = [];
            $valueArCounts = [];
            
            foreach ($values as $index => $value) {
                $val = $value['value'] ?? null;
                $valAr = $value['value_ar'] ?? null;
                
                if ($val) {
                    if (isset($valueCounts[$val])) {
                        $validator->errors()->add(
                            "values.$index.value",
                            "The value '{$val}' is duplicated. Each value must be unique."
                        );
                    }
                    $valueCounts[$val] = true;
                }
                
                if ($valAr) {
                    if (isset($valueArCounts[$valAr])) {
                        $validator->errors()->add(
                            "values.$index.value_ar",
                            "The Arabic value '{$valAr}' is duplicated. Each value must be unique."
                        );
                    }
                    $valueArCounts[$valAr] = true;
                }
            }
        });
    }
}
