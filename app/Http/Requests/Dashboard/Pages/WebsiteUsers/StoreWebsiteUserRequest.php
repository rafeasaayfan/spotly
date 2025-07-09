<?php

namespace App\Http\Requests\Dashboard\Pages\WebsiteUsers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWebsiteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_id' => ['required', 'exists:websites,id'],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:website_users,email',
                Rule::unique('website_users', 'email')->where('website_id', $this->input('website_id'))
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('website_users', 'phone_number')->where('website_id', $this->input('website_id'))
            ],
            'status' => ['required', 'in:active,inactive,banned'],
            'email_verified_at' => ['nullable', 'date'],
        ];
    }
}
