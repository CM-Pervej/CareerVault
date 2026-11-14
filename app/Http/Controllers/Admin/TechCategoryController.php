<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TechCategoryRequest;
use App\Models\TechCategory;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TechCategoryController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', TechCategory::class);

        $search = trim($request->input('search', ''));
        $status = $request->input('status');

        $techCategories = TechCategory::query()
            ->with([
                'createdBy:id,name,role',
                'updatedBy:id,name,role',
            ])
            ->withCount('technologies')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $totalTechCategories = TechCategory::count();
        $activeTechCategories = TechCategory::where('is_active', true)->count();
        $inactiveTechCategories = TechCategory::where('is_active', false)->count();
        $trashedTechCategories = TechCategory::onlyTrashed()->count();

        return view('admin.reference.tech-category.index', compact(
            'techCategories',
            'search',
            'status',
            'totalTechCategories',
            'activeTechCategories',
            'inactiveTechCategories',
            'trashedTechCategories',
        ));
    }

    public function create(): View
    {
        $this->authorize('create', TechCategory::class);

        return view('admin.reference.tech-category.create');
    }

    public function store(TechCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            TechCategory::create([
                ...$validated,
                'slug' => $this->generateUniqueSlug($validated['name']),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.tech-categories.index')
            ->with('success', 'Technology category created successfully.');
    }

    public function show(TechCategory $techCategory): View
    {
        $this->authorize('view', $techCategory);

        $techCategory->load([
            'createdBy:id,name,role',
            'updatedBy:id,name,role',
            'deletedBy:id,name,role',
        ])->loadCount('technologies');

        return view('admin.reference.tech-category.show', compact('techCategory'));
    }

    public function edit(TechCategory $techCategory): View
    {
        $this->authorize('update', $techCategory);

        return view('admin.reference.tech-category.edit', compact('techCategory'));
    }

    public function update(TechCategoryRequest $request, TechCategory $techCategory): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($techCategory, $validated) {
            if ($techCategory->name !== $validated['name']) {
                $validated['slug'] = $this->generateUniqueSlug(
                    $validated['name'],
                    $techCategory->id
                );
            }

            $techCategory->update([
                ...$validated,
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.tech-categories.show', $techCategory)
            ->with('success', 'Technology category updated successfully.');
    }

    public function destroy(TechCategory $techCategory): RedirectResponse
    {
        $this->authorize('delete', $techCategory);

        DB::transaction(function () use ($techCategory) {
            $techCategory->update([
                'deleted_by' => Auth::id(),
            ]);

            $techCategory->delete();
        });

        return redirect()
            ->route('admin.tech-categories.index')
            ->with('success', 'Technology category moved to trash successfully.');
    }

    public function trash(Request $request): View
    {
        $this->authorize('viewAny', TechCategory::class);

        $search = trim($request->input('search', ''));
        $deletedBy = $request->input('deleted_by', '');
        $deletedPeriod = trim($request->input('deleted_period', ''));

        $techCategories = TechCategory::onlyTrashed()
            ->with([
                'deletedBy:id,name,role',
                'createdBy:id,name',
                'updatedBy:id,name',
            ])
            ->withCount('technologies')
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

        $users = User::whereIn(
            'id',
            TechCategory::onlyTrashed()
                ->whereNotNull('deleted_by')
                ->distinct()
                ->pluck('deleted_by')
        )
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        $totalTrashedTechCategories = TechCategory::onlyTrashed()->count();

        $deletedTodayTechCategories = TechCategory::onlyTrashed()
            ->whereDate('deleted_at', today())
            ->count();

        $deletedThisWeekTechCategories = TechCategory::onlyTrashed()
            ->where('deleted_at', '>=', now()->subDays(7))
            ->count();

        return view('admin.reference.tech-category.trash', compact(
            'techCategories',
            'search',
            'deletedBy',
            'deletedPeriod',
            'users',
            'totalTrashedTechCategories',
            'deletedTodayTechCategories',
            'deletedThisWeekTechCategories',
        ));
    }

    public function restore(string $techCategory): RedirectResponse
    {
        $techCategory = TechCategory::onlyTrashed()
            ->where('slug', $techCategory)
            ->firstOrFail();

        $this->authorize('restore', $techCategory);

        DB::transaction(function () use ($techCategory) {
            $techCategory->restore();

            $techCategory->forceFill([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ])->save();
        });

        return redirect()
            ->route('admin.tech-categories.trash')
            ->with('success', 'Technology category restored successfully.');
    }

    public function forceDelete(string $techCategory): RedirectResponse
    {
        $techCategory = TechCategory::onlyTrashed()
            ->where('slug', $techCategory)
            ->firstOrFail();

        $this->authorize('forceDelete', $techCategory);

        DB::transaction(function () use ($techCategory) {
            $techCategory->forceDelete();
        });

        return redirect()
            ->route('admin.tech-categories.trash')
            ->with('success', 'Technology category permanently deleted.');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'tech-category';

        $slug = $baseSlug;
        $counter = 1;

        while (
            TechCategory::withTrashed()
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