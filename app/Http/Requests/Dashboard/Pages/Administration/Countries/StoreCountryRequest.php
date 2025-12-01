<?php

namespace App\Http\Requests\Dashboard\Pages\Administration\Countries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'flag' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:600'],
            'country' => ['required', 'string', 'max:255', 'unique:countries,country'],
            'country_ar' => ['required', 'string', 'max:255', 'unique:countries,country_ar'],
            'country_fr' => ['required', 'string', 'max:255', 'unique:countries,country_fr'],
            'code' => ['required', 'string', 'max:255', 'unique:countries,code'],
            'phone_code' => ['required', 'string', 'max:255', 'regex:/^\+\d{1,4}$/', 'unique:countries,phone_code'],
            'region' => ['required', 'in:africa,asia,europe,north_america,south_america,australia,antarctica,middle_east,oceania,other'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
