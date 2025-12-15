<?php

namespace App\Http\Requests\Dashboard\Pages\Subscriptions\Plans;

use App\Enums\Spotly\PlanCurrency;
use App\Enums\Spotly\PlanDuration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'currency' => ['required', 'string', Rule::enum(PlanCurrency::class)],
            'duration' => ['required', 'string', Rule::enum(PlanDuration::class)],
            'features' => ['required', 'array'],
            'features.*' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
