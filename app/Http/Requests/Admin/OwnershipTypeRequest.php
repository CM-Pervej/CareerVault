<?php

namespace App\Http\Requests\Admin;

use App\Models\OwnershipType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnershipTypeRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $ownershipType = $this->route('ownership_type');

        if ($ownershipType instanceof OwnershipType) {
            return $this->user()?->can(
                $this->isMethod('PUT') || $this->isMethod('PATCH')
                    ? 'update'
                    : 'view',
                $ownershipType
            ) ?? false;
        }

        return $this->user()?->can('create', OwnershipType::class) ?? false;
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        $ownershipType = $this->route('ownership_type');

        $ownershipTypeId = $ownershipType instanceof OwnershipType
            ? $ownershipType->id
            : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ownership_types', 'name')
                    ->ignore($ownershipTypeId),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('ownership_types', 'slug')
                    ->ignore($ownershipTypeId),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The ownership type name is required.',
            'name.unique' => 'This ownership type name already exists.',
            'slug.unique' => 'This ownership type slug already exists.',
            'slug.alpha_dash' => 'The slug may contain letters, numbers, dashes, and underscores only.',
            'sort_order.integer' => 'The sort order must be a valid integer.',
            'sort_order.min' => 'The sort order cannot be negative.',
            'is_active.boolean' => 'Please specify a valid active status.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('slug')) {
            $this->merge([
                'slug' => trim((string) $this->input('slug')),
            ]);
        }
    }
}