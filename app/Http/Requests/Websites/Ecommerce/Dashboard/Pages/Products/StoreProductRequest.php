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
            'variants.*.color' => ['required', 'string', 'max:50'],
            'variants.*.stock_quantity' => ['required', 'integer', 'min:0'],
            'variants.*.ecommerce_product_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],

            'category_id' => [
                'required',
                'exists:categories,id,website_id,' . $website->id . ',is_active,1'
            ],
            'brand_id' => [
                'required',
                'exists:brands,id,website_id,' . $website->id . ',is_active,1'
            ],

            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'sale_price'  => ['nullable', 'numeric', 'lt:price'],

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
                $color = $variant['color'] ?? null;

                if (!$color) continue;

                // duplicate
                if (in_array($color, $colors)) {
                    $validator->errors()->add("variants.$index.color", "Duplicate color in request.");
                }

                $colors[] = $color;
            }
        });
    }
}
