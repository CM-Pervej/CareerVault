<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyTypeRequest;
use App\Models\CompanyType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyTypeController extends Controller
{
    public function index(Request $request): View
    {
        $query = CompanyType::query()
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->status === 'active'
            );
        }

        $companyTypes = (clone $query)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $totalCompanyTypes = CompanyType::count();
        $activeCompanyTypes = CompanyType::where('is_active', true)->count();
        $inactiveCompanyTypes = CompanyType::where('is_active', false)->count();
        $trashedCompanyTypesCount = CompanyType::onlyTrashed()->count();

        return view('admin.company.company-type.index', compact(
            'companyTypes',
            'totalCompanyTypes',
            'activeCompanyTypes',
            'inactiveCompanyTypes',
            'trashedCompanyTypesCount'
        ));
    }

    public function create(): View
    {
        $companyType = new CompanyType([
            'is_active' => true,
            'sort_order' => 0,
        ]);

        return view('admin.company.company-type.create', compact('companyType'));
    }

    public function store(CompanyTypeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            CompanyType::create([
                ...$validated,
                'slug' => $this->generateUniqueSlug($validated['name']),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.company-types.index')
            ->with('success', 'Company type created successfully.');
    }

    public function show(CompanyType $companyType): View
    {
        $companyType->load([
            'createdBy:id,name',
            'updatedBy:id,name',
            'deletedBy:id,name',
        ]);

        return view('admin.company.company-type.show', compact('companyType'));
    }

    public function edit(CompanyType $companyType): View
    {
        return view('admin.company.company-type.edit', compact('companyType'));
    }

    public function update(
        CompanyTypeRequest $request,
        CompanyType $companyType
    ): RedirectResponse {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $companyType) {
            $data = [
                ...$validated,
                'updated_by' => Auth::id(),
            ];

            if ($companyType->name !== $validated['name']) {
                $data['slug'] = $this->generateUniqueSlug(
                    $validated['name'],
                    $companyType->id
                );
            }

            $companyType->update($data);
        });

        return redirect()
            ->route('admin.company-types.show', $companyType)
            ->with('success', 'Company type updated successfully.');
    }

    public function destroy(CompanyType $companyType): RedirectResponse
    {
        DB::transaction(function () use ($companyType) {
            $companyType->update([
                'deleted_by' => Auth::id(),
            ]);

            $companyType->delete();
        });

        return redirect()
            ->route('admin.company-types.index')
            ->with('success', 'Company type moved to trash.');
    }

    public function trash(Request $request): View
    {
        $query = CompanyType::onlyTrashed()
            ->with('deletedBy:id,name');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('deleted_period')) {
            $now = now();

            match ($request->deleted_period) {
                'today' => $query->whereDate('deleted_at', $now->toDateString()),
                'yesterday' => $query->whereDate(
                    'deleted_at',
                    $now->copy()->subDay()->toDateString()
                ),
                'last_7_days' => $query->where(
                    'deleted_at',
                    '>=',
                    $now->copy()->subDays(7)
                ),
                'last_30_days' => $query->where(
                    'deleted_at',
                    '>=',
                    $now->copy()->subDays(30)
                ),
                'last_3_months' => $query->where(
                    'deleted_at',
                    '>=',
                    $now->copy()->subMonths(3)
                ),
                'this_year' => $query->whereYear('deleted_at', $now->year),
                default => null,
            };
        }

        $companyTypes = $query
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        $trashedCompanyTypesCount = CompanyType::onlyTrashed()->count();

        return view('admin.company.company-type.trash', compact(
            'companyTypes',
            'trashedCompanyTypesCount'
        ));
    }

    public function restore(string $companyType): RedirectResponse
    {
        $record = CompanyType::onlyTrashed()
            ->where('id', $companyType)
            ->firstOrFail();

        DB::transaction(function () use ($record) {
            $record->restore();

            $record->update([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.company-types.trash')
            ->with('success', 'Company type restored successfully.');
    }

    public function forceDelete(string $companyType): RedirectResponse
    {
        $record = CompanyType::onlyTrashed()
            ->where('id', $companyType)
            ->firstOrFail();

        $record->forceDelete();

        return redirect()
            ->route('admin.company-types.trash')
            ->with('success', 'Company type permanently deleted.');
    }

    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'company-type';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            CompanyType::withTrashed()
                ->where('slug', $slug)
                ->when(
                    $ignoreId !== null,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $counter++;
            $slug = $baseSlug . '-' . $counter;
        }

        return $slug;
    }
}