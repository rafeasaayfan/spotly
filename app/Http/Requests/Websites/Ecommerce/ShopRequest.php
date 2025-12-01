<?php

namespace App\Http\Requests\Websites\Ecommerce;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
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
        return [
            'limit'     => ['nullable', 'integer', 'min:1', 'max:100'],
            'search'    => ['nullable', 'string', 'max:150'],
            'category'  => ['nullable', 'string', 'max:100'],
            'brand'     => ['nullable', 'string', 'max:100'],
            'minPrice'  => ['nullable', 'numeric', 'min:0'],
            'maxPrice'  => ['nullable', 'numeric', 'gt:minPrice', 'min:0'],
            'special'   => ['nullable', 'in:true,false'],
            'onSale'    => ['nullable', 'in:true,false'],
        ];
    }
}
