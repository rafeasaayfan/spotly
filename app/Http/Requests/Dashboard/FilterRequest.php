<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'max:100'],
            'filter' => ['nullable', 'array'],
            'sort_by' => ['nullable', 'string'],
            'sort_dir' => ['nullable', 'string', 'in:desc,asc'],
            'limit' => ['nullable', 'in:5,10,20,50,100'],
            'page' => ['nullable', 'integer', 'min:1']
        ];
    }
}
