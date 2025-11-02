<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'light_logo' => [
                'nullable',
                'file',
                'mimes:svg',
                'max:2048',
                when(!$this->route('website')->hasMedia('dark_logo'), 'required_with:dark_logo')
            ],
            'dark_logo' => [
                'nullable',
                'file',
                'mimes:svg',
                'max:2048',
                when(!$this->route('website')->hasMedia('dark_logo'), 'required_with:light_logo')
            ],

            'phone_number' => [
                'required',
                'regex:/^(?:\+961)?(03\d{6}|70\d{6}|71\d{6}|76\d{6}|78\d{6}|79\d{6}|81\d{6})$/',
                Rule::unique('websites', 'phone_number')->ignore($this->route('website')->id)
            ],
            'email' => ['nullable', 'email', Rule::unique('websites', 'email')->ignore($this->route('website')->id)],

            'address' => ['nullable', 'string', 'max:15', 'min:3'],
            'country' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\Country::where('country', $value)
                        ->orWhere('country_ar', $value)
                        ->orWhere('country_fr', $value)
                        ->exists();
                    if (!$exists) {
                        $fail('The selected country is invalid.');
                    }
                }
            ],
            'city' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $cities = config('cities.lebanon');
                    if (!array_key_exists($value, $cities)) {
                        $fail('The selected city is invalid.');
                    }
                }
            ],
            'language' => ['required', 'string', 'in:en,ar,fr'],

            'instagram' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],

            'about_us' => ['required', 'string', 'max:255', 'min:30'],
        ];
    }
}
