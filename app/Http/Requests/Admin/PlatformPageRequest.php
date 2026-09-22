<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlatformPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $page = $this->route('platformPage');

        return [
            'platform_id' => [
                'required',
                'integer',
                'exists:platforms,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('platform_pages', 'slug')
                    ->where(fn ($query) =>
                        $query->where(
                            'platform_id',
                            $this->input('platform_id')
                        )
                    )
                    ->ignore($page?->id),
            ],

            'page_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'url' => [
                'required',
                'url',
                'max:2048',
            ],

            'short_desc' => [
                'nullable',
                'string',
                'max:500',
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
                'nullable',
                'integer',
                'min:0',
                'max:999999',
            ],

            'last_verified_at' => [
                'nullable',
                'date',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'sort_order' => $this->input('sort_order', 0),
        ]);
    }
}