<?php

namespace App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Products;

use App\Models\Color;
use App\Models\Attribute;
use App\Models\AttributeValue;
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

        $activeWebsiteAttributes = Attribute::where('website_id', $website->id)->active()->count();

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

            'variants' => [
                'required',
                'array',
                'min:1',
                $activeWebsiteAttributes > 0 ? null : 'max:1',
                function ($attribute, $value, $fail) {
                    $seenCombinations = [];

                    foreach ($value as $variantIndex => $variant) {
                        $attributes = $variant['attributes'] ?? [];

                        // Create a signature for this variant's attributes
                        $signature = [];

                        foreach ($attributes as $attr) {
                            if (isset($attr['value']) && $attr['value'] !== null && $attr['value'] !== '') {
                                // Use attribute name or id as key, and value as value
                                $key = $attr['name'] ?? $attr['id'] ?? '';
                                if ($key) {
                                    $signature[$key] = $attr['value'];
                                }
                            }
                        }

                        // Sort the signature to ensure consistent ordering
                        ksort($signature);
                        $signatureString = json_encode($signature);

                        // Check if this combination has been seen before
                        if (isset($seenCombinations[$signatureString])) {
                            $originalVariantNumber = $seenCombinations[$signatureString] + 1;
                            $currentVariantNumber = $variantIndex + 1;
                            return $fail("Variant {$currentVariantNumber} has the same attributes as Variant {$originalVariantNumber}. Variants must have unique attribute combinations.");
                        }

                        $seenCombinations[$signatureString] = $variantIndex;
                    }
                }
            ],
            'variants.*.price' => [
                'nullable',
                'numeric',
                'min:0',
                when($this->input('discount_price'), 'gte:discount_price')
            ],
            'variants.*.stock_quantity' => ['nullable', 'integer', 'min:0'],
            'variants.*.ecommerce_product_images' => ['required', 'array', 'max:4'],
            'variants.*.ecommerce_product_images.*.file' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg,gif,svg,webp',
                'max:600'
            ],

            'variants.*.attributes' => array_merge(
                array_filter([
                    $activeWebsiteAttributes > 0 ? 'required' : 'nullable',
                    'array',
                    $activeWebsiteAttributes > 0 ? 'min:1' : 'max:0',
                ]),
                $activeWebsiteAttributes > 0 ? [
                    function ($attribute, $value, $fail) use ($activeWebsiteAttributes) {
                        // Extract variant index from attribute name (e.g., "variants.0.attributes" -> 0)
                        preg_match('/variants\.(\d+)\.attributes/', $attribute, $matches);
                        $variantIndex = isset($matches[1]) ? (int)$matches[1] : null;
                        $variantNumber = $variantIndex !== null ? $variantIndex + 1 : '';
                        $variantPrefix = $variantNumber ? "Variant {$variantNumber}: " : '';

                        if (!is_array($value) || empty($value)) {
                            return $fail("{$variantPrefix}At least one attribute must be provided.");
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
                            return $fail("{$variantPrefix}At least one attribute must have a non-null value.");
                        }
                    }
                ] : []
            ),
            'variants.*.attributes.*.id' => [
                'nullable',
                'integer',
                'max:255',
                Rule::exists('attributes', 'id')->where('website_id', $website->id),
            ],
            'variants.*.attributes.*.name' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('attributes', 'name')->where('website_id', $website->id)
            ],
            'variants.*.attributes.*.type' => ['nullable', 'in:text,select'],
            'variants.*.attributes.*.value' => [
                'nullable',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Extract variant and attribute indices from attribute name
                    // e.g., "variants.0.attributes.1.value" -> variant 0, attribute 1
                    preg_match('/variants\.(\d+)\.attributes\.(\d+)\.value/', $attribute, $matches);
                    $variantIndex = isset($matches[1]) ? (int)$matches[1] : null;
                    $attrIndex = isset($matches[2]) ? (int)$matches[2] : null;
                    $variantNumber = $variantIndex !== null ? $variantIndex + 1 : '';
                    $attributeNumber = $attrIndex !== null ? $attrIndex + 1 : '';
                    $prefix = ($variantNumber && $attributeNumber) ? "Variant {$variantNumber}, Attribute {$attributeNumber}: " : '';

                    $attributeData = data_get($this->all(), str_replace('.value', '', $attribute));

                    if (!$attributeData) return;

                    if (($attributeData['name'] ?? null) === 'color') {
                        // check in colors
                        if (!Color::where('id', $value)->exists()) {
                            return $fail("{$prefix}Invalid color value.");
                        }
                    } elseif (is_int($value)) {
                        // check in attribute_values
                        if (!AttributeValue::where('id', $value)
                            ->where('attribute_id', $attributeData['id'] ?? null)
                            ->exists()) {
                            return $fail("{$prefix}Invalid attribute value.");
                        }
                    }
                }
            ],

            'short_description'  => ['nullable', 'string', 'max:255'],
            'description'        => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        $messages = [
            // Variants array validation
            'variants.required' => 'At least one variant is required.',
            'variants.array' => 'Variants must be an array.',
        ];

        // Get variants to create dynamic messages with variant numbers
        $variants = $this->input('variants', []);

        foreach ($variants as $index => $variant) {
            $variantNumber = $index + 1;

            // Variant price validation
            $messages["variants.{$index}.price.numeric"] = "Variant {$variantNumber} price must be a valid number.";
            $messages["variants.{$index}.price.min"] = "Variant {$variantNumber} price must be at least :min.";
            $messages["variants.{$index}.price.gte"] = "Variant {$variantNumber} price must be greater than or equal to the discount price.";

            // Variant stock quantity validation
            $messages["variants.{$index}.stock_quantity.integer"] = "Variant {$variantNumber} stock quantity must be a valid integer.";
            $messages["variants.{$index}.stock_quantity.min"] = "Variant {$variantNumber} stock quantity must be at least :min.";

            // Variant images validation
            $messages["variants.{$index}.ecommerce_product_images.required"] = "Variant {$variantNumber} must have at least one image.";
            $messages["variants.{$index}.ecommerce_product_images.array"] = "Variant {$variantNumber} images must be an array.";
            $messages["variants.{$index}.ecommerce_product_images.max"] = "Variant {$variantNumber} can have a maximum of :max images.";

            // Variant attributes validation (only if there are active website attributes)
            $messages["variants.{$index}.attributes.required"] = "Variant {$variantNumber} must have at least one attribute.";
            $messages["variants.{$index}.attributes.min"] = "Variant {$variantNumber} must have at least one attribute.";
            $messages["variants.{$index}.attributes.array"] = "Variant {$variantNumber} attributes must be an array.";

            // Handle nested images
            if (isset($variant['ecommerce_product_images']) && is_array($variant['ecommerce_product_images'])) {
                foreach ($variant['ecommerce_product_images'] as $imgIndex => $img) {
                    $imageNumber = $imgIndex + 1;
                    $messages["variants.{$index}.ecommerce_product_images.{$imgIndex}.file.required"] = "Variant {$variantNumber} image {$imageNumber} is required.";
                    $messages["variants.{$index}.ecommerce_product_images.{$imgIndex}.file.file"] = "Variant {$variantNumber} image {$imageNumber} must be a valid file.";
                    $messages["variants.{$index}.ecommerce_product_images.{$imgIndex}.file.mimes"] = "Variant {$variantNumber} image {$imageNumber} must be a file of type: :values.";
                    $messages["variants.{$index}.ecommerce_product_images.{$imgIndex}.file.max"] = "Variant {$variantNumber} image {$imageNumber} size must not exceed :max kilobytes.";
                }
            }

            // Handle nested attributes
            if (isset($variant['attributes']) && is_array($variant['attributes'])) {
                foreach ($variant['attributes'] as $attrIndex => $attr) {
                    $attributeNumber = $attrIndex + 1;
                    $messages["variants.{$index}.attributes.{$attrIndex}.id.integer"] = "Variant {$variantNumber} attribute {$attributeNumber} ID must be a valid integer.";
                    $messages["variants.{$index}.attributes.{$attrIndex}.id.max"] = "Variant {$variantNumber} attribute {$attributeNumber} ID must not exceed :max.";
                    $messages["variants.{$index}.attributes.{$attrIndex}.id.exists"] = "Variant {$variantNumber} attribute {$attributeNumber} does not exist.";

                    $messages["variants.{$index}.attributes.{$attrIndex}.name.string"] = "Variant {$variantNumber} attribute {$attributeNumber} name must be a valid string.";
                    $messages["variants.{$index}.attributes.{$attrIndex}.name.max"] = "Variant {$variantNumber} attribute {$attributeNumber} name must not exceed :max characters.";
                    $messages["variants.{$index}.attributes.{$attrIndex}.name.exists"] = "Variant {$variantNumber} attribute {$attributeNumber} name does not exist.";

                    $messages["variants.{$index}.attributes.{$attrIndex}.type.in"] = "Variant {$variantNumber} attribute {$attributeNumber} type must be either text or select.";

                    $messages["variants.{$index}.attributes.{$attrIndex}.value.max"] = "Variant {$variantNumber} attribute {$attributeNumber} value must not exceed :max characters.";
                }
            }
        }

        return $messages;
    }
}
