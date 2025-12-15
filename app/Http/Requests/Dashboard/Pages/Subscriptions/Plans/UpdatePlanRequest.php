<?php

namespace App\Http\Requests\Dashboard\Pages\Subscriptions\Plans;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Spotly\PlanCurrency;
use App\Enums\Spotly\PlanDuration;

class UpdatePlanRequest extends FormRequest
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
                'min:3',
                'max:30',
                Rule::unique('plans', 'name')->ignore($this->route('plan')->id)
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', Rule::enum(PlanCurrency::class)],
            'duration' => ['required', 'string', Rule::enum(PlanDuration::class)],
            'features' => ['required', 'array'],
            'features.*' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
