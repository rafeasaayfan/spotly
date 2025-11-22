<?php

namespace App\Http\Requests\Websites\Ecommerce;

use App\Models\EcommerceProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'imageUrl' => ['nullable', 'string'],
            'color_id' => [
                'required',
                function ($attribute, $value, $fail) use ($website) {
                    $productId = $this->input('product_id');

                    $exists = EcommerceProductVariant::where('color_id', $value)
                        ->where('product_id', $productId)
                        ->exists();

                    if (!$exists) {
                        $fail('The selected color variant is invalid or does not belong to this product.');
                    }
                }
            ],
            'unit_price' => ['required', 'numeric', 'gt:0']
        ];
    }
}
