<?php

namespace App\Http\Requests\Dashboard\Pages\WebsiteTemplates;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsiteTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_id' => ['required', 'exists:websites,id'],
            'template_id' => [
                'required',
                'exists:templates,id',
                Rule::unique('website_templates', 'template_id')->where('website_id', $this->input('website_id'))
                    ->ignore($this->route('websiteTemplate')->id),
            ],
            'template_color_id' => [
                'required',
                'exists:template_colors,id',
                Rule::unique('website_templates', 'template_color_id')->where('template_id', $this->input('template_id'))
                    ->ignore($this->route('websiteTemplate')->id),
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
