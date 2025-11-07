<?php

namespace App\Http\Requests\Dashboard\Pages\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'lowercase', 'email:rfc,dns', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'phone_number' => ['nullable', 'string', 'max:255',              
            'regex:/^(?:\+961)?(03\d{6}|70\d{6}|71\d{6}|76\d{6}|78\d{6}|79\d{6}|81\d{6})$/'
        ],
            'status' => ['required', 'string', 'in:active,inactive,banned'],
        ];
    }
}
