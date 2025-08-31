<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateWebsiteUiRequest extends FormRequest
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
            'template_images' => [
                Rule::when(
                    $this->input('is_custom'),
                    ['nullable'],
                    ['required', 'array']
                )
            ],
            'template_images.*' => ['string', 'url'],

            'template_id' => ['required', 'exists:templates,id'],
            'template_color_id' => [
                Rule::when(
                    $this->input('is_custom'),
                    ['nullable'],
                    ['required', 'exists:template_colors,id']
                )
            ],
            'is_custom' => [
                Rule::when(
                    $this->input('template_color_id') > 0,
                    ['nullable'],
                    ['required', 'boolean'],
                )
            ],
            'colors' => [
                Rule::when(
                    $this->input('is_custom'),
                    ['required', 'array'],
                    ['nullable']
                )
            ],
        ];
    }
}
