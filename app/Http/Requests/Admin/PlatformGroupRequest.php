<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlatformGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $platformGroup = $this->route('platformGroup');

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
                Rule::unique('platform_groups', 'slug')
                    ->where(fn ($query) => $query->where(
                        'platform_id',
                        $this->input('platform_id')
                    ))
                    ->ignore($platformGroup?->id),
            ],

            'group_type' => [
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

            'access_type' => [
                'required',
                Rule::in([
                    'public',
                    'members_only',
                    'private',
                ]),
            ],

            'is_bangladesh_focused' => [
                'required',
                'boolean',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', // 2 MB
            ],

            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120', // 5 MB
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'sort_order' => [
                'required',
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
            'is_bangladesh_focused' => $this->boolean(
                'is_bangladesh_focused'
            ),

            'is_active' => $this->boolean('is_active'),

            'sort_order' => (int) $this->input(
                'sort_order',
                0
            ),
        ]);
    }

    public function messages(): array
    {
        return [
            'logo.uploaded' => $this->uploadErrorMessage('logo'),
            'cover_image.uploaded' => $this->uploadErrorMessage('cover_image'),
        ];
    }

    private function uploadErrorMessage(string $field): string
    {
        $file = $this->file($field);

        return $file
            ? $file->getErrorMessage()
            : 'The file could not be uploaded.';
    }
}