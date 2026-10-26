<?php

namespace App\Http\Requests\Admin;

use App\Models\BusinessModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $businessModel = $this->route('business_model');

        if (! $businessModel instanceof BusinessModel) {
            $businessModel = null;
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('business_models', 'name')->ignore($businessModel?->id),
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}