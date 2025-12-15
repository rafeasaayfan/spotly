<?php

namespace App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Products;

use App\Models\Color;
use App\Models\EcommerceProductAttributeValue;
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
            'discount_price'  => ['nullable', 'numeric', 'lte:price', 'gt:0'],

            'is_active'   => ['required', 'boolean'],
            
            'variants' => ['required', 'array'],
            'variants.*.id' => ['nullable', 'integer', 'exists:ecommerce_product_variants,id'],
            'variants.*.price' => [
                'nullable', 
                'numeric', 
                'min:0',
                when($this->input('discount_price'), 'gte:discount_price')
            ],
            'variants.*.stock_quantity' => ['nullable', 'integer', 'min:0'],
            'variants.*.ecommerce_product_images' => ['required', 'array', 'max:4'],
            'variants.*.ecommerce_product_images.*.id' => [
                'nullable',
                'integer',
            ],
            'variants.*.ecommerce_product_images.*.file' => [
                'sometimes',
                'required',
                'file',
                'mimes:jpeg,png,jpg,gif,svg,webp',
                'max:600'
            ],

            'variants.*.attributes' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    if (!is_array($value) || empty($value)) {
                        return $fail("At least one attribute must be provided.");
                    }

                    // Check if at least one attribute has a non-null value
                    $hasValidValue = false;
                    foreach ($value as $attr) {
                        if (isset($attr['value']) && $attr['value'] !== null && $attr['value'] !== '') {
                            $hasValidValue = true;
                            break;
                        }
                    }

                    if (!$hasValidValue) {
                        return $fail("At least one attribute must have a non-null value.");
                    }
                }
            ],
            'variants.*.attributes.*.id' => [
                'nullable',
                'integer',
                'max:255',
                Rule::exists('ecommerce_product_attributes', 'id')->where('website_id', $website->id),
            ],
            'variants.*.attributes.*.name' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('ecommerce_product_attributes', 'name')->where('website_id', $website->id)
            ],
            'variants.*.attributes.*.type' => ['nullable', 'in:text,select'],
            'variants.*.attributes.*.value' => [
                'nullable',
                'max:255',
                function ($attribute, $value, $fail) {
                    $attributeData = data_get($this->all(), str_replace('.value', '', $attribute));

                    if (!$attributeData) return;

                    if (($attributeData['name'] ?? null) === 'color') {
                        // check in colors
                        if (!Color::where('id', $value)->exists()) {
                            return $fail("Invalid color value.");
                        }
                    } elseif (is_int($value)) {
                        // check in ecommerce_product_attribute_values
                        if (!EcommerceProductAttributeValue::where('id', $value)
                            ->where('attribute_id', $attributeData['id'] ?? null)
                            ->exists()) {
                            return $fail("Invalid attribute value.");
                        }
                    }
                }
            ],

            'short_description'  => ['nullable', 'string', 'max:255'],
            'description'        => ['nullable', 'string', 'max:500'],
        ];
    }

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         $colors = [];
    //         $variants = $this->input('variants', []);
    //         $requestVariantIds = collect($variants)->pluck('id')->filter()->all();

    //         foreach ($variants as $index => $variant) {
    //             $colorId = $variant['color_id'] ?? null;
    //             $id    = $variant['id'] ?? null;

    //             if (!$colorId) continue;

    //             // duplicate
    //             if (in_array($colorId, $colors)) {
    //                 $validator->errors()->add("variants.$index.color_id", "Duplicate color!");
    //             }

    //             $query = EcommerceProductVariant::where('product_id', $this->route('product')->id)
    //                 ->whereIn('id', $requestVariantIds)
    //                 ->where('color_id', $colorId);

    //             if ($id) {
    //                 $query->where('id', '!=', $id); // ignore 
    //             }

    //             if ($query->exists()) {
    //                 $validator->errors()->add("variants.$index.color_id", "color $index already exists for this product.");
    //             }

    //             $colors[] = $colorId;
    //         }
    //     });
    // }
}
