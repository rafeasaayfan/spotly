<?php

namespace App\Http\Requests\Dashboard\Pages\Administration\Colors;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:colors,name,' . $this->route('color')->id],
            'ar_name' => ['required', 'string', 'max:255', 'unique:colors,ar_name,' . $this->route('color')->id],
            'code' => ['required', 'string', 'max:255', 'unique:colors,code,' . $this->route('color')->id],
        ];
    }
}
