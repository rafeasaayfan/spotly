<?php

namespace App\Http\Requests\Dashboard\Pages\WebsiteTypes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsiteTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:30', Rule::unique('website_types', 'type')->ignore($this->websiteType->id)],
            'description' => ['required', 'string', 'max:100'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
