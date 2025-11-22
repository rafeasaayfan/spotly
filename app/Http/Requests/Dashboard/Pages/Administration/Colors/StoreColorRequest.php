<?php

namespace App\Http\Requests\Dashboard\Pages\Administration\Colors;

use Illuminate\Foundation\Http\FormRequest;

class StoreColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:colors,name'],
            'ar_name' => ['required', 'string', 'max:255', 'unique:colors,ar_name'],
            'code' => ['required', 'string', 'max:255', 'unique:colors,code'],
        ];
    }
}
