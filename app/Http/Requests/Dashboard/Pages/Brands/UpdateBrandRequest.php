<?php

namespace App\Http\Requests\Dashboard\Pages\Brands;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_id' => ['required', 'exists:websites,id'],
            'name' => ['required', 'string', 'max:20', 'min:3', Rule::unique('brands', 'name')->where(function ($query) {
                return $query->where('website_id', $this->website_id);
            })->ignore($this->brand->id)],
            'description' => ['nullable', 'string', 'max:255', 'min:3'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
