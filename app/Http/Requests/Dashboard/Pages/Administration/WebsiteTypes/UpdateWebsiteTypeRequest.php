<?php

namespace App\Http\Requests\Dashboard\Pages\Administration\WebsiteTypes;

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
            'title' => ['required', 'string', 'max:30', Rule::unique('website_types', 'title')->ignore($this->route('websiteType')->id)],
            'type' => ['required', 'string', 'max:30', Rule::unique('website_types', 'type')->ignore($this->route('websiteType')->id)],
            'description' => ['required', 'string', 'max:100'],
            'priority' => ['required', 'integer', 'min:0', 'max:100'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
