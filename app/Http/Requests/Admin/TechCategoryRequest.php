<?php

namespace App\Http\Requests\Admin;

use App\Models\TechCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TechCategoryRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $techCategory = $this->route('tech_category');

        if ($techCategory instanceof TechCategory) {
            return $this->user()?->can(
                $this->isMethod('PUT') || $this->isMethod('PATCH')
                    ? 'update'
                    : 'view',
                $techCategory
            ) ?? false;
        }

        return $this->user()?->can('create', TechCategory::class) ?? false;
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        $techCategory = $this->route('tech_category');

        $techCategoryId = $techCategory instanceof TechCategory
            ? $techCategory->id
            : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tech_categories', 'name')
                    ->ignore($techCategoryId),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The technology category name is required.',
            'name.unique' => 'This technology category name already exists.',
            'slug.required' => 'The technology category slug is required.',
            'slug.unique' => 'This technology category slug already exists.',
            'slug.alpha_dash' => 'The slug may contain letters, numbers, dashes, and underscores only.',
            'description.string' => 'The description must be valid text.',
            'is_active.boolean' => 'The active status must be either active or inactive.',
            'sort_order.required' => 'The sort order is required.',
            'sort_order.integer' => 'The sort order must be a valid integer.',
            'sort_order.min' => 'The sort order cannot be negative.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => trim((string) $this->input('name')),
            ]);
        }

        if ($this->has('slug')) {
            $this->merge([
                'slug' => trim((string) $this->input('slug')),
            ]);
        }
    }
}