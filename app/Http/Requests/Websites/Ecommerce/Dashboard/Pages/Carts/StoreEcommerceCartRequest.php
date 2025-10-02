<?php

namespace App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Carts;

use Illuminate\Foundation\Http\FormRequest;

class StoreEcommerceCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            
        ];
    }
}
