<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyTypeRequest;
use App\Models\CompanyType;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyTypeController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', CompanyType::class);

        $query = CompanyType::query()
            ->with([
                'createdBy:id,name,role',
                'updatedBy:id,name,role',
            ]);

        $search = $request->input('search');
        $status = $request->input('status');

        if (filled($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $companyTypes = $query
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
            'search',
            'status',
            'totalCompanyTypes',
            'activeCompanyTypes',
            'inactiveCompanyTypes',
            'trashedCompanyTypesCount'
        ));
    }
    
    public function create(): View
    {
        $this->authorize('create', CompanyType::class);

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
        $this->authorize('view', $companyType);

        $companyType->load([
            'createdBy:id,name',
            'updatedBy:id,name',
            'deletedBy:id,name',
        ]);

        return view('admin.company.company-type.show', compact('companyType'));
    }

    public function edit(CompanyType $companyType): View
    {
        $this->authorize('update', $companyType);

        return view('admin.company.company-type.edit', compact('companyType'));
    }

    public function update(
        CompanyTypeRequest $request,
        CompanyType $companyType
    ): RedirectResponse {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $companyType) {
            if ($companyType->name !== $validated['name']) {
                $validated['slug'] = $this->generateUniqueSlug(
                    $validated['name'],
                    $companyType->id
                );
            }

            $companyType->update([
                ...$validated,
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.company-types.index')
            ->with('success', 'Company type updated successfully.');
    }

    public function destroy(CompanyType $companyType): RedirectResponse
    {
        $this->authorize('delete', $companyType);

        DB::transaction(function () use ($companyType) {
            $companyType->update([
                'deleted_by' => Auth::id(),
            ]);

            $companyType->delete();
        });

        return redirect()
            ->route('admin.company-types.index')
            ->with('success', 'Company type moved to trash successfully.');
    }


    public function trash(Request $request): View
    {
        $this->authorize('viewAny', CompanyType::class);
        $search = trim($request->input('search', ''));
        $deletedBy = $request->input('deleted_by', '');
        $deletedPeriod = trim($request->input('deleted_period', ''));
        $companyTypes = CompanyType::onlyTrashed()
            ->with([
                'deletedBy:id,name,role',
                'createdBy:id,name',
                'updatedBy:id,name',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($deletedBy, function ($query) use ($deletedBy) {
                $query->where('deleted_by', $deletedBy);
            })
            ->when($deletedPeriod, function ($query) use ($deletedPeriod) {
                match ($deletedPeriod) {
                    'today' => $query->whereDate('deleted_at', today()),
                    'yesterday' => $query->whereDate('deleted_at', today()->subDay()),
                    'last_7_days' => $query->where('deleted_at', '>=', now()->subDays(7)),
                    'last_30_days' => $query->where('deleted_at', '>=', now()->subDays(30)),
                    'last_3_months' => $query->where('deleted_at', '>=', now()->subMonths(3)),
                    'this_year' => $query->whereYear('deleted_at', now()->year),
                    default => null,
                };
            })
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();
        $users = User::whereIn('id', CompanyType::onlyTrashed()->whereNotNull('deleted_by')->distinct()->pluck('deleted_by'))
            ->orderBy('name')
            ->get(['id', 'name', 'role']);
        $trashedCompanyTypesCount = CompanyType::onlyTrashed()->count();
        $deletedTodayCompanyTypes = CompanyType::onlyTrashed()->whereDate('deleted_at', today())->count();
        $deletedThisWeekCompanyTypes = CompanyType::onlyTrashed()->where('deleted_at', '>=', now()->subDays(7))->count();
        return view('admin.company.company-type.trash', compact(
            'companyTypes',
            'search',
            'deletedBy',
            'deletedPeriod',
            'users',
            'trashedCompanyTypesCount',
            'deletedTodayCompanyTypes',
            'deletedThisWeekCompanyTypes'
        ));
    }

    public function restore(CompanyType $companyType): RedirectResponse
    {
        $this->authorize('restore', $companyType);

        if (!$companyType->trashed()) {
            return redirect()
                ->route('admin.company-types.trash')
                ->with('error', 'This company type is not in the trash.');
        }

        DB::transaction(function () use ($companyType) {
            $companyType->restore();

            $companyType->forceFill([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ])->save();
        });

        return redirect()
            ->route('admin.company-types.trash')
            ->with('success', 'Company type restored successfully.');
    }

    /**
     * Permanently delete a trashed company type.
     */
    public function forceDelete(CompanyType $companyType): RedirectResponse
    {
        $this->authorize('forceDelete', $companyType);

        if (!$companyType->trashed()) {
            return redirect()
                ->route('admin.company-types.trash')
                ->with('error', 'Only trashed company types can be permanently deleted.');
        }

        DB::transaction(function () use ($companyType) {
            $companyType->forceDelete();
        });

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
