<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlatformConnectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'platform_id' => [
                'required',
                'integer',
                'exists:platforms,id',
            ],

            'connections' => [
                'nullable',
                'array',
            ],

            'connections.*.connected_platform_id' => [
                'required',
                'integer',
                'exists:platforms,id',
            ],

            'connections.*.account_url' => [
                'nullable',
                'url',
                'max:2048',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $platformId = (int) $this->input('platform_id');
            $connections = $this->input('connections', []);

            $connectedPlatformIds = [];

            foreach ($connections as $index => $connection) {
                $connectedPlatformId = (int) ($connection['connected_platform_id'] ?? 0);

                if ($connectedPlatformId === $platformId) {
                    $validator->errors()->add(
                        "connections.{$index}.connected_platform_id",
                        'A platform cannot be connected to itself.'
                    );
                }

                if (in_array($connectedPlatformId, $connectedPlatformIds, true)) {
                    $validator->errors()->add(
                        "connections.{$index}.connected_platform_id",
                        'This platform has already been added.'
                    );
                }

                $connectedPlatformIds[] = $connectedPlatformId;
            }
        });
    }
}