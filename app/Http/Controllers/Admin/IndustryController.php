<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IndustryController extends Controller
{
    public function index()
    {
        $search = trim(request()->input('search', ''));
        $parent = trim(request()->input('parent', ''));
        $status = request()->input('status');

        // Industries
        $industries = Industry::query()
            ->with('parent')
            ->withCount('children')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($parent !== '', function ($query) use ($parent) {
                $query->where('parent_id', $parent);
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderByRaw('parent_id IS NOT NULL')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        // Parent Industries
        $parents = Industry::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Summary Statistics
        $stats = Industry::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(is_active = 1) as active')
            ->selectRaw('SUM(is_active = 0) as inactive')
            ->selectRaw('SUM(parent_id IS NULL) as parents')
            ->selectRaw('SUM(parent_id IS NOT NULL) as sub_industries')
            ->first();

        $totalIndustries = (int) $stats->total;
        $activeIndustries = (int) $stats->active;
        $inactiveIndustries = (int) $stats->inactive;
        $parentIndustries = (int) $stats->parents;
        $subIndustries = (int) $stats->sub_industries;

        $industriesWithChildren = Industry::query()
            ->has('children')
            ->count();

        $leafIndustries = Industry::query()
            ->doesntHave('children')
            ->count();

        $recentlyAdded = Industry::query()
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $recentlyUpdated = Industry::query()
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

        $trashedIndustries = Industry::onlyTrashed()->count();

        return view('admin.reference.industries.index', compact(
            'industries',
            'parents',
            'search',
            'parent',
            'status',
            'totalIndustries',
            'activeIndustries',
            'inactiveIndustries',
            'parentIndustries',
            'subIndustries',
            'industriesWithChildren',
            'leafIndustries',
            'recentlyAdded',
            'recentlyUpdated',
            'trashedIndustries',
        ));
    }

    public function create()
    {
        $parents = Industry::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.reference.industries.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateIndustry($request);

        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $validated['created_by'] = Auth::id();

        Industry::create($validated);

        return redirect()
            ->route('admin.industries.index')
            ->with('success', 'Industry created successfully.');
    }

    public function show(Industry $industry)
    {
        $industry->load([
            'parent' => fn ($query) => $query->withTrashed(),
            'children' => fn ($query) => $query
                ->orderBy('sort_order')
                ->orderBy('name'),
        ]);

        return view('admin.reference.industries.show', compact('industry'));
    }

    public function edit(Industry $industry)
    {
        $parents = Industry::query()
            ->whereNull('parent_id')
            ->where('id', '!=', $industry->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.reference.industries.edit', compact(
            'industry',
            'parents'
        ));
    }

    public function update(Request $request, Industry $industry)
    {
        $validated = $this->validateIndustry($request, $industry);

        if (
            isset($validated['parent_id']) &&
            $validated['parent_id'] == $industry->id
        ) {
            return back()
                ->withErrors(['parent_id' => 'An industry cannot be its own parent.'])
                ->withInput();
        }

        if ($validated['name'] !== $industry->name) {
            $validated['slug'] = $this->generateUniqueSlug(
                $validated['name'],
                $industry->id
            );
        }

        $validated['updated_by'] = Auth::id();

        $industry->update($validated);

        return redirect()
            ->route('admin.industries.index')
            ->with('success', 'Industry updated successfully.');
    }

    public function destroy(Industry $industry)
    {
        if ($industry->children()->exists()) {
            return back()->with('error', 'Remove or reassign its sub-industries before deleting this industry.');
        }

        $industry->deleted_by = Auth::id();
        $industry->save();
        $industry->delete();

        return redirect()
            ->route('admin.industries.index')
            ->with('success', 'Industry moved to trash.');
    }

    public function trash()
    {
        $search = trim(request()->input('search', ''));
        $parent = trim(request()->input('parent', ''));
        $deletedBy = trim(request()->input('deleted_by', ''));
        $deletedPeriod = trim(request()->input('deleted_period', ''));

        // Trashed Industries
        $industries = Industry::onlyTrashed()
            ->with([
                'parent' => fn ($query) => $query->withTrashed(),
                'deletedBy:id,name,slug,role',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($parent !== '', function ($query) use ($parent) {
                $query->where('parent_id', $parent);
            })
            ->when($deletedBy !== '', function ($query) use ($deletedBy) {
                $query->where('deleted_by', $deletedBy);
            })
            ->when($deletedPeriod !== '', function ($query) use ($deletedPeriod) {
                match ($deletedPeriod) {
                    'today' => $query->whereDate('deleted_at', today()),

                    'yesterday' => $query->whereDate(
                        'deleted_at',
                        today()->subDay()
                    ),

                    'last_7_days' => $query->where(
                        'deleted_at',
                        '>=',
                        now()->subDays(7)
                    ),

                    'last_30_days' => $query->where(
                        'deleted_at',
                        '>=',
                        now()->subDays(30)
                    ),

                    'last_3_months' => $query->where(
                        'deleted_at',
                        '>=',
                        now()->subMonths(3)
                    ),

                    'this_year' => $query->whereYear(
                        'deleted_at',
                        now()->year
                    ),

                    default => null,
                };
            })
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        // Parent Industries
        $parents = Industry::withTrashed()
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Users Who Deleted Industries
        $deletedByUsers = \App\Models\User::query()
            ->whereIn(
                'id',
                Industry::onlyTrashed()
                    ->whereNotNull('deleted_by')
                    ->distinct()
                    ->pluck('deleted_by')
            )
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
                'role',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Summary Statistics
        |--------------------------------------------------------------------------
        */

        $trashedIndustries = Industry::onlyTrashed()->count();

        $recentlyDeleted = Industry::onlyTrashed()
            ->where('deleted_at', '>=', now()->subDays(30))
            ->count();

        $deletedToday = Industry::onlyTrashed()
            ->whereDate('deleted_at', today())
            ->count();

        $deletedThisMonth = Industry::onlyTrashed()
            ->whereYear('deleted_at', now()->year)
            ->whereMonth('deleted_at', now()->month)
            ->count();

        $deletedThisYear = Industry::onlyTrashed()
            ->whereYear('deleted_at', now()->year)
            ->count();

        $trashedWithParent = Industry::onlyTrashed()
            ->whereNotNull('parent_id')
            ->count();

        $trashedTopLevel = Industry::onlyTrashed()
            ->whereNull('parent_id')
            ->count();

        $deletedByUsersCount = Industry::onlyTrashed()
            ->whereNotNull('deleted_by')
            ->distinct('deleted_by')
            ->count('deleted_by');

        return view('admin.reference.industries.trash', compact(
            'industries',
            'parents',
            'deletedByUsers',
            'search',
            'parent',
            'deletedBy',
            'deletedPeriod',
            'trashedIndustries',
            'recentlyDeleted',
            'deletedToday',
            'deletedThisMonth',
            'deletedThisYear',
            'trashedWithParent',
            'trashedTopLevel',
            'deletedByUsersCount',
        ));
    }

    public function restore(Industry $industry)
    {
        $industry->restore();

        $industry->deleted_by = null;
        $industry->save();

        return redirect()
            ->route('admin.industries.trash')
            ->with('success', 'Industry restored successfully.');
    }

    public function forceDelete(Industry $industry)
    {
        if ($industry->children()->withTrashed()->exists()) {
            return back()->with('error', 'This industry still has sub-industries and cannot be permanently deleted.');
        }

        $industry->forceDelete();

        return redirect()
            ->route('admin.industries.trash')
            ->with('success', 'Industry permanently deleted.');
    }

    private function validateIndustry(
        Request $request,
        ?Industry $industry = null
    ): array {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                $industry
                    ? Rule::unique('industries', 'name')->ignore($industry->id)
                    : Rule::unique('industries', 'name'),
            ],
            'description' => ['nullable', 'string'],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:industries,id',
            ],
            'icon' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 2;

        while (
            Industry::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}