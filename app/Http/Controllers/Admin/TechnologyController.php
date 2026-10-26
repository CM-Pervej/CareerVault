<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TechnologyRequest;
use App\Models\Technology;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TechnologyController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Technology::class);

        $query = Technology::query();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->input('status') === 'active'
            );
        }

        $technologies = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $categories = Technology::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.reference.technology.index', [
            'technologies' => $technologies,
            'categories' => $categories,
            'search' => $request->input('search', ''),
            'category' => $request->input('category', ''),
            'status' => $request->input('status', ''),
            'totalTechnologies' => Technology::count(),
            'activeTechnologies' => Technology::where('is_active', true)->count(),
            'inactiveTechnologies' => Technology::where('is_active', false)->count(),
            'trashedTechnologies' => Technology::onlyTrashed()->count(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Technology::class);

        return view('admin.reference.technology.create');
    }

    public function store(TechnologyRequest $request): RedirectResponse
    {
        $this->authorize('create', Technology::class);

        $data = $request->validated();

        $data['slug'] = $this->generateUniqueSlug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        Technology::create($data);

        return redirect()
            ->route('admin.technology.index')
            ->with('success', 'Technology created successfully.');
    }

    public function show(Technology $technology): View
    {
        $this->authorize('view', $technology);

        $technology->load([
            'creator:id,name',
            'updater:id,name',
            'deleter:id,name',
        ]);

        return view('admin.reference.technology.show', compact('technology'));
    }

    public function edit(Technology $technology): View
    {
        $this->authorize('update', $technology);

        return view('admin.reference.technology.edit', compact('technology'));
    }

    public function update(
        TechnologyRequest $request,
        Technology $technology
    ): RedirectResponse {
        $this->authorize('update', $technology);

        $data = $request->validated();

        if ($technology->name !== $data['name']) {
            $data['slug'] = $this->generateUniqueSlug(
                $data['name'],
                $technology->id
            );
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['updated_by'] = Auth::id();

        $technology->update($data);

        return redirect()
            ->route('admin.technology.index')
            ->with('success', 'Technology updated successfully.');
    }

    public function destroy(Technology $technology): RedirectResponse
    {
        $this->authorize('delete', $technology);

        $technology->deleted_by = Auth::id();
        $technology->save();

        $technology->delete();

        return redirect()
            ->route('admin.technology.index')
            ->with('success', 'Technology moved to trash.');
    }

    public function trash(Request $request): View
    {
        $this->authorize('viewTrashed', Technology::class);

        $query = Technology::onlyTrashed()
            ->with('deleter:id,name');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $technologies = $query
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        $categories = Technology::onlyTrashed()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.reference.technology.trash', [
            'technologies' => $technologies,
            'categories' => $categories,
            'search' => $request->input('search', ''),
            'category' => $request->input('category', ''),
            'trashedTechnologies' => Technology::onlyTrashed()->count(),
        ]);
    }

    public function restore(string $id): RedirectResponse
    {
        $technology = Technology::onlyTrashed()
            ->whereKey($id)
            ->firstOrFail();

        $this->authorize('restore', $technology);

        $technology->restore();

        $technology->deleted_by = null;
        $technology->updated_by = Auth::id();
        $technology->save();

        return redirect()
            ->route('admin.technology.trash')
            ->with('success', 'Technology restored successfully.');
    }

    public function forceDelete(string $id): RedirectResponse
    {
        $technology = Technology::onlyTrashed()
            ->whereKey($id)
            ->firstOrFail();

        $this->authorize('forceDelete', $technology);

        $technology->forceDelete();

        return redirect()
            ->route('admin.technology.trash')
            ->with('success', 'Technology permanently deleted.');
    }

    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'technology';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            Technology::withTrashed()
                ->where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}