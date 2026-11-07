<?php

namespace App\Http\Requests\Admin;

use App\Models\CompanyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyTypeRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $companyType = $this->route('company_type');

        if ($companyType instanceof CompanyType) {
            return $this->user()?->can(
                $this->isMethod('PUT') || $this->isMethod('PATCH')
                    ? 'update'
                    : 'view',
                $companyType
            ) ?? false;
        }

        return $this->user()?->can('create', CompanyType::class) ?? false;
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        $companyType = $this->route('company_type');

        $companyTypeId = $companyType instanceof CompanyType
            ? $companyType->id
            : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('company_types', 'name')
                    ->ignore($companyTypeId),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('company_types', 'slug')
                    ->ignore($companyTypeId),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'required',
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
            'name.required' => 'The company type name is required.',
            'name.unique' => 'This company type name already exists.',
            'slug.required' => 'The company type slug is required.',
            'slug.unique' => 'This company type slug already exists.',
            'slug.alpha_dash' => 'The slug may contain letters, numbers, dashes, and underscores only.',
            'sort_order.required' => 'The sort order is required.',
            'sort_order.min' => 'The sort order cannot be negative.',
            'is_active.required' => 'Please specify whether this company type is active.',
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
