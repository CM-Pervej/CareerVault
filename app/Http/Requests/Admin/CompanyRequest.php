<?php

namespace App\Http\Requests\Admin;

use App\Models\City;
use App\Models\Company;
use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_verified' => $this->boolean('is_verified'),
            'is_featured' => $this->boolean('is_featured'),
            'platform_ids' => $this->input('platform_ids', []) ?: [],
        ]);

        $platformVerification = $this->input('platform_is_verified', []);

        if (is_array($platformVerification)) {
            foreach ($platformVerification as $platformId => $verified) {
                $platformVerification[$platformId] = filter_var(
                    $verified,
                    FILTER_VALIDATE_BOOLEAN
                );
            }

            $this->merge([
                'platform_is_verified' => $platformVerification,
            ]);
        }
    }

    public function rules(): array
    {
        $company = $this->route('company');
        $companyId = $company instanceof Company ? $company->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'short_desc' => ['nullable', 'string', 'max:10000'],
            'description' => ['nullable', 'string'],

            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'website' => ['nullable', 'url', 'max:2048'],
            'career_page' => ['nullable', 'url', 'max:2048'],
            'emails_text' => ['nullable', 'string', 'max:10000'],
            'phones_text' => ['nullable', 'string', 'max:10000'],

            'founded_by' => ['nullable', 'string', 'max:255'],
            'founded_month' => ['nullable', 'integer', 'between:1,12'],
            'founded_year' => ['nullable', 'integer', 'between:1800,' . now()->year],
            'employee_count' => ['nullable', 'integer', 'min:0'],

            'company_size_id' => [
                'nullable', 'integer',
                Rule::exists('company_sizes', 'id')->whereNull('deleted_at'),
            ],
            'company_type_id' => [
                'nullable', 'integer',
                Rule::exists('company_types', 'id')->whereNull('deleted_at'),
            ],
            'ownership_type_id' => [
                'nullable', 'integer',
                Rule::exists('ownership_types', 'id')->whereNull('deleted_at'),
            ],
            'primary_industry_id' => [
                'nullable', 'integer',
                Rule::exists('industries', 'id')->whereNull('deleted_at'),
            ],
            'parent_company_id' => [
                'nullable', 'integer',
                Rule::exists('companies', 'id')
                    ->whereNull('deleted_at')
                    ->where('id', '!=', $companyId ?? 0),
            ],

            'business_model_ids' => ['sometimes', 'array'],
            'business_model_ids.*' => [
                'required', 'integer', 'distinct',
                Rule::exists('business_models', 'id')->whereNull('deleted_at'),
            ],
            'industry_ids' => ['sometimes', 'array'],
            'industry_ids.*' => [
                'required', 'integer', 'distinct',
                Rule::exists('industries', 'id')->whereNull('deleted_at'),
            ],
            'technology_ids' => ['sometimes', 'array'],
            'technology_ids.*' => [
                'required', 'integer', 'distinct',
                Rule::exists('technologies', 'id')->whereNull('deleted_at'),
            ],
            'country_ids' => ['sometimes', 'array'],
            'country_ids.*' => [
                'required', 'integer', 'distinct',
                Rule::exists('countries', 'id')->whereNull('deleted_at'),
            ],
            'state_ids' => ['sometimes', 'array'],
            'state_ids.*' => [
                'required', 'integer', 'distinct',
                Rule::exists('states', 'id')->whereNull('deleted_at'),
            ],

            'company_places' => ['sometimes', 'array'],
            'company_places.*.city_id' => [
                'required', 'integer',
                Rule::exists('cities', 'id')->whereNull('deleted_at'),
            ],
            'company_places.*.place' => ['nullable', 'string', 'max:255'],

            // Each platform row is submitted so unchecked platforms can be detached.
            'platform_ids' => ['sometimes', 'array'],
            'platform_ids.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('platforms', 'id')->whereNull('deleted_at'),
            ],

            'platform_urls' => ['sometimes', 'array'],
            'platform_urls.*' => ['nullable', 'url', 'max:2048'],

            'platform_is_verified' => ['sometimes', 'array'],
            'platform_is_verified.*' => ['nullable', 'boolean'],

            'platform_last_verified_at' => ['sometimes', 'array'],
            'platform_last_verified_at.*' => ['nullable', 'date'],


            'status' => ['required', Rule::in(['active', 'inactive', 'draft', 'archived'])],
            'operating_status' => [
                'nullable',
                Rule::in(['operating', 'acquired', 'merged', 'closed', 'dissolved', 'unknown']),
            ],
            'is_verified' => ['required', 'boolean'],
            'is_featured' => ['required', 'boolean'],
            'last_verified_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:10000'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $countryIds = collect($this->input('country_ids', []))
                    ->map(fn ($id) => (int) $id)->all();

                $stateIds = collect($this->input('state_ids', []))
                    ->map(fn ($id) => (int) $id)->all();

                if ($this->exists('state_ids')) {
                    $states = State::query()->whereIn('id', $stateIds)->get(['id', 'country_id']);

                    foreach ($states as $state) {
                        if (!in_array((int) $state->country_id, $countryIds, true)) {
                            $validator->errors()->add(
                                'state_ids',
                                "The selected state (ID {$state->id}) does not belong to a selected country."
                            );
                        }
                    }
                }

                foreach ($this->input('company_places', []) as $index => $place) {
                    if (empty($place['city_id'])) {
                        continue;
                    }

                    $city = City::query()
                        ->whereKey($place['city_id'])
                        ->first(['id', 'country_id', 'state_id']);

                    if (!$city) {
                        continue;
                    }

                    if (!in_array((int) $city->state_id, $stateIds, true)) {
                        $validator->errors()->add(
                            "company_places.{$index}.city_id",
                            'The city must belong to one of the selected states.'
                        );
                    }

                    if (!in_array((int) $city->country_id, $countryIds, true)) {
                        $validator->errors()->add(
                            "company_places.{$index}.city_id",
                            'The city must belong to one of the selected countries.'
                        );
                    }

                    $stateCountryId = DB::table('states')
                        ->where('id', $city->state_id)
                        ->whereNull('deleted_at')
                        ->value('country_id');

                    if (
                        $stateCountryId !== null
                        && (int) $stateCountryId !== (int) $city->country_id
                    ) {
                        $validator->errors()->add(
                            "company_places.{$index}.city_id",
                            'The city and its state have inconsistent country assignments.'
                        );
                    }
                }

                if (
                    $this->filled('primary_industry_id')
                    && $this->exists('industry_ids')
                    && !in_array(
                        (int) $this->input('primary_industry_id'),
                        collect($this->input('industry_ids', []))->map(fn ($id) => (int) $id)->all(),
                        true
                    )
                ) {
                    $validator->errors()->add(
                        'primary_industry_id',
                        'The primary industry must also be selected in the company industries.'
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'founded_month.between' => 'The founded month must be between 1 and 12.',
            'founded_year.between' => 'Please enter a valid founding year.',
            'parent_company_id.exists' => 'Please select a valid parent company other than this company.',
            'logo.image' => 'The logo must be a valid image file.',
            'cover_image.image' => 'The cover image must be a valid image file.',
            'business_model_ids.*.exists' => 'One or more selected business models are invalid or inactive.',
            'industry_ids.*.exists' => 'One or more selected industries are invalid or inactive.',
            'technology_ids.*.exists' => 'One or more selected technologies are invalid or inactive.',
            'country_ids.*.exists' => 'One or more selected countries are invalid or inactive.',
            'state_ids.*.exists' => 'One or more selected states are invalid or inactive.',
            'company_places.*.city_id.exists' => 'One or more selected cities are invalid or inactive.',
            'platform_ids.*.exists' => 'One or more selected platforms are invalid.',
            'platform_urls.*.url' => 'Each platform profile must be a valid URL.',
            'platform_is_verified.*.boolean' => 'Platform verification must be true or false.',
            'platform_last_verified_at.*.date' => 'Each platform verification date must be valid.',
        ];
    }
}
