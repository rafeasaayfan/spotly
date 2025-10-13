<?php

namespace App\Http\Requests\Websites\Common\Dashboard\Categories;

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

        $website = app('website');

        return [
            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where('website_id', $website->id)
                    ->where('is_active', 1),
                $validateParentId
            ],

            'name' => [
                'required',
                'string',
                'max:50',
                'min:2',
                Rule::unique('categories', 'name')
                    ->where('website_id', $website->id),
            ],
            'ar_name' => [
                'required',
                'string',
                'max:50',
                'min:2',
                Rule::unique('categories', 'ar_name')
                    ->where('website_id', $website->id),
            ],

            'description' => ['nullable', 'string', 'max:255', 'min:3'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
