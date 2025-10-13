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
            'slug' => ['required', 'string'],
            'product_id' => ['required', 'exists:ecommerce_products,id,website_id,' . $website->id . ',is_active,1'],
            'quantity' => ['required', 'integer'],
            'imageUrl' => ['nullable', 'string'],
            'color' => ['required', 'string', 'max:50'],
            'unit_price' => ['required']
        ];
    }
}
