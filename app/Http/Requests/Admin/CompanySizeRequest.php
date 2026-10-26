<?php

namespace App\Http\Requests\Admin;

use App\Models\CompanySize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanySizeRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $companySize = $this->route('company_size');

        if ($companySize instanceof CompanySize) {
            return $this->user()?->can(
                $this->isMethod('PUT') || $this->isMethod('PATCH')
                    ? 'update'
                    : 'view',
                $companySize
            ) ?? false;
        }

        return $this->user()?->can('create', CompanySize::class) ?? false;
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        $companySize = $this->route('company_size');

        $companySizeId = $companySize instanceof CompanySize
            ? $companySize->id
            : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('company_sizes', 'name')
                    ->ignore($this->route('companySize')),
            ],

            'min_employees' => [
                'required',
                'integer',
                'min:0',
            ],

            'max_employees' => [
                'nullable',
                'integer',
                'gte:min_employees',
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
            'name.required' => 'The company size name is required.',
            'name.unique' => 'This company size name already exists.',
            'slug.required' => 'The company size slug is required.',
            'slug.unique' => 'This company size slug already exists.',
            'slug.alpha_dash' => 'The slug may contain letters, numbers, dashes, and underscores only.',
            'min_employees.required' => 'The minimum employee count is required.',
            'min_employees.min' => 'The minimum employee count cannot be negative.',
            'max_employees.gt' => 'The maximum employee count must be greater than the minimum employee count.',
            'sort_order.required' => 'The sort order is required.',
            'sort_order.min' => 'The sort order cannot be negative.',
            'is_active.required' => 'Please specify whether this company size is active.',
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