<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnershipTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ownershipType = $this->route('ownership_type');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ownership_types', 'name')
                    ->ignore($ownershipType?->id),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('ownership_types', 'slug')
                    ->ignore($ownershipType?->id),
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}