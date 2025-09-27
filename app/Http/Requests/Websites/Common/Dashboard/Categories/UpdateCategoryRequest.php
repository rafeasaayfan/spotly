<?php

namespace App\Http\Requests\Websites\Common\Dashboard\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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

        $website = app('website');

        return [
            'website_id' => ['required', 'exists:websites,id'],
            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where('website_id', $website->id)
                    ->where('is_active', 1),
                $validateParentId,
            ],

            'name' => ['required', 'string', 'max:30', 'min:3', Rule::unique('categories', 'name')->where(function ($query) {
                return $query->where('website_id', $this->website_id);
            })->ignore($this->route('category')->id)],

            'description' => ['nullable', 'string', 'max:255', 'min:3'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
