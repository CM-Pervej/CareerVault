<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::with(['countries', 'industries', 'cities'])
            ->orderBy('name')
            ->paginate(15);

        return view('job.company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $industries = Industry::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('job.company.create', compact('countries', 'industries', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->cleanRepeatableFields($request);

        $validated = $this->validateCompany($request);

        $company = Company::create([
            'name'          => $validated['name'],
            'slug'          => Str::slug($validated['name']),
            'website'       => $validated['website'] ?? null,
            'career_page'   => $validated['career_page'] ?? null,

            'emails'        => $this->nullIfEmpty($validated['emails'] ?? null),
            'phones'        => $this->nullIfEmpty($validated['phones'] ?? null),
            'address'       => $this->nullIfEmpty($validated['address'] ?? null),
            'social_links'  => $this->nullIfEmpty($validated['social_links'] ?? null),
        ]);


        $company->countries()->sync($validated['country_ids'] ?? []);
        $company->industries()->sync($validated['industry_ids'] ?? []);
        $company->cities()->sync($validated['city_ids'] ?? []);

        return redirect()->route('companies.show', $company)->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company->load([
            'countries:id,name,iso_code',
            'industries:id,name',
            'cities:id,name',
        ]);

        return view('job.company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $countries = Country::orderBy('name')->get();
        $industries = Industry::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('job.company.edit', compact('company', 'countries', 'industries', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $this->cleanRepeatableFields($request);

        $validated = $this->validateCompany($request, $company);

        $company->update([
            'name'          => $validated['name'],
            'slug'          => Str::slug($validated['name']),
            'website'       => $validated['website'] ?? null,
            'career_page'   => $validated['career_page'] ?? null,

            'emails'        => $this->nullIfEmpty($validated['emails'] ?? null),
            'phones'        => $this->nullIfEmpty($validated['phones'] ?? null),
            'address'       => $this->nullIfEmpty($validated['address'] ?? null),
            'social_links'  => $this->nullIfEmpty($validated['social_links'] ?? null),
        ]);

        $company->countries()->sync($validated['country_ids'] ?? []);
        $company->industries()->sync($validated['industry_ids'] ?? []);
        $company->cities()->sync($validated['city_ids'] ?? []);

        return redirect()->route('companies.show', $company)->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    /**
     * Remove empty repeatable fields.
     */
    private function cleanRepeatableFields(Request $request): void
    {
        $request->merge([
            'emails' => collect($request->input('emails', []))
                ->filter(fn ($item) => filled($item['email_type'] ?? null) || filled($item['email'] ?? null))
                ->values()
                ->all(),

            'phones' => collect($request->input('phones', []))
                ->filter(fn ($item) => filled($item['phone_type'] ?? null) || filled($item['phone'] ?? null))
                ->values()
                ->all(),

            'address' => collect($request->input('address', []))
                ->filter(fn ($item) => filled($item['address_type'] ?? null) || filled($item['address'] ?? null))
                ->values()
                ->all(),

            'social_links' => collect($request->input('social_links', []))
                ->filter(fn ($item) => filled($item['platform'] ?? null) || filled($item['url'] ?? null))
                ->values()
                ->all(),
        ]);
    }

    /**
     * Company validation rules.
     */
    private function validateCompany(Request $request, Company $company = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'website' => [ 'nullable', 'url', 'max:255', 'unique:companies,website,' . ($company?->id ?? 'NULL')],
            'career_page' => ['nullable', 'url', 'max:255', 'unique:companies,career_page,' . ($company?->id ?? 'NULL')],

            'emails' => 'nullable|array',
            'emails.*.email_type' => 'required_with:emails.*.email|string|max:255',
            'emails.*.email' => 'required_with:emails.*.email_type|email|max:255',

            'phones' => 'nullable|array',
            'phones.*.phone_type' => 'required_with:phones.*.phone|string|max:255',
            'phones.*.phone' => 'required_with:phones.*.phone_type|string|max:50',

            'address' => 'nullable|array',
            'address.*.address_type' => 'required_with:address.*.address|string|max:255',
            'address.*.address' => 'required_with:address.*.address_type|string|max:255',

            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required_with:social_links.*.url|string|max:100',
            'social_links.*.url' => 'required_with:social_links.*.platform|url|max:255',

            'country_ids' => 'nullable|array',
            'country_ids.*' => 'exists:countries,id',
            
            'industry_ids' => 'nullable|array',
            'industry_ids.*' => 'exists:industries,id',

            'city_ids'  => 'nullable|array',
            'city_ids.*'  => 'exists:cities,id',
        ]);
    }

    /**
     * Convert empty arrays to null.
     */
    private function nullIfEmpty($value)
    {
        return empty($value) ? null : $value;
    }
}