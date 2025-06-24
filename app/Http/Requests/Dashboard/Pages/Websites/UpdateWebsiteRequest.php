<?php

namespace App\Http\Requests\Dashboard\Pages\Websites;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'owner_id' => ['required', 'exists:users,id'],
            'website_type_id' => ['required', 'exists:website_types,id'],
            'name' => ['required', 'max:15', Rule::unique('websites', 'name')->ignore($this->website->id)],
            'subdomain' => ['required', 'max:15', Rule::unique('websites', 'subdomain')->ignore($this->website->id)],
            'logo' => request()->hasFile('logo')
                ? 'required|file|mimes:svg|max:2048'
                : 'required|string',
            'description' => ['required', 'max:255'],
            'country' => 'nullable',
            'city' => 'nullable',
            'address' => ['required'],
            'phone_number' => ['required'],
            'instagram' => ['required'],
            'facebook' => ['required'],
            'tiktok' => ['required'],
            'language' => ['required'],
            'is_active' => ['required'],
        ];
    }
}
