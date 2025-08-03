<?php

namespace App\Http\Requests\WebsiteBuilder;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteBuilderRequest extends FormRequest
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

        $validation = [];

        switch ($this->route('step')) {
            case '1':
                $validation = [
                    'website_type_id' => ['required', 'exists:website_types,id'],
                    'language' => ['required', 'string', 'in:en,ar,fr'],
                    'name' => ['required', 'string', 'max:15', 'min:3', 'unique:websites,name'],
                    'subdomain' => ['required', 'string', 'max:15', 'min:3', 'unique:websites,subdomain'],
                    'about_us' => ['required', 'string', 'max:255', 'min:30'],
                ];
                break;

            case '2':
                $validation = [
                    'phone_number' => [
                        'required',
                        'unique:websites,phone_number',
                        'regex:/^(?:\+961|961|0)?((03\d{6})|(71\d{6})|(78\d{6})|(76\d{6})|(01\d{6})|(70\d{6}))$/'
                    ],
                    'email' => ['nullable', 'string', 'min:3', 'unique:websites,email'],
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
                    'instagram' => ['nullable', 'string', 'max:255'],
                    'facebook' => ['nullable', 'string', 'max:255'],
                    'tiktok' => ['nullable', 'string', 'max:255'],
                    'youtube' => ['nullable', 'string', 'max:255'],
                ];
                break;

            case 3: 
                $validation = [
                    'lightLogo' => ['nullable', 'file', 'mimes:svg', 'max:2048'],
                    'darkLogo' => ['nullable', 'file', 'mimes:svg', 'max:2048'],

                    'template_id' => ['required', 'exists:templates,id'],
                    'template_color_id' => [
                        function ($attribute, $value, $fail) {
                            if ($this->input('custom_template_color')) {
                                return;
                            }
                            if (empty($value)) {
                                $fail('Please choose a template colors');
                            }
                        },
                        'exists:template_colors,id'
                    ],
                    'custom_template_color' => ['nullable', 'boolean'],
                    'colors' => ['nullable', 'array'],
                ];
                break;

            case '4':
                $validation = [
                    'user_phone_number' => ['required', 'string'],
                    'user_email' => ['required', 'email', 'unique:users,email'],
                    'plan_id' => ['required', 'exists:plans,id'],
                    'payment_method_id' => ['required', 'exists:payment_methods,id'],
                ];
                break;
        };

        return $validation;
    }
}
