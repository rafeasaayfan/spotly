<?php

namespace App\Http\Requests\Dashboard\Pages\Ui\TemplateTemplateColors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTemplateTemplateColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_type_id' => ['required', 'exists:website_types,id,is_active,1'],
            'template_id' => [
                'required',
                'exists:templates,id,is_active,1',
                Rule::unique('template_template_colors', 'template_id')
                    ->where('website_type_id', $this->input('website_type_id'))
                    ->where('template_color_id', $this->input('template_color_id'))
                    ->ignore($this->route('templateTemplateColor')->id)
            ],
            'template_color_id' => [
                'required',
                'exists:template_colors,id,is_active,1',
                Rule::unique('template_template_colors', 'template_color_id')
                    ->where('website_type_id', $this->input('website_type_id'))
                    ->where('template_id', $this->input('template_id'))
                    ->ignore($this->route('templateTemplateColor')->id)
            ],
            'uiImages.*' => ['nullable', when('string', '', ['file', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'])],
        ];
    }
}
