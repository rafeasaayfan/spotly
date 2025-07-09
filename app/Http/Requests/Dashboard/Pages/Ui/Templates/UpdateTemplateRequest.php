<?php

namespace App\Http\Requests\Dashboard\Pages\Ui\Templates;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_type_id' => ['required', 'exists:website_types,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('templates', 'name')->ignore($this->route('template')->id)],
            'description' => ['nullable', 'string', 'max:150'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
