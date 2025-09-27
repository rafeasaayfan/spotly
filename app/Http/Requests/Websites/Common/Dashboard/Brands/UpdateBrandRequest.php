<?php

namespace App\Http\Requests\Websites\Common\Dashboard\Brands;

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
        $website = app('website');

        return [
            'name' => ['required', 'string', 'max:30', 'min:3', Rule::unique('brands', 'name')->where(function ($query) use ($website) {
                return $query->where('website_id', $website->id);
            })->ignore($this->route('brand')->id)],
            
            'description' => ['nullable', 'string', 'max:255', 'min:3'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
