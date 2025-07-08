<?php

namespace App\Http\Requests\Dashboard\Pages\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $validateParentId = function ($attribute, $value, $fail) {
            $currentId = $this->input('id');
            if ($value && $currentId) {
                if ($value == $currentId) {
                    return $fail('The parent category cannot be the same as the category itself.');
                }
                $childrenIds = \App\Models\Category::where('parent_id', $currentId)->pluck('id')->toArray();
                if (in_array($value, $childrenIds)) {
                    return $fail('The parent category cannot be one of its own children.');
                }
            }
        };

        return [
            'website_id' => ['required', 'exists:websites,id'],
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                $validateParentId,
            ],
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->where(function ($query) {
                return $query->where('website_id', $this->website_id);
            })],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
