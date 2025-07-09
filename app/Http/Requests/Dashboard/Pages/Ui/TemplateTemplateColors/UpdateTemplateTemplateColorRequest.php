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
            'template_id' => ['required', 'exists:templates,id'],
            'template_color_id' => [
                'required',
                'exists:template_colors,id',
                Rule::unique('template_template_colors', 'template_color_id')
                    ->where('template_id', $this->input('template_id'))
                    ->ignore($this->route('templateTemplateColor')->id)
            ],
            'is_default' => ['required', 'boolean'],
        ];
    }
}
