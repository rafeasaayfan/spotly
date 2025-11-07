<?php

namespace App\Http\Requests\Dashboard\Pages\Websites;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'website_type_id' => ['required', 'integer', 'exists:website_types,id,is_active,1'],

            'name' => [
                'required',
                'string',
                'max:15',
                'min:3',
                'unique:websites,name',
                'regex:/^[A-Za-z0-9]+$/'
            ],
            'subdomain' => [
                'required',
                'string',
                'min:3',
                'max:15',
                'unique:websites,subdomain',
                'regex:/^(?!-)[a-z0-9-]+(?<!-)$/'
            ],

            'about_us' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $trimmed = trim($value);

                    if (!preg_match('/^[\p{Latin}\d\s.,;:!?"\'()-]+$/u', $trimmed)) {
                        $fail('The ' . str_replace('_', ' ', $attribute) . ' field must be in English.');
                    }

                    if (preg_match('/\s{2,}/', $trimmed)) {
                        $fail('The ' . str_replace('_', ' ', $attribute) . ' field contains too many spaces.');
                    }
                }
            ],
            'about_us_ar' => [
                'required',
                'string',
                'max:255',
                'min:120',
                function ($attribute, $value, $fail) {
                    $trimmed = trim($value);

                    if (!preg_match('/^[\p{Arabic}\d\s.,؛:!؟"\'()\-]+$/u', $trimmed)) {
                        $fail('The ' . str_replace('_', ' ', $attribute) . ' field must be in Arabic.');
                    }

                    if (preg_match('/\s{2,}/u', $trimmed)) {
                        $fail('The ' . str_replace('_', ' ', $attribute) . ' field contains too many spaces.');
                    }
                }
            ],

            'phone_number' => [
                'nullable',
                'regex:/^(?:\+961)?(03\d{6}|70\d{6}|71\d{6}|76\d{6}|78\d{6}|79\d{6}|81\d{6})$/'
            ],
            'email' => [
                Rule::requiredIf(function () {
                    $type = \App\Models\WebsiteType::find($this->website_type_id);
                    return $type && ($type->type === 'e-commerce' || $type->type === 'restaurant');
                }),
                'lowercase',
                'email:rfc,dns',
                'min:3',
                'unique:websites,email'
            ],

            'address' => ['nullable', 'string', 'max:255', 'min:3'],
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

            'instagram' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],

            'light_logo' => ['nullable', 'file', 'mimes:svg', 'max:2048', 'required_with:dark_logo'],
            'dark_logo' => ['nullable', 'file', 'mimes:svg', 'max:2048', 'required_with:light_logo'],

            'template_id' => ['required', 'exists:templates,id'],

            'template_color_id' => [
                Rule::when(
                    $this->input('custom_template_color'),
                    ['nullable'],
                    ['required', 'exists:template_colors,id']
                )
            ],

            'custom_template_color' => [
                Rule::when(
                    $this->input('template_color_id') > 0,
                    ['nullable'],
                    ['required', 'boolean']
                )
            ],

            'colors' => [
                Rule::when(
                    $this->input('custom_template_color'),
                    ['required', 'array'],
                    ['nullable']
                )
            ],

            'template_images' => [
                Rule::when(
                    $this->input('custom_template_color'),
                    ['nullable'],
                    ['required', 'array']
                )
            ],

            'template_images.*' => ['string', 'url'],

            'language' => ['required', 'string', 'in:en,ar,fr'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
