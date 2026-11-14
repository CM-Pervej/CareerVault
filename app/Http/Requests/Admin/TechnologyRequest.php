<?php

namespace App\Http\Requests\Admin;

use App\Models\TechCategory;
use App\Models\Technology;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TechnologyRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $technology = $this->route('technology');

        if ($technology instanceof Technology) {
            return $this->user()?->can(
                $this->isMethod('PUT') || $this->isMethod('PATCH')
                    ? 'update'
                    : 'view',
                $technology
            ) ?? false;
        }

        return $this->user()?->can('create', Technology::class) ?? false;
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        $technology = $this->route('technology');

        $technologyId = $technology instanceof Technology
            ? $technology->id
            : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('technologies', 'name')
                    ->ignore($technologyId),
            ],

            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('tech_categories', 'id')
                    ->whereNull('deleted_at'),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:255',
            ],

            'color' => [
                'nullable',
                'string',
                'max:30',
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
            'name.required' => 'The technology name is required.',
            'name.unique' => 'This technology name already exists.',
            'category_id.integer' => 'Please select a valid technology category.',
            'category_id.exists' => 'The selected technology category is invalid or has been deleted.',
            'description.string' => 'The description must be valid text.',
            'icon.string' => 'The icon must be valid text.',
            'color.string' => 'The color must be valid text.',
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
    }
}