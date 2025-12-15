<?php

namespace App\Http\Requests\Websites\Ecommerce;

use App\Enums\Websites\Ecommerce\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', Rule::enum(OrderStatus::class)],
            'search' => ['nullable', 'string', 'min:0', 'max:100'],
            'sort_by' => ['nullable', 'string', 'in:date,amount'],
            'sort_dir' => ['nullable', 'string', 'in:asc,desc'],
        ];
    }
}
