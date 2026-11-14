<?php

namespace App\Http\Requests\Admin;

use App\Models\BusinessModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessModelRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $businessModel = $this->route('business_model');

        if ($businessModel instanceof BusinessModel) {
            return $this->user()?->can(
                $this->isMethod('PUT') || $this->isMethod('PATCH')
                    ? 'update'
                    : 'view',
                $businessModel
            ) ?? false;
        }

        return $this->user()?->can('create', BusinessModel::class) ?? false;
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        $businessModel = $this->route('business_model');

        $businessModelId = $businessModel instanceof BusinessModel
            ? $businessModel->id
            : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('business_models', 'name')
                    ->ignore($businessModelId),
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
            'name.required' => 'The business model name is required.',
            'name.unique' => 'This business model name already exists.',
            'slug.required' => 'The business model slug is required.',
            'slug.unique' => 'This business model slug already exists.',
            'slug.alpha_dash' => 'The slug may contain letters, numbers, dashes, and underscores only.',
            'description.string' => 'The description must be a valid text.',
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