<?php

namespace App\Http\Requests\Dashboard\Pages\Ui\Templates;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_type_id' => ['required', 'exists:website_types,id'],
            'name' => ['required', 'string', 'max:255', 'unique:templates,name'],
            'description' => ['nullable', 'string', 'max:150'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
