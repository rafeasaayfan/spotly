<?php

namespace App\Http\Requests\Dashboard\Pages\Ui\TemplateColors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTemplateColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('template_colors', 'name')
                    ->where('is_custom', false)
                    ->ignore($this->route('templateColor')->id)
            ],

            'bg_body_light' => ['required', 'string', 'max:255'],
            'bg_body_dark' => ['required', 'string', 'max:255'],
            'bg_nav_light' => ['required', 'string', 'max:255'],
            'bg_nav_dark' => ['required', 'string', 'max:255'],
            'bg_footer_light' => ['required', 'string', 'max:255'],
            'bg_footer_dark' => ['required', 'string', 'max:255'],

            'foreground_light' => ['required', 'string', 'max:255'],
            'foreground_dark' => ['required', 'string', 'max:255'],
            'foreground_active_light' => ['required', 'string', 'max:255'],
            'foreground_active_dark' => ['required', 'string', 'max:255'],
            'foreground_muted_light' => ['required', 'string', 'max:255'],
            'foreground_muted_dark' => ['required', 'string', 'max:255'],

            'bg_field_light' => ['required', 'string', 'max:255'],
            'bg_field_dark' => ['required', 'string', 'max:255'],

            'bg_card_light' => ['required', 'string', 'max:255'],
            'bg_card_hover_light' => ['required', 'string', 'max:255'],
            'bg_card_dark' => ['required', 'string', 'max:255'],
            'bg_card_hover_dark' => ['required', 'string', 'max:255'],

            'bg_content_light' => ['required', 'string', 'max:255'],
            'bg_content_hover_light' => ['required', 'string', 'max:255'],
            'bg_content_active_light' => ['required', 'string', 'max:255'],
            'bg_content_dark' => ['required', 'string', 'max:255'],
            'bg_content_hover_dark' => ['required', 'string', 'max:255'],
            'bg_content_active_dark' => ['required', 'string', 'max:255'],

            'bg_dropdown_light' => ['required', 'string', 'max:255'],
            'bg_dropdown_dark' => ['required', 'string', 'max:255'],

            'primary_light' => ['required', 'string', 'max:255'],
            'primary_hover_light' => ['required', 'string', 'max:255'],
            'primary_dark' => ['required', 'string', 'max:255'],
            'primary_hover_dark' => ['required', 'string', 'max:255'],
            'danger_light' => ['required', 'string', 'max:255'],
            'danger_hover_light' => ['required', 'string', 'max:255'],
            'danger_dark' => ['required', 'string', 'max:255'],
            'danger_hover_dark' => ['required', 'string', 'max:255'],
            'secondary_light' => ['required', 'string', 'max:255'],
            'secondary_hover_light' => ['required', 'string', 'max:255'],
            'secondary_dark' => ['required', 'string', 'max:255'],
            'secondary_hover_dark' => ['required', 'string', 'max:255'],

            'border_color_light' => ['required', 'string', 'max:255'],
            'border_color_dark' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string', 'max:100'],

            'is_active' => ['required', 'boolean'],
        ];
    }
}
