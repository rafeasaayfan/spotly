<?php

namespace App\Http\Requests\Dashboard\Pages\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Spotly\UserStatus;

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
            'email' => ['required', 'lowercase', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($this->route('user')->id)],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'phone_number' => ['nullable', 'string', 'max:255',              
                'regex:/^(?:\+961)?(3\d{6}|70\d{6}|71\d{6}|76\d{6}|78\d{6}|79\d{6}|81\d{6})$/'
            ],
            'status' => ['required', 'string', Rule::enum(UserStatus::class)],
        ];
    }
}
