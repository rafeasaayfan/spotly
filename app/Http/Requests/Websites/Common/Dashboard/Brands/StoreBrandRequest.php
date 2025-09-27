<?php

namespace App\Http\Requests\Websites\Common\Dashboard\Brands;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends FormRequest
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
                'max:30',
                'min:3',
                Rule::unique('brands', 'name')->where('website_id', $website->id)
            ],
            'description' => ['nullable', 'string', 'max:255', 'min:3'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
