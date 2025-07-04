<?php

namespace App\Http\Requests\Dashboard\Pages\WebsiteTypes;

use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:30', 'unique:website_types,title'],
            'type' => ['required', 'string', 'max:30', 'unique:website_types,type'],
            'description' => ['required', 'string', 'max:100'],
            'priority' => ['required', 'integer', 'min:0', 'max:100'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
