<?php

namespace App\Http\Requests\Dashboard\Pages\EmailSubscribers;

use Illuminate\Foundation\Http\FormRequest;

class StoreemailSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:email_subscribers',
        ];
    }
}
