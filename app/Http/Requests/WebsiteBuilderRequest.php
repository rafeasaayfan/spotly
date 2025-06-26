<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteBuilderRequest extends FormRequest
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

        $validation = [];

        switch ($this->input('step')) {
            case '1':
                $validation = [
                    'website_type_id' => ['required', 'exists:website_types,id'],
                    'logo' => ['required', 'file', 'mimes:svg', 'max:2048'],
                    'name' => ['required', 'string', 'max:15', 'min:3', 'unique:websites,name'],
                    'subdomain' => ['required', 'string', 'max:15', 'min:3', 'unique:websites,subdomain'],
                    'language' => ['required', 'string', 'in:en,ar,fr'],
                    'description' => ['required', 'text', 'max:255', 'min:10'],
                ];
                break;
            case '2':
                $validation = [
                    'website_type_id' => ['required', 'exists:website_types,id'],
                    'logo' => ['required', 'file', 'mimes:svg', 'max:2048'],
                    'name' => ['required', 'string', 'max:15', 'min:3', 'unique:websites,name'],
                    'subdomain' => ['required', 'string', 'max:15', 'min:3', 'unique:websites,subdomain'],
                    'language' => ['required', 'string', 'in:en,ar,fr'],
                    'description' => ['required', 'text', 'max:255', 'min:10'],
                ];
                break;
        };

        return $validation;
    }
}
