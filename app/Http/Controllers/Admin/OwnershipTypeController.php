<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OwnershipTypeRequest;
use App\Models\OwnershipType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OwnershipTypeController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', OwnershipType::class);

        $query = OwnershipType::query();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->query('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->query('status') === 'inactive') {
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
            compact('ownershipTypes', 'stats')
        );
    }

    public function create(): View
    {
        $this->authorize('create', OwnershipType::class);

        return view('admin.company.ownership-type.create');
    }

    public function store(OwnershipTypeRequest $request): RedirectResponse
    {
        $this->authorize('create', OwnershipType::class);

        $data = $request->validated();

        $data['slug'] = $this->makeUniqueSlug(
            $data['slug'] ?: $data['name']
        );

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        OwnershipType::create($data);

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
        $this->authorize('update', $ownershipType);

        $data = $request->validated();

        $data['slug'] = $this->makeUniqueSlug(
            $data['slug'] ?: $data['name'],
            $ownershipType->id
        );

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['updated_by'] = Auth::id();

        $ownershipType->update($data);

        return redirect()
            ->route('admin.ownership-types.index')
            ->with('success', 'Ownership type updated successfully.');
    }

    public function destroy(
        OwnershipType $ownershipType
    ): RedirectResponse {
        $this->authorize('delete', $ownershipType);

        $ownershipType->deleted_by = Auth::id();
        $ownershipType->save();

        $ownershipType->delete();

        return redirect()
            ->route('admin.ownership-types.index')
            ->with('success', 'Ownership type moved to trash.');
    }

    public function trash(Request $request): View
    {
        $this->authorize('viewAny', OwnershipType::class);

        $query = OwnershipType::onlyTrashed()
            ->with('deleter:id,name');

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $ownershipTypes = $query
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.company.ownership-type.trash',
            compact('ownershipTypes')
        );
    }

    public function restore(int $id): RedirectResponse
    {
        $ownershipType = OwnershipType::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $ownershipType);

        $ownershipType->restore();
        $ownershipType->deleted_by = null;
        $ownershipType->updated_by = Auth::id();
        $ownershipType->save();

        return redirect()
            ->route('admin.ownership-types.trash')
            ->with('success', 'Ownership type restored successfully.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $ownershipType = OwnershipType::onlyTrashed()->findOrFail($id);

        $this->authorize('forceDelete', $ownershipType);

        $ownershipType->forceDelete();

        return redirect()
            ->route('admin.ownership-types.trash')
            ->with('success', 'Ownership type permanently deleted.');
    }

    private function makeUniqueSlug(
        string $value,
        ?int $ignoreId = null
    ): string {
        $base = Str::slug($value) ?: 'ownership-type';
        $slug = $base;
        $counter = 2;

        while (
            OwnershipType::withTrashed()
                ->where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}