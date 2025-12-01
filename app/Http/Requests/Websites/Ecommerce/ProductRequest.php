<?php

namespace App\Http\Requests\Websites\Ecommerce;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $website = app('website');

        return [
            'product_id' => ['required', 'exists:ecommerce_products,id,website_id,' . $website->id . ',is_active,1'],

            'quantity' => ['required', 'integer', 'gt:0'],

            'variantId' => ['required', 'exists:ecommerce_product_variants,id'],
        ];
    }
}
