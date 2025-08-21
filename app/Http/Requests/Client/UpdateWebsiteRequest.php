<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

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
            'light_logo' => ['nullable', 'file', 'mimes:svg', 'max:2048', 'required_with:dark_logo'],
            'dark_logo' => ['nullable', 'file', 'mimes:svg', 'max:2048', 'required_with:light_logo'],

            'about_us' => ['required', 'string', 'max:255', 'min:30'],
            'language' => ['required', 'string', 'in:en,ar,fr'],

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
                    if (!is_array($cities) || !in_array($value, $cities)) {
                        $fail('The selected city is invalid.');
                    }
                }
            ],

            'instagram' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
        ];
    }
}
