<?php

namespace App\Http\Requests\Websites\Common\Dashboard\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
