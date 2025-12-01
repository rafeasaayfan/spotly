<?php

namespace App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Attributes;

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
        $website = app('website');

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('ecommerce_product_attributes', 'name')->where('website_id', $website->id),
                Rule::in(array_column(config('ecommerce_attributes.attributes'), 'value'))
            ],
            'name_ar' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('ecommerce_product_attributes', 'name_ar')->where('website_id', $website->id),
                Rule::in(array_column(config('ecommerce_attributes.attributes'), 'value_ar'))
            ],

            'values' => ['nullable', 'array'],
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
