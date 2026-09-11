<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlatformRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $platform=$this->route('platform');

        return [
            'name'=>[
                'required',
                'string',
                'max:255',
            ],

            'official_name'=>[
                'nullable',
                'string',
                'max:255',
            ],

            'base_url'=>[
                'nullable',
                'url',
                'max:255',
            ],

            'job_url'=>[
                'nullable',
                'url',
                'max:255',
            ],

            'short_desc'=>[
                'nullable',
                'string',
                'max:500',
            ],

            'description'=>[
                'nullable',
                'string',
            ],

            'job_type'=>[
                'required',
                Rule::in([
                    'Onsite',
                    'Remote',
                    'Both',
                ]),
            ],

            'business_model'=>[
                'required',
                Rule::in([
                    'Free',
                    'Freemium',
                    'Paid',
                ]),
            ],

            'account_required'=>[
                'boolean',
            ],

            'is_active'=>[
                'boolean',
            ],

            'color'=>[
                'nullable',
                'string',
                'max:50',
            ],

            'icon'=>[
                'nullable',
                'string',
                'max:255',
            ],

            'logo'=>[
                'nullable',
                'string',
                'max:255',
            ],

            'cover_image'=>[
                'nullable',
                'string',
                'max:255',
            ],

            'sort_order'=>[
                'required',
                'integer',
                'min:0',
            ],

            'is_bangladesh_focused'=>[
                'boolean',
            ],

            'founded_month'=>[
                'nullable',
                'integer',
                'between:1,12',
            ],

            'founded_year'=>[
                'nullable',
                'integer',
                'min:1800',
                'max:'.date('Y'),
            ],

            'last_verified_at'=>[
                'nullable',
                'date',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'account_required'=>$this->boolean('account_required'),
            'is_active'=>$this->boolean('is_active'),
            'is_bangladesh_focused'=>$this->boolean('is_bangladesh_focused'),
        ]);
    }
}