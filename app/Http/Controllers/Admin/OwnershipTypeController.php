<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OwnershipTypeRequest;
use App\Models\OwnershipType;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OwnershipTypeController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', OwnershipType::class);

        $query = OwnershipType::query()
            ->with([
                'creator:id,name,role',
                'updater:id,name,role',
            ]);

        $search = trim((string) $request->input('search', ''));
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

        $ownershipTypes = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => OwnershipType::count(),
            'active' => OwnershipType::where('is_active', true)->count(),
            'inactive' => OwnershipType::where('is_active', false)->count(),
            'trashed' => OwnershipType::onlyTrashed()->count(),
        ];

        return view(
            'admin.company.ownership-type.index',
            compact('ownershipTypes', 'stats', 'search', 'status')
        );
    }

    public function create(): View
    {
        $this->authorize('create', OwnershipType::class);

        $ownershipType = new OwnershipType([
            'is_active' => true,
            'sort_order' => 0,
        ]);

        return view(
            'admin.company.ownership-type.create',
            compact('ownershipType')
        );
    }

    public function store(OwnershipTypeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            OwnershipType::create([
                ...$validated,
                'slug' => $this->makeUniqueSlug(
                    $validated['slug'] ?: $validated['name']
                ),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.ownership-types.index')
            ->with('success', 'Ownership type created successfully.');
    }

    public function show(OwnershipType $ownershipType): View
    {
        $this->authorize('view', $ownershipType);

        $ownershipType->load([
            'creator:id,name',
            'updater:id,name',
            'deleter:id,name',
        ]);

        return view(
            'admin.company.ownership-type.show',
            compact('ownershipType')
        );
    }

    public function edit(OwnershipType $ownershipType): View
    {
        $this->authorize('update', $ownershipType);

        return view(
            'admin.company.ownership-type.edit',
            compact('ownershipType')
        );
    }

    public function update(
        OwnershipTypeRequest $request,
        OwnershipType $ownershipType
    ): RedirectResponse {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $ownershipType) {
            if ($ownershipType->name !== $validated['name']) {
                $validated['slug'] = $this->makeUniqueSlug(
                    $validated['slug'] ?: $validated['name'],
                    $ownershipType->id
                );
            }

            $ownershipType->update([
                ...$validated,
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.ownership-types.index')
            ->with('success', 'Ownership type updated successfully.');
    }

    public function destroy(
        OwnershipType $ownershipType
    ): RedirectResponse {
        $this->authorize('delete', $ownershipType);

        DB::transaction(function () use ($ownershipType) {
            $ownershipType->update([
                'deleted_by' => Auth::id(),
            ]);

            $ownershipType->delete();
        });

        return redirect()
            ->route('admin.ownership-types.index')
            ->with('success', 'Ownership type moved to trash successfully.');
    }

    public function trash(Request $request): View
    {
        $this->authorize('viewAny', OwnershipType::class);

        $search = trim($request->input('search', ''));
        $deletedBy = $request->input('deleted_by', '');
        $deletedPeriod = trim($request->input('deleted_period', ''));

        $ownershipTypes = OwnershipType::onlyTrashed()
            ->with([
                'deleter:id,name,role',
                'creator:id,name',
                'updater:id,name',
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

        $users = User::whereIn(
            'id',
            OwnershipType::onlyTrashed()
                ->whereNotNull('deleted_by')
                ->distinct()
                ->pluck('deleted_by')
        )
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        $stats = [
            'trashed' => OwnershipType::onlyTrashed()->count(),
            'deleted_today' => OwnershipType::onlyTrashed()
                ->whereDate('deleted_at', today())
                ->count(),
            'deleted_this_week' => OwnershipType::onlyTrashed()
                ->where('deleted_at', '>=', now()->subDays(7))
                ->count(),
        ];

        return view(
            'admin.company.ownership-type.trash',
            compact(
                'ownershipTypes',
                'search',
                'deletedBy',
                'deletedPeriod',
                'users',
                'stats'
            )
        );
    }

    public function restore(OwnershipType $ownershipType): RedirectResponse
    {
        $this->authorize('restore', $ownershipType);

        if (!$ownershipType->trashed()) {
            return redirect()
                ->route('admin.ownership-types.trash')
                ->with('error', 'This ownership type is not in the trash.');
        }

        DB::transaction(function () use ($ownershipType) {
            $ownershipType->restore();

            $ownershipType->forceFill([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ])->save();
        });

        return redirect()
            ->route('admin.ownership-types.trash')
            ->with('success', 'Ownership type restored successfully.');
    }

    /**
     * Permanently delete a trashed ownership type.
     */
    public function forceDelete(OwnershipType $ownershipType): RedirectResponse
    {
        $this->authorize('forceDelete', $ownershipType);

        if (!$ownershipType->trashed()) {
            return redirect()
                ->route('admin.ownership-types.trash')
                ->with('error', 'Only trashed ownership types can be permanently deleted.');
        }

        DB::transaction(function () use ($ownershipType) {
            $ownershipType->forceDelete();
        });

        return redirect()
            ->route('admin.ownership-types.trash')
            ->with('success', 'Ownership type permanently deleted.');
    }

    private function makeUniqueSlug(
        string $value,
        ?int $ignoreId = null
    ): string {
        $base = Str::slug($value);

        if ($base === '') {
            $base = 'ownership-type';
        }

        $slug = $base;
        $counter = 1;

        while (
            OwnershipType::withTrashed()
                ->where('slug', $slug)
                ->when(
                    $ignoreId !== null,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $counter++;
            $slug = $base . '-' . $counter;
        }

        return $slug;
    }
}