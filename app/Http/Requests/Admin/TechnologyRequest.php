<?php

namespace App\Http\Requests\Admin;

use App\Models\Technology;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TechnologyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $technology = $this->route('technology');

        if (! $technology instanceof Technology) {
            $technology = null;
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('technologies', 'name')->ignore($technology?->id),
            ],

            'category' => [
                'nullable',
                'string',
                'max:255',
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
}