<?php

namespace App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Products;

use App\Models\EcommerceProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'variants.*.id' => ['nullable'],
            'variants.*.color_id' => ['nullable', 'exists:colors,id'],
            'variants.*.stock_quantity' => ['required', 'integer', 'min:0'],
            'variants.*.ecommerce_product_image' => [
                'required',
                when('string', '', ['file', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'])
            ],

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
            $variants = $this->input('variants', []);
            $requestVariantIds = collect($variants)->pluck('id')->filter()->all();

            foreach ($variants as $index => $variant) {
                $colorId = $variant['color_id'] ?? null;
                $id    = $variant['id'] ?? null;

                if (!$colorId) continue;

                // duplicate
                if (in_array($colorId, $colors)) {
                    $validator->errors()->add("variants.$index.color_id", "Duplicate color!");
                }

                $query = EcommerceProductVariant::where('product_id', $this->route('product')->id)
                    ->whereIn('id', $requestVariantIds)
                    ->where('color_id', $colorId);

                if ($id) {
                    $query->where('id', '!=', $id); // ignore 
                }

                if ($query->exists()) {
                    $validator->errors()->add("variants.$index.color_id", "color $index already exists for this product.");
                }

                $colors[] = $colorId;
            }
        });
    }
}
