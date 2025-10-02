<?php

namespace App\Http\Requests\Websites\Common\Dashboard\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $website = app('website');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('website_users', 'email')->where('website_id', $website->id)
            ],
            'password' => ['required', 'min:8'],
            'phone_number' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^(?:\+961)?(03\d{6}|70\d{6}|71\d{6}|76\d{6}|78\d{6}|79\d{6}|81\d{6})$/',
                Rule::unique('website_users', 'phone_number')->where('website_id', $website->id)
            ],
            'status' => ['required', 'string', 'in:active,inactive,banned'],
            'role' => ['required', 'string', 'in:user,admin'],
        ];
    }
}
