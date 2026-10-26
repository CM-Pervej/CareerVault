<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyRequest;
use App\Models\BusinessModel;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanySize;
use App\Models\CompanyType;
use App\Models\Country;
use App\Models\Industry;
use App\Models\OwnershipType;
use App\Models\Platform;
use App\Models\State;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Company::query()->with([
            'companySize:id,name',
            'companyType:id,name',
            'ownershipType:id,name',
            'primaryIndustry:id,name',
            'parentCompany:id,name,slug',
            'platforms:id,name,slug',
        ]);

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());

            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('legal_name', 'like', "%{$search}%")
                    ->orWhere('tagline', 'like', "%{$search}%")
                    ->orWhere('website', 'like', "%{$search}%");
            });
        }

        foreach ([
            'company_type_id',
            'company_size_id',
            'ownership_type_id',
            'primary_industry_id',
        ] as $field) {
            $query->when(
                $request->filled($field),
                fn ($query) => $query->where($field, $request->input($field))
            );
        }

        $query->when(
            $request->filled('status'),
            fn ($query) => $query->where('status', $request->input('status'))
        );

        $query->when(
            $request->filled('operating_status'),
            fn ($query) => $query->where(
                'operating_status',
                $request->input('operating_status')
            )
        );

        if ($request->input('verified') !== null && $request->input('verified') !== '') {
            $query->where('is_verified', $request->boolean('verified'));
        }

        if ($request->input('featured') !== null && $request->input('featured') !== '') {
            $query->where('is_featured', $request->boolean('featured'));
        }

        $companies = $query->orderBy('name')->paginate(20)->withQueryString();

        $stats = [
            'total' => Company::count(),
            'active' => Company::where('status', 'active')->count(),
            'verified' => Company::where('is_verified', true)->count(),
            'featured' => Company::where('is_featured', true)->count(),
            'trash' => Company::onlyTrashed()->count(),
        ];

        return view('admin.company.company.index', [
            'companies' => $companies,
            'stats' => $stats,
            'search' => $request->input('search', ''),
            'filters' => $request->only([
                'company_type_id',
                'company_size_id',
                'ownership_type_id',
                'primary_industry_id',
                'status',
                'operating_status',
                'verified',
                'featured',
            ]),
            'companyTypes' => CompanyType::orderBy('name')->get(['id', 'name']),
            'companySizes' => CompanySize::orderBy('sort_order')
                ->orderBy('name')->get(['id', 'name']),
            'ownershipTypes' => OwnershipType::orderBy('name')->get(['id', 'name']),
            'industries' => Industry::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('admin.company.company.create', $this->formOptions());
    }

    public function store(CompanyRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $relationships = $this->extractRelationships($data);

        $emails = $this->parseLines($data['emails_text'] ?? '');
        $phones = $this->parseLines($data['phones_text'] ?? '');

        unset($data['emails_text'], $data['phones_text'], $data['logo'], $data['cover_image']);

        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['emails'] = $emails ?: null;
        $data['phones'] = $phones ?: null;
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        $data['employee_count_updated_at'] = filled($data['employee_count'] ?? null) ? now() : null;
        $data['last_verified_at'] = !empty($data['is_verified'])
            ? ($data['last_verified_at'] ?? now())
            : null;

        $company = DB::transaction(function () use ($request, $data, $relationships) {
            $company = Company::create($data);

            if ($request->hasFile('logo')) {
                $company->logo = $request->file('logo')->store('companies/logos', 'public');
            }

            if ($request->hasFile('cover_image')) {
                $company->cover_image = $request->file('cover_image')->store('companies/covers', 'public');
            }

            $company->save();
            $this->syncRelationships($company, $relationships);

            return $company;
        });

        return redirect()->route('admin.companies.show', $company)
            ->with('success', 'Company created successfully.');
    }

    public function show(Company $company): View
    {
        $company->load([
            'companySize',
            'companyType',
            'ownershipType',
            'primaryIndustry',
            'parentCompany',
            'subsidiaries',
            'creator',
            'updater',
            'deleter',
            'businessModels',
            'industries',
            'technologies',
            'countries',
            'states',
            'companyPlaces.city.state.country',
            'platforms',
        ]);

        return view('admin.company.company.show', compact('company'));
    }

    public function edit(Company $company): View
    {
        $company->load([
            'businessModels',
            'industries',
            'technologies',
            'countries',
            'states',
            'companyPlaces.city.state.country',
            'platforms',
        ]);

        return view(
            'admin.company.company.edit',
            array_merge($this->formOptions($company), compact('company'))
        );
    }

    public function update(CompanyRequest $request, Company $company): RedirectResponse
    {
        $data = $request->validated();
        $relationships = $this->extractRelationships($data);

        $emails = $this->parseLines($data['emails_text'] ?? '');
        $phones = $this->parseLines($data['phones_text'] ?? '');

        unset($data['emails_text'], $data['phones_text'], $data['logo'], $data['cover_image']);

        if ($data['name'] !== $company->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $company->id);
        }

        $data['emails'] = $emails ?: null;
        $data['phones'] = $phones ?: null;
        $data['updated_by'] = Auth::id();

        if (
            array_key_exists('employee_count', $data)
            && (int) $data['employee_count'] !== (int) $company->employee_count
        ) {
            $data['employee_count_updated_at'] = filled($data['employee_count'] ?? null) ? now() : null;
        }

        $data['last_verified_at'] = !empty($data['is_verified'])
            ? ($data['last_verified_at'] ?? $company->last_verified_at ?? now())
            : null;

        $oldLogo = $company->logo;
        $oldCover = $company->cover_image;
        $newLogo = null;
        $newCover = null;

        DB::transaction(function () use (
            $request, $company, $data, $relationships, &$newLogo, &$newCover
        ) {
            $company->fill($data);

            if ($request->hasFile('logo')) {
                $newLogo = $request->file('logo')->store('companies/logos', 'public');
                $company->logo = $newLogo;
            }

            if ($request->hasFile('cover_image')) {
                $newCover = $request->file('cover_image')->store('companies/covers', 'public');
                $company->cover_image = $newCover;
            }

            $company->save();
            $this->syncRelationships($company, $relationships);
        });

        // Remove old files only after the database transaction succeeds.
        if ($newLogo && $oldLogo) {
            Storage::disk('public')->delete($oldLogo);
        }

        if ($newCover && $oldCover) {
            Storage::disk('public')->delete($oldCover);
        }

        return redirect()->route('admin.companies.show', $company)
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->deleted_by = Auth::id();
        $company->save();
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company moved to trash.');
    }

    public function trash(Request $request): View
    {
        $query = Company::onlyTrashed()->with([
            'deleter:id,name',
            'companyType:id,name',
        ]);

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());

            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('legal_name', 'like', "%{$search}%");
            });
        }

        return view('admin.company.company.trash', [
            'companies' => $query->orderByDesc('deleted_at')->paginate(20)->withQueryString(),
            'search' => $request->input('search', ''),
            'trashCount' => Company::onlyTrashed()->count(),
        ]);
    }

    public function restore(int $id): RedirectResponse
    {
        $company = Company::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($company) {
            $company->restore();
            $company->forceFill([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ])->save();
        });

        return redirect()->route('admin.companies.trash')
            ->with('success', 'Company restored successfully.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $company = Company::onlyTrashed()->findOrFail($id);
        $logo = $company->logo;
        $cover = $company->cover_image;

        DB::transaction(fn () => $company->forceDelete());

        if ($logo) {
            Storage::disk('public')->delete($logo);
        }

        if ($cover) {
            Storage::disk('public')->delete($cover);
        }

        return redirect()->route('admin.companies.trash')
            ->with('success', 'Company permanently deleted.');
    }


    private function extractRelationships(array &$data): array
    {
        $fields = [
            'business_model_ids' => 'businessModels',
            'industry_ids' => 'industries',
            'technology_ids' => 'technologies',
            'country_ids' => 'countries',
            'state_ids' => 'states',
            'company_places' => 'companyPlaces',
        ];

        $relationships = [];

        foreach ($fields as $field => $relationship) {
            if (array_key_exists($field, $data)) {
                $relationships[$relationship] = $data[$field];
                unset($data[$field]);
            }
        }

        // Platform relationship fields
        foreach ([
            'platform_ids',
            'platform_urls',
            'platform_is_verified',
            'platform_last_verified_at',
        ] as $field) {
            if (array_key_exists($field, $data)) {
                $relationships[$field] = $data[$field];
                unset($data[$field]);
            }
        }

        return $relationships;
    }

    private function syncRelationships(Company $company, array $relationships): void
    {
        foreach ([
            'businessModels' => 'businessModels',
            'industries' => 'industries',
            'technologies' => 'technologies',
            'countries' => 'countries',
            'states' => 'states',
        ] as $key => $method) {
            if (array_key_exists($key, $relationships)) {
                $company->{$method}()->sync($relationships[$key] ?? []);
            }
        }

        // Sync platform profiles and pivot metadata
        if (array_key_exists('platform_ids', $relationships)) {
            $platformIds = collect($relationships['platform_ids'] ?? [])
                ->filter(fn ($id) => is_numeric($id) && (int) $id > 0)
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $platformUrls = $relationships['platform_urls'] ?? [];
            $platformVerification = $relationships['platform_is_verified'] ?? [];
            $platformVerifiedDates = $relationships['platform_last_verified_at'] ?? [];

            $syncData = [];

            foreach ($platformIds as $platformId) {
                $syncData[$platformId] = [
                    'profile_url' => $platformUrls[$platformId] ?? null,
                    'is_verified' => filter_var(
                        $platformVerification[$platformId] ?? false,
                        FILTER_VALIDATE_BOOLEAN
                    ),
                    'last_verified_at' => !empty($platformVerifiedDates[$platformId])
                        ? $platformVerifiedDates[$platformId]
                        : null,
                ];
            }

            $company->platforms()->sync($syncData);
        }

        // Sync company locations
        if (array_key_exists('companyPlaces', $relationships)) {
            $company->companyPlaces()->delete();

            foreach ($relationships['companyPlaces'] ?? [] as $place) {
                if (empty($place['city_id'])) {
                    continue;
                }

                $company->companyPlaces()->create([
                    'city_id' => $place['city_id'],
                    'place' => $place['place'] ?? null,
                ]);
            }
        }
    }

    private function formOptions(?Company $company = null): array
    {
        $parentCompanies = Company::query()
            ->where('status', 'active')
            ->when($company, fn ($query) => $query->where('id', '!=', $company->id))
            ->orderBy('name')
            ->get(['id', 'name']);

        return [
            'companySizes' => CompanySize::orderBy('sort_order')->orderBy('name')->get(['id', 'name']),
            'companyTypes' => CompanyType::orderBy('name')->get(['id', 'name']),
            'ownershipTypes' => OwnershipType::orderBy('name')->get(['id', 'name']),
            'businessModels' => BusinessModel::where('is_active', true)
                ->orderBy('sort_order')->orderBy('name')->get(['id', 'name']),
            'industries' => Industry::where('is_active', true)
                ->orderBy('name')->get(['id', 'name', 'parent_id']),
            'technologies' => Technology::where('is_active', true)
                ->orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'category']),
            'countries' => Country::where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'states' => State::where('is_active', true)->orderBy('name')->get(['id', 'country_id', 'name']),
            'cities' => City::where('is_active', true)
                ->orderBy('name')->get(['id', 'country_id', 'state_id', 'name']),
            'platforms' => Platform::orderBy('name')->get(['id', 'name', 'slug', 'base_url']),
            'parentCompanies' => $parentCompanies,
        ];
    }

    private function parseLines(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value ?? ''))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'company';
        $slug = $base;
        $counter = 1;

        while (
            Company::withTrashed()->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $counter++;
            $slug = "{$base}-{$counter}";
        }

        return $slug;
    }
}
