<?php

namespace App\Http\Requests\Dashboard\Pages\Ui\Templates;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTemplateRequest extends FormRequest
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
                'max:255',
                Rule::unique('templates', 'name')
            ],
            'description' => ['nullable', 'string', 'max:150'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
