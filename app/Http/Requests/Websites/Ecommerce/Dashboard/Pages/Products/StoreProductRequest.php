<?php

namespace App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $website = app('website');

        return [
            'variants' => ['required', 'array'],
            'variants.*.color_id' => ['nullable', 'exists:colors,id'],
            'variants.*.stock_quantity' => ['required', 'integer', 'min:0'],
            'variants.*.ecommerce_product_image' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],

            'category_id' => [
                'nullable',
                'exists:categories,id,website_id,' . $website->id . ',is_active,1'
            ],
            'brand_id' => [
                'nullable',
                'exists:brands,id,website_id,' . $website->id . ',is_active,1'
            ],

            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'discount_price'  => ['nullable', 'numeric', 'lt:price', 'gt:0'],
            
            'is_active'   => ['required', 'boolean'],

            'short_description'  => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $colors = [];

            foreach ($this->input('variants', []) as $index => $variant) {
                $colorId = $variant['color_id'] ?? null;

                if (!$colorId) continue;

                // duplicate
                if (in_array($colorId, $colors)) {
                    $validator->errors()->add("variants.$index.color_id", "Duplicate color in request.");
                }

                $colors[] = $colorId;
            }
        });
    }
}
