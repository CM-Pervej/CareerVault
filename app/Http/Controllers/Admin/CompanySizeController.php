<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanySizeRequest;
use App\Models\CompanySize;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanySizeController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display all company sizes.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', CompanySize::class);

        $search = $request->input('search');
        $status = $request->input('status');

        $query = CompanySize::query()
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
            // ->withCount('companies');

        if ($search) {
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

        $companySizes = $query
            ->orderBy('sort_order')
            ->orderBy('min_employees')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'totalCompanySizes' => CompanySize::count(),
            'activeCompanySizes' => CompanySize::where('is_active', true)->count(),
            'inactiveCompanySizes' => CompanySize::where('is_active', false)->count(),
            'trashedCompanySizes' => CompanySize::onlyTrashed()->count(),
        ];

        return view('admin.company.company-size.index', compact(
            'companySizes',
            'search',
            'status',
            'stats'
        ));
    }

    /**
     * Show the create form.
     */
    public function create(): View
    {
        $this->authorize('create', CompanySize::class);

        return view('admin.company.company-size.create');
    }

    /**
     * Store a new company size.
     */
    public function store(CompanySizeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            CompanySize::create([
                ...$validated,
                'slug' => $this->generateUniqueSlug($validated['name']),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.company-sizes.index')
            ->with('success', 'Company size created successfully.');
    }

    /**
     * Display a company size.
     */
    public function show(CompanySize $companySize): View
    {
        $this->authorize('view', $companySize);

        $companySize->load([
            'createdBy:id,name',
            'updatedBy:id,name',
        ]);

        return view('admin.company.company-size.show', compact(
            'companySize'
        ));
    }

    /**
     * Show the edit form.
     */
    public function edit(CompanySize $companySize): View
    {
        $this->authorize('update', $companySize);

        return view('admin.company.company-size.edit', compact(
            'companySize'
        ));
    }

    /**
     * Update a company size.
     */
    public function update(
        CompanySizeRequest $request,
        CompanySize $companySize
    ): RedirectResponse {
        $validated = $request->validated();

        DB::transaction(function () use ($companySize, $validated) {
            if ($companySize->name !== $validated['name']) {
                $validated['slug'] = $this->generateUniqueSlug(
                    $validated['name'],
                    $companySize->id
                );
            }

            $companySize->update([
                ...$validated,
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()->route('admin.company-sizes.index')
            ->with('success', 'Company size updated successfully.');
    }

    /**
     * Move a company size to trash.
     */
    public function destroy(CompanySize $companySize): RedirectResponse
    {
        $this->authorize('delete', $companySize);

        DB::transaction(function () use ($companySize) {
            $companySize->update([
                'deleted_by' => Auth::id(),
            ]);

            $companySize->delete();
        });

        return redirect()
            ->route('admin.company-sizes.index')
            ->with('success', 'Company size moved to trash successfully.');
    }

    /**
     * Display trashed company sizes.
     */
    public function trash(Request $request): View
    {
        $this->authorize('viewAny', CompanySize::class);

        $search = $request->input('search');
        $deletedBy = $request->input('deleted_by');
        $deletedPeriod = $request->input('deleted_period');

        $query = CompanySize::onlyTrashed()
            ->with([
                'deletedBy:id,name,role',
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
            // ->withCount('companies');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($deletedBy) {
            $query->where('deleted_by', $deletedBy);
        }

        if ($deletedPeriod) {
            $now = now();

            switch ($deletedPeriod) {
                case 'today':
                    $query->whereDate('deleted_at', $now->toDateString());
                    break;

                case 'yesterday':
                    $query->whereDate(
                        'deleted_at',
                        $now->copy()->subDay()->toDateString()
                    );
                    break;

                case 'last_7_days':
                    $query->whereBetween('deleted_at', [
                        $now->copy()->subDays(7),
                        $now,
                    ]);
                    break;

                case 'last_30_days':
                    $query->whereBetween('deleted_at', [
                        $now->copy()->subDays(30),
                        $now,
                    ]);
                    break;

                case 'last_3_months':
                    $query->whereBetween('deleted_at', [
                        $now->copy()->subMonths(3),
                        $now,
                    ]);
                    break;

                case 'this_year':
                    $query->whereYear('deleted_at', $now->year);
                    break;
            }
        }

        $companySizes = $query
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        $users = User::query()
            ->whereIn(
                'id',
                CompanySize::onlyTrashed()
                    ->whereNotNull('deleted_by')
                    ->select('deleted_by')
                    ->distinct()
            )
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        $stats = [
            'totalTrashed' => CompanySize::onlyTrashed()->count(),

            'deletedToday' => CompanySize::onlyTrashed()
                ->whereDate('deleted_at', today())
                ->count(),

            'deletedThisWeek' => CompanySize::onlyTrashed()
                ->whereBetween('deleted_at', [
                    now()->subDays(7),
                    now(),
                ])
                ->count(),
        ];

        return view('admin.company.company-size.trash', compact(
            'companySizes',
            'search',
            'deletedBy',
            'deletedPeriod',
            'users',
            'stats'
        ));
    }

    /**
     * Restore a trashed company size.
     */
    public function restore(CompanySize $companySize): RedirectResponse
    {
        $this->authorize('restore', $companySize);

        if (!$companySize->trashed()) {
            return redirect()
                ->route('admin.company-sizes.trash')
                ->with('error', 'This company size is not in the trash.');
        }

        DB::transaction(function () use ($companySize) {
            $companySize->restore();

            $companySize->forceFill([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ])->save();
        });

        return redirect()
            ->route('admin.company-sizes.trash')
            ->with('success', 'Company size restored successfully.');
    }

    /**
     * Permanently delete a trashed company size.
     */
    public function forceDelete(CompanySize $companySize): RedirectResponse
    {
        $this->authorize('forceDelete', $companySize);

        if (!$companySize->trashed()) {
            return redirect()
                ->route('admin.company-sizes.trash')
                ->with('error', 'Only trashed company sizes can be permanently deleted.');
        }

        // if ($companySize->companies()->exists()) {
        //     return back()->with(
        //         'error',
        //         'This company size cannot be permanently deleted because it is associated with companies.'
        //     );
        // }

        DB::transaction(function () use ($companySize) {
            $companySize->forceDelete();
        });

        return redirect()
            ->route('admin.company-sizes.trash')
            ->with('success', 'Company size permanently deleted.');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            CompanySize::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $counter++;
            $slug = $baseSlug . '-' . $counter;
        }

        return $slug;
    }
}