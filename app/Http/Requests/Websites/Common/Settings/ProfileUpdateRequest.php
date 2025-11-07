<?php

namespace App\Http\Requests\Websites\Common\Settings;

use App\Models\WebsiteUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email:rfc,dns',
                'max:255',
                Rule::unique(WebsiteUser::class)->ignore($this->user('website')->id),
            ],
            'phone_number' => [
                'required',
                'regex:/^(?:\+961)?(03\d{6}|70\d{6}|71\d{6}|76\d{6}|78\d{6}|79\d{6}|81\d{6})$/',
                Rule::unique(WebsiteUser::class)->ignore($this->user('website')->id)
            ],
        ];
    }
}
