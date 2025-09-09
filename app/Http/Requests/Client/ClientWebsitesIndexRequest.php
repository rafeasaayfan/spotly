<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class ClientWebsitesIndexRequest extends FormRequest
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
            'sort_by' => ['nullable', 'string', 'in:newest,oldest,name_asc,name_desc'],
            'website_type' => ['nullable', 'exists:website_types,id'],
            'status' => ['nullable', 'string', 'in:pending,denied,approved'],
            'is_active' => ['nullable', 'boolean'],
            'limit' => ['nullable', 'in:3,6,9,12'],
            'page' => ['nullable', 'integer', 'min:1']
        ];
    }
}
