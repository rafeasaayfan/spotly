<?php

namespace App\Http\Requests\Dashboard\Pages\Websites;

use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'owner_id' => ['required', 'integer', 'exists:users,id'],
            'website_type_id' => ['required', 'integer', 'exists:website_types,id'],
            'name' => ['required', 'max:15', 'unique:websites,name', 'string'],
            'subdomain' => ['required', 'max:15', 'unique:websites,subdomain', 'string'],
            'logo_light' => ['nullable', 'file', 'mimes:svg', 'max:2048'],
            'logo_dark' => ['nullable', 'file', 'mimes:svg', 'max:2048'],
            'phone_number' => ['required', 'string', 'max:255', 'regex:/^\+[1-9]\d{1,14}$/', 'unique:websites,phone_number'],
            'email' => ['nullable', 'email', 'string'],
            'about_us' => ['nullable', 'max:255', 'string'],
            'city' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'instagram' => ['nullable', 'string'],
            'facebook' => ['nullable', 'string'],
            'tiktok' => ['nullable', 'string'],
            'youtube' => ['nullable', 'string'],
            'language' => ['required', 'string', 'in:en,ar,fr'],
            'is_active' => ['required', 'boolean'],
            'is_verified' => ['required', 'boolean'],
            'status' => ['required', 'string', 'in:pending,denied,approved'],
        ];
    }
}
