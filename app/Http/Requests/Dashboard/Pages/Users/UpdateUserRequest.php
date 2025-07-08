<?php

namespace App\Http\Requests\Dashboard\Pages\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->route('user')->id)],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'phone_number' => ['required', 'string', 'max:255', 'regex:/^\+[1-9]\d{1,14}$/'],
        ];
    }
}
