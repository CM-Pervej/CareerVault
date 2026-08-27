<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Industry;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $totalCompanies = Company::count();

        $companies = Company::query()->with(['countries', 'industries', 'cities',])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    
                    $query->where('name', 'like', "%{$search}%")                    // Company name

                    ->orWhereHas('cities', function ($query) use ($search) {        // City name
                        $query->where('name', 'like', "%{$search}%");
                    })

                    ->orWhereHas('countries', function ($query) use ($search) {     // Country name
                        $query->where('name', 'like', "%{$search}%");
                    })

                    ->orWhereHas('industries', function ($query) use ($search) {    // Industry name
                        $query->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        // AJAX search/pagination request
        if ($request->ajax()) {
            return response()->json(['html' => view('job.company.index', compact('companies', 'totalCompanies'))->render(),]);
        }

        return view('job.company.index', compact('companies', 'totalCompanies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $industries = Industry::orderBy('name')->get();
        $cities = collect();
        $platforms = Platform::orderBy('name')->get();

        return view('job.company.create', compact('countries', 'industries', 'cities', 'platforms'));
    }

    /**
     * Return cities belonging to the selected countries.
     *
     * Used by AJAX when countries are selected/changed.
     */
    public function cities(Request $request)
    {
        $countryIds = $request->input('country_ids', []);

        if (!is_array($countryIds)) {
            $countryIds = [$countryIds];
        }

        $countryIds = collect($countryIds)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($countryIds->isEmpty()) {
            return response()->json([]);
        }

        $cities = City::whereIn('country_id', $countryIds)
            ->orderBy('name')
            ->get(['id', 'country_id', 'name',]);

        return response()->json($cities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->cleanRepeatableFields($request);

        $validated = $this->validateCompany($request);

        $countryIds = $validated['country_ids'] ?? [];
        $cityIds = $validated['city_ids'] ?? [];

        $this->validateCitiesBelongToCountries($cityIds, $countryIds);

        $company = Company::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'website' => $validated['website'] ?? null,
            'career_page' => $validated['career_page'] ?? null,

            'emails' => $this->nullIfEmpty($validated['emails'] ?? null),
            'phones' => $this->nullIfEmpty($validated['phones'] ?? null),
            'address' => $this->nullIfEmpty($validated['address'] ?? null),
            // 'social_links' => $this->nullIfEmpty($validated['social_links'] ?? null),
        ]);

        $company->countries()->sync($countryIds);
        $company->industries()->sync($validated['industry_ids'] ?? []);
        $company->cities()->sync($cityIds);
        $this->syncPlatforms(
            $company,
            $validated['platform_ids'] ?? [],
            $validated['platform_urls'] ?? [],
        );

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
            'cities:id,country_id,name',
            'platforms:id,name,icon,color',
        ]);

        // Group cities by their country
        $citiesByCountry = $company->cities->sortBy('name')->groupBy('country_id');

        return view('job.company.show', compact('company', 'citiesByCountry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $company->load([
            'countries:id,name', 
            'industries:id,name', 
            'cities:id,country_id,name', 
            'platforms:id,name,icon,color',
        ]);

        $countries = Country::orderBy('name')->get();
        $industries = Industry::orderBy('name')->get();
        $platforms = Platform::orderBy('name')->get();

        $countryIds = $company->countries->pluck('id')->toArray();

        $cities = empty($countryIds)
            ? collect()
            : City::whereIn('country_id', $countryIds)
                ->orderBy('name')
                ->get(['id', 'country_id', 'name',]);

        return view('job.company.edit', compact('company', 'countries', 'industries', 'cities', 'platforms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $this->cleanRepeatableFields($request);

        $validated = $this->validateCompany($request, $company);

        $countryIds = $validated['country_ids'] ?? [];
        $cityIds = $validated['city_ids'] ?? [];

        $this->validateCitiesBelongToCountries($cityIds, $countryIds);

        $company->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'website' => $validated['website'] ?? null,
            'career_page' => $validated['career_page'] ?? null,

            'emails' => $this->nullIfEmpty($validated['emails'] ?? null),
            'phones' => $this->nullIfEmpty($validated['phones'] ?? null),
            'address' => $this->nullIfEmpty($validated['address'] ?? null),
            // 'social_links' => $this->nullIfEmpty($validated['social_links'] ?? null),
        ]);

        /*
         * Sync countries, industries, cities
         */
        $company->countries()->sync($countryIds);
        $company->cities()->sync($cityIds);
        $company->industries()->sync($validated['industry_ids'] ?? []);
        $this->syncPlatforms(
            $company,
            $validated['platform_ids'] ?? [],
            $validated['platform_urls'] ?? [],
        );

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
                ->filter(
                    fn ($item) => filled($item['email_type'] ?? null) || filled($item['email'] ?? null)
                )
                ->values()
                ->all(),

            'phones' => collect($request->input('phones', []))
                ->filter(
                    fn ($item) => filled($item['phone_type'] ?? null) || filled($item['phone'] ?? null)
                )
                ->values()
                ->all(),

            'address' => collect($request->input('address', []))
                ->filter(
                    fn ($item) => filled($item['address_type'] ?? null) || filled($item['address'] ?? null)
                )
                ->values()
                ->all(),

            // 'social_links' => collect($request->input('social_links', []))
            //     ->filter(
            //         fn ($item) => filled($item['platform'] ?? null) || filled($item['url'] ?? null)
            //     )
            //     ->values()
            //     ->all(),
        ]);
    }

    /**
     * Company validation rules.
     */
    private function validateCompany(Request $request, ?Company $company = null): array {
        return $request->validate([
            'name' => ['required', 'string', 'max:255',],
            'website' => ['nullable', 'url', 'max:255', 'unique:companies,website,' . ($company?->id ?? 'NULL'),],
            'career_page' => ['nullable', 'url', 'max:255', 'unique:companies,career_page,' . ($company?->id ?? 'NULL'),],

            'emails' => ['nullable', 'array',],
            'emails.*.email_type' => ['required_with:emails.*.email', 'string', 'max:255',],
            'emails.*.email' => ['required_with:emails.*.email_type', 'email', 'max:255',],

            'phones' => ['nullable', 'array',],
            'phones.*.phone_type' => ['required_with:phones.*.phone', 'string', 'max:255',],
            'phones.*.phone' => ['required_with:phones.*.phone_type', 'string', 'max:50',],

            'address' => ['nullable', 'array',],
            'address.*.address_type' => ['required_with:address.*.address', 'string', 'max:255',],
            'address.*.address' => ['required_with:address.*.address_type', 'string', 'max:255',],

            // 'social_links' => ['nullable', 'array',],
            // 'social_links.*.platform' => ['required_with:social_links.*.url', 'string', 'max:100',],
            // 'social_links.*.url' => ['required_with:social_links.*.platform', 'url', 'max:255',],

            'country_ids' => ['nullable', 'array',],
            'country_ids.*' => ['integer', 'exists:countries,id',],

            'industry_ids' => ['nullable', 'array',],
            'industry_ids.*' => ['integer', 'exists:industries,id',],
            
            'city_ids' => ['nullable', 'array',],
            'city_ids.*' => ['integer', 'exists:cities,id',],

            'platform_ids' => ['nullable', 'array',],
            'platform_ids.*' => ['integer', 'exists:platforms,id', 'distinct',],

            'platform_urls' => ['nullable', 'array',],
            'platform_urls.*' => ['required', 'url', 'max:255',],
        ]);
    }

    /**
     * Sync platforms and their URLs.
     * One company can have multiple different platforms.
     */
    private function syncPlatforms(Company $company,array $platformIds,array $platformUrls): void
    {
        $platforms = [];

        foreach($platformIds as $platformId){
            $platformId = (int)$platformId;

            $platforms[$platformId] = [
                'url' => $platformUrls[$platformId],
            ];
        }

        $company->platforms()->sync($platforms);
    }

    /**
     * Make sure every selected city belongs to one
     * of the selected countries.
     */
    private function validateCitiesBelongToCountries(array $cityIds, array $countryIds): void 
    {
        $cityIds = array_values(array_unique($cityIds));
        $countryIds = array_values(array_unique($countryIds));

        if (empty($cityIds)) {
            return;
        }

        if (empty($countryIds)) {
            throw ValidationException::withMessages([
                'city_ids' => 'Please select a country before selecting cities.',
            ]);
        }

        $validCityCount = City::whereIn('id', $cityIds)
            ->whereIn('country_id', $countryIds)
            ->count();

        if ($validCityCount !== count($cityIds)) {
            throw ValidationException::withMessages([
                'city_ids' => 'One or more selected cities do not belong to the selected countries.',
            ]);
        }
    }

    /**
     * Convert empty arrays to null.
     */
    private function nullIfEmpty($value)
    {
        return empty($value) ? null : $value;
    }
}