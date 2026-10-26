<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BusinessModelRequest;
use App\Models\BusinessModel;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BusinessModelController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request): View
    {
        $this->authorize('viewAny', BusinessModel::class);

        $query = BusinessModel::query();

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
                $request->input('status') === 'active'
            );
        }

        $businessModels = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.company.business-model.index', [
            'businessModels' => $businessModels,
            'search' => $request->input('search', ''),
            'status' => $request->input('status', ''),
            'totalBusinessModels' => BusinessModel::count(),
            'activeBusinessModels' => BusinessModel::where('is_active', true)->count(),
            'inactiveBusinessModels' => BusinessModel::where('is_active', false)->count(),
            'trashedBusinessModelsCount' => BusinessModel::onlyTrashed()->count(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', BusinessModel::class);

        return view('admin.company.business-model.create');
    }

    public function store(BusinessModelRequest $request): RedirectResponse
    {
        $this->authorize('create', BusinessModel::class);

        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        BusinessModel::create($data);

        return redirect()
            ->route('admin.business-models.index')
            ->with('success', 'Business model created successfully.');
    }

    public function show(BusinessModel $businessModel): View
    {
        $this->authorize('view', $businessModel);

        $businessModel->load([
            'creator:id,name',
            'updater:id,name',
        ]);

        return view('admin.company.business-model.show', compact('businessModel'));
    }

    public function edit(BusinessModel $businessModel): View
    {
        $this->authorize('update', $businessModel);

        return view('admin.company.business-model.edit', compact('businessModel'));
    }

    public function update(
        BusinessModelRequest $request,
        BusinessModel $businessModel
    ): RedirectResponse {
        $this->authorize('update', $businessModel);

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['updated_by'] = Auth::id();

        if ($businessModel->name !== $data['name']) {
            $data['slug'] = $this->generateUniqueSlug(
                $data['name'],
                $businessModel->id
            );
        }

        $businessModel->update($data);

        return redirect()
            ->route('admin.business-models.index')
            ->with('success', 'Business model updated successfully.');
    }

    public function destroy(BusinessModel $businessModel): RedirectResponse
    {
        $this->authorize('delete', $businessModel);

        $businessModel->deleted_by = Auth::id();
        $businessModel->save();
        $businessModel->delete();

        return redirect()
            ->route('admin.business-models.index')
            ->with('success', 'Business model moved to trash.');
    }

    public function trash(Request $request): View
    {
        $this->authorize('viewTrashed', BusinessModel::class);

        $query = BusinessModel::onlyTrashed()
            ->with('deleter:id,name');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $businessModels = $query
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.company.business-model.trash', [
            'businessModels' => $businessModels,
            'search' => $request->input('search', ''),
            'trashedBusinessModelsCount' => BusinessModel::onlyTrashed()->count(),
        ]);
    }

    public function restore(string $businessModel): RedirectResponse
    {
        $record = BusinessModel::onlyTrashed()
            ->where('slug', $businessModel)
            ->firstOrFail();

        $this->authorize('restore', $record);

        $record->restore();
        $record->deleted_by = null;
        $record->updated_by = Auth::id();
        $record->save();

        return redirect()
            ->route('admin.business-models.trash')
            ->with('success', 'Business model restored successfully.');
    }

    public function forceDelete(string $businessModel): RedirectResponse
    {
        $record = BusinessModel::onlyTrashed()
            ->where('slug', $businessModel)
            ->firstOrFail();

        $this->authorize('forceDelete', $record);

        $record->forceDelete();

        return redirect()
            ->route('admin.business-models.trash')
            ->with('success', 'Business model permanently deleted.');
    }

    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug ?: 'business-model';
        $counter = 1;

        while (
            BusinessModel::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = ($baseSlug ?: 'business-model') . '-' . $counter++;
        }

        return $slug;
    }
}