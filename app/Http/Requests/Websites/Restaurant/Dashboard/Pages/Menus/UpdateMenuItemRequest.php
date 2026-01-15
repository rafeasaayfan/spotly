<?php

namespace App\Http\Requests\Websites\Restaurant\Dashboard\Pages\Menus;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $websiteId = app('website')->id;

        $baseRules = [
            'restaurant_item_images' => ['required', 'array', 'max:3'],
            'restaurant_item_images.*.id' => [
                'nullable',
                'integer',
            ],
            'restaurant_item_images.*.file' => [
                'sometimes',
                'required',
                'file',
                'mimes:jpeg,png,jpg,gif,svg,webp',
                'max:600'
            ],

            'category_id' => [
                'nullable',
                'string',
                'exists:categories,id,website_id,' . $websiteId . ',is_active,1',
            ],

            'name' => [
                'required',
                'string',
                'min:2',
                'max:90',
                // Rule::unique('restaurant_menu_items', 'name')
                //     ->where('website_id', $websiteId),
            ],

            'price' => ['required', 'numeric', 'gt:0'],
            'discount_price' => ['nullable', 'numeric', 'gt:0', 'lte:price', 'required_if:is_discount,1'],

            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],

            'is_active' => ['required', 'boolean'],
            'is_discount' => ['nullable', 'boolean']
        ];

        if ((int) $this->input('step') === 1) {
            return $baseRules;
        }

        return array_merge($baseRules, [
            'group_options' => ['nullable', 'array'],

            'group_options.*.group_id' => [
                'required',
                'integer',
                'exists:restaurant_menu_item_groups,id',
            ],

            'group_options.*.options' => ['nullable', 'array'],

            // 'group_options.*.options.*.option_id' => [
            //     'required',
            //     'integer',
            //     'exists:restaurant_menu_item_options,id',
            // ],

            // name & name_ar required IF options exist
            'group_options.*.options.*.name' => [
                'required_with:group_options.*.options',
                'string',
                'max:90',
            ],

            'group_options.*.options.*.name_ar' => [
                'required_with:group_options.*.options',
                'string',
                'max:90',
            ],

            // price_delta logic
            'group_options.*.options.*.price_delta' => [
                'nullable',
                'numeric',
                'gt:0',
                ($this->input('discount_price') > 0 && $this->input('is_discount')) ?
                    'lt:discount_price' : 'lt:price'
            ],

            'group_options.*.options.*.is_increase' => [
                'nullable',
                'required_with:group_options.*.options.*.price_delta',
                'boolean',
            ],

            'group_options.*.options.*.option_explain' => [
                'nullable',
                'string',
                'max:150',
            ],

            'group_options.*.options.*.is_active' => [
                'required',
                'boolean',
            ],
        ]);
    }

    public function withValidator($validator)
    {
        if ((int) $this->input('step') === 2) {
            $validator->after(function ($validator) {
                $groups = $this->input('group_options', []);

                foreach ($groups as $group) {
                    if (empty($group['options'])) {
                        continue;
                    }

                    $names = [];
                    $namesAr = [];

                    foreach ($group['options'] as $option) {
                        if (isset($option['name'])) {
                            if (in_array($option['name'], $names)) {
                                $validator->errors()->add(
                                    'group_options',
                                    'Option names must be unique within the same group.'
                                );
                            }
                            $names[] = $option['name'];
                        }

                        if (isset($option['name_ar'])) {
                            if (in_array($option['name_ar'], $namesAr)) {
                                $validator->errors()->add(
                                    'group_options',
                                    'Arabic option names must be unique within the same group.'
                                );
                            }
                            $namesAr[] = $option['name_ar'];
                        }
                    }
                }
            });
        }
    }

    public function messages(): array
    {
        return [
            'restaurant_item_images.*.file.file' => 'Each image must be a valid file.',
            'restaurant_item_images.*.file.mimes' => 'Images must be jpeg, png, jpg, gif, svg, or webp.',
            'restaurant_item_images.*.file.max' => 'Each image must not exceed 600 KB.',
            'discount_price.required_if' => 'The discount price is required when is discounted.',

            'group_options.*.options.*.name.required_with' => 'Option name is required.',

            'group_options.*.options.*.name_ar.required_with' => 'Arabic option name is required.',

            'group_options.*.options.*.is_increase.required_with' => 'Increase or decrease must be selected.',

            'group_options.*.options.*.price_delta.lt' => 'The option price must be less than the item price or discount price.',
            'group_options.*.options.*.price_delta.gt' => 'The option price must be greater than 0.',
            'group_options.*.options.*.price_delta.numeric' => 'The option price must be a number.',
        ];
    }
}
