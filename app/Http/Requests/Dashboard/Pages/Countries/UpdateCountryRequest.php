<?php

namespace App\Http\Requests\Dashboard\Pages\Countries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'flag' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'country' => [
                'required',
                'string',
                'max:255',
                Rule::unique('countries', 'country')->ignore($this->route('country')->id)
            ],
            'country_ar' => [
                'required',
                'string',
                'max:255',
                Rule::unique('countries', 'country_ar')->ignore($this->route('country')->id)
            ],
            'country_fr' => [
                'required',
                'string',
                'max:255',
                Rule::unique('countries', 'country_fr')->ignore($this->route('country')->id)
            ],
            'code' => ['required', 'string', 'max:255', Rule::unique('countries', 'code')->ignore($this->route('country')->id)],
            'phone_code' => [
                'required',
                'string',
                'max:255',
                'regex:/^\+\d{1,4}$/',
                Rule::unique('countries', 'phone_code')->ignore($this->route('country')->id)
            ],
            'region' => [
                'required',
                'in:africa,asia,europe,north_america,south_america,australia,antarctica,middle_east,oceania,other'
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
