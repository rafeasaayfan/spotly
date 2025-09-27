<?php

namespace App\Http\Requests\Dashboard\Pages\Subscriptions\Plans;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:30', 'unique:plans,name'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'in:USD,LBP'],
            'duration' => ['required', 'string', 'in:monthly,yearly'],
            'features' => ['required', 'array'],
            'features.*' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
