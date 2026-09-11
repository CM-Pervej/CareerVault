<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $user=$this->route('user');

        return [
            'name'=>[
                'required',
                'string',
                'max:255',
            ],

            'email'=>[
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users','email')->ignore($user?->id),
            ],

            'role'=>[
                'required',
                Rule::in([
                    'user',
                    'admin',
                    'super_admin',
                ]),
            ],

            'status'=>[
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'password'=>[
                'nullable',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if(!$this->user()?->isSuperAdmin()){
            $this->merge([
                'role'=>'user',
            ]);
        }
    }
}