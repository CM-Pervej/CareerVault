<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TechnologyRequest;
use App\Models\TechCategory;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TechnologyController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Technology::class);
        $search = trim($request->input('search', ''));
        $category = $request->input('category', '');
        $status = $request->input('status', '');
        $technologies = Technology::query()
            ->with([
                'category:id,name,slug',
                'createdBy:id,name,role',
                'updatedBy:id,name,role',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($category !== '', function ($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
        $categories = TechCategory::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);
        $totalTechnologies = Technology::count();
        $activeTechnologies = Technology::where('is_active', true)->count();
        $inactiveTechnologies = Technology::where('is_active', false)->count();
        $trashedTechnologiesCount = Technology::onlyTrashed()->count();
        return view('admin.reference.technology.index', compact(
            'technologies',
            'categories',
            'search',
            'category',
            'status',
            'totalTechnologies',
            'activeTechnologies',
            'inactiveTechnologies',
            'trashedTechnologiesCount',
        ));
    }

    public function create(): View
    {
        $this->authorize('create', Technology::class);
        $categories = TechCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);
        return view('admin.reference.technology.create', compact('categories'));
    }

    public function store(TechnologyRequest $request): RedirectResponse
    {
        $this->authorize('create', Technology::class);
        $data = $request->validated();
        DB::transaction(function () use ($request, $data) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
            $data['is_active'] = $request->boolean('is_active');
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
            Technology::create($data);
        });
        return redirect()->route('admin.technology.index')->with('success', 'Technology created successfully.');
    }

    public function show(Technology $technology): View
    {
        $this->authorize('view', $technology);
        $technology->load([
            'category:id,name,slug',
            'createdBy:id,name',
            'updatedBy:id,name',
            'deletedBy:id,name',
        ]);
        return view('admin.reference.technology.show', compact('technology'));
    }

    public function edit(Technology $technology): View
    {
        $this->authorize('update', $technology);
        $categories = TechCategory::where('is_active', true)
            ->orWhere('id', $technology->category_id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);
        return view('admin.reference.technology.edit', compact('technology', 'categories'));
    }

    public function update(TechnologyRequest $request, Technology $technology): RedirectResponse
    {
        $this->authorize('update', $technology);
        $data = $request->validated();
        DB::transaction(function () use ($request, $technology, $data) {
            if ($technology->name !== $data['name']) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $technology->id);
            }
            $data['is_active'] = $request->boolean('is_active');
            $data['updated_by'] = Auth::id();
            $technology->update($data);
        });
        return redirect()->route('admin.technology.show', $technology)->with('success', 'Technology updated successfully.');
    }

    public function destroy(Technology $technology): RedirectResponse
    {
        $this->authorize('delete', $technology);
        DB::transaction(function () use ($technology) {
            $technology->update([
                'deleted_by' => Auth::id(),
            ]);
            $technology->delete();
        });
        return redirect()->route('admin.technology.index')->with('success', 'Technology moved to trash successfully.');
    }

    public function trash(Request $request): View
    {
        $this->authorize('viewAny', Technology::class);
        $search = trim($request->input('search', ''));
        $category = $request->input('category', '');
        $deletedBy = $request->input('deleted_by', '');
        $deletedPeriod = trim($request->input('deleted_period', ''));
        $technologies = Technology::onlyTrashed()
            ->with([
                'category:id,name,slug',
                'createdBy:id,name,role',
                'updatedBy:id,name,role',
                'deletedBy:id,name,role',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($category !== '', function ($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->when($deletedBy !== '', function ($query) use ($deletedBy) {
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
        $categories = TechCategory::withTrashed()
            ->whereIn('id', Technology::onlyTrashed()->whereNotNull('category_id')->distinct()->pluck('category_id'))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);
        $users = User::whereIn('id', Technology::onlyTrashed()->whereNotNull('deleted_by')->distinct()->pluck('deleted_by'))
            ->orderBy('name')
            ->get(['id', 'name', 'role']);
        $trashedTechnologiesCount = Technology::onlyTrashed()->count();
        $deletedTodayTechnologies = Technology::onlyTrashed()->whereDate('deleted_at', today())->count();
        $deletedThisWeekTechnologies = Technology::onlyTrashed()->where('deleted_at', '>=', now()->subDays(7))->count();
        return view('admin.reference.technology.trash', compact(
            'technologies',
            'categories',
            'search',
            'category',
            'deletedBy',
            'deletedPeriod',
            'users',
            'trashedTechnologiesCount',
            'deletedTodayTechnologies',
            'deletedThisWeekTechnologies',
        ));
    }

    public function restore(string $technology): RedirectResponse
    {
        $record = Technology::onlyTrashed()
            ->where('slug', $technology)
            ->firstOrFail();
        $this->authorize('restore', $record);
        DB::transaction(function () use ($record) {
            $record->restore();
            $record->forceFill([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ])->save();
        });
        return redirect()->route('admin.technology.trash')->with('success', 'Technology restored successfully.');
    }

    public function forceDelete(string $technology): RedirectResponse
    {
        $record = Technology::onlyTrashed()
            ->where('slug', $technology)
            ->firstOrFail();
        $this->authorize('forceDelete', $record);
        DB::transaction(function () use ($record) {
            $record->forceDelete();
        });
        return redirect()->route('admin.technology.trash')->with('success', 'Technology permanently deleted.');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug ?: 'technology';
        $slug = $baseSlug;
        $counter = 1;
        while (
            Technology::withTrashed()
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