<?php

namespace App\Http\Requests\WebsiteBuilder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $step = $this->route('step');

        $validation = [];

        switch ($step) {
            case '1':
                $validation = [
                    'website_type_id' => ['required', 'exists:website_types,id,is_active,1'],
                    'language' => ['required', 'string', 'in:en,ar'],
                    'name' => [
                        'required',
                        'string',
                        'max:15',
                        'min:3',
                        'unique:websites,name',
                        'regex:/^(?=(?:.*[A-Za-z]){2,})[A-Za-z0-9 ]+$/',
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
                        'required_with:about_us_ar',
                        'string',
                        'max:255',
                        function ($attribute, $value, $fail) {
                            $trimmed = trim($value);

                            if (!preg_match('/^[\p{Latin}\d\s.,;:!?"\'()-]+$/u', $trimmed)) {
                                $fail('The ' . str_replace('_', ' ', $attribute) . ' field must be in English.');
                            }
                        }
                    ],
                    'about_us_ar' => [
                        'nullable',
                        'required_with:about_us',
                        'string',
                        'max:255',
                        function ($attribute, $value, $fail) {
                            $trimmed = trim($value);

                            if (!preg_match('/^[\p{Arabic}\d\s.,؛:!؟"\'()\-]+$/u', $trimmed)) {
                                $fail('The ' . str_replace('_', ' ', $attribute) . ' field must be in Arabic.');
                            }
                        }
                    ],
                ];
                break;

            case '2':
                $validation = [
                    'phone_number' => [
                        'required',
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
                    'instagram' => ['nullable', 'url', 'max:255'],
                    'facebook' => ['nullable', 'url', 'max:255'],
                    'tiktok' => ['nullable', 'url', 'max:255'],
                    'youtube' => ['nullable', 'url', 'max:255'],
                ];
                break;

            case 3:
                $validation = [
                    'light_logo' => ['nullable', 'file', 'mimes:svg,png,jpg,jpeg,webp', 'max:600', 'required_with:dark_logo'],
                    'dark_logo' => ['nullable', 'file', 'mimes:svg,png,jpg,jpeg,webp', 'max:600', 'required_with:light_logo'],

                    'template_images' => [
                        Rule::when(
                            $this->input('custom_template_color'),
                            ['nullable'],
                            ['required', 'array']
                        )
                    ],
                    'template_images.*' => ['string', 'url'],

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
                            ['required', 'boolean'],
                        )
                    ],
                    'colors' => [
                        Rule::when(
                            $this->input('custom_template_color'),
                            ['required', 'array'],
                            ['nullable']
                        )
                    ],
                ];
                break;

            case '4':
                $validation = [
                    'acceptSteps' => ['required', 'boolean']
                ];
                break;
        };

        return $validation;
    }

    protected function passedValidation()
    {
        $step = $this->route('step');
        $data = $this->validated();

        if ($step === '3') {
            if ($this->hasFile('light_logo') && $this->hasFile('dark_logo')) {
                $data['light_logo'] = $this->file('light_logo')->store('temp');
                $data['dark_logo'] = $this->file('dark_logo')->store('temp');
            }
        }

        session(["wizard_step_{$step}" => $data]);

        // Validator::make($allData, [ ... ])->validate();
    }

    public function messages()
    {
        return [
            'name.regex' => __('messages.websiteBuilder.firstStep.business_name_validation'),
            'subdomain.regex' => __('messages.websiteBuilder.firstStep.subdomain_validation'),
        ];
    }
}
