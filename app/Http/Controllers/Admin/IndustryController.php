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
    public function index(Request $request)
    {
        $query = Industry::query()
            ->with('parent')
            ->withCount('children');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('parent')) {
            $query->where('parent_id', $request->input('parent'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status'));
        }

        $industries = $query
            ->orderByRaw('parent_id IS NOT NULL')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $parents = Industry::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.reference.industries.index', compact(
            'industries',
            'parents'
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
            'parent',
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
        $industries = Industry::onlyTrashed()
            ->with('parent')
            ->orderByDesc('deleted_at')
            ->paginate(20);

        return view('admin.reference.industries.trash', compact('industries'));
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