<?php

namespace App\Http\Requests\Dashboard\Pages\Countries;

use Illuminate\Foundation\Http\FormRequest;

class StoreCountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country' => 'required',
            'country_ar' => 'required',
            'country_fr' => 'required',
            'code' => 'required',
            'phone_code' => 'required',
            'is_active' => 'required',
        ];
    }
}
