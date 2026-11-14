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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BusinessModelController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', BusinessModel::class);

        $search = trim($request->input('search', ''));
        $status = $request->input('status');

        $businessModels = BusinessModel::query()
            ->with([
                'creator:id,name,role',
                'updater:id,name,role',
            ])
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

        // Summary Statistics
        $totalBusinessModels = BusinessModel::count();
        $activeBusinessModels = BusinessModel::where('is_active', true)->count();
        $inactiveBusinessModels = BusinessModel::where('is_active', false)->count();
        $trashedBusinessModelsCount = BusinessModel::onlyTrashed()->count();

        return view('admin.company.business-model.index', compact(
            'businessModels',
            'search',
            'status',
            'totalBusinessModels',
            'activeBusinessModels',
            'inactiveBusinessModels',
            'trashedBusinessModelsCount',
        ));
    }

    public function create(): View
    {
        $this->authorize('create', BusinessModel::class);

        return view('admin.company.business-model.create');
    }

    public function store(BusinessModelRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($request, $data) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
            $data['is_active'] = $request->boolean('is_active');
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();

            BusinessModel::create($data);
        });

        return redirect()->route('admin.business-models.index')->with('success', 'Business model created successfully.');
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
        $data = $request->validated();

        DB::transaction(function () use ($request, $businessModel, $data) {
            if ($businessModel->name !== $data['name']) {
                $data['slug'] = $this->generateUniqueSlug(
                    $data['name'],
                    $businessModel->id
                );
            }

            $data['is_active'] = $request->boolean('is_active');
            $data['updated_by'] = Auth::id();

            $businessModel->update($data);
        });

        return redirect()->route('admin.business-models.show', $businessModel)->with('success', 'Business model updated successfully.');
    }

    public function destroy(BusinessModel $businessModel): RedirectResponse
    {
        $this->authorize('delete', $businessModel);

        DB::transaction(function () use ($businessModel) {
            $businessModel->update([
                'deleted_by' => Auth::id(),
            ]);

            $businessModel->delete();
        });

        return redirect()->route('admin.business-models.index')->with('success', 'Business model moved to trash successfully.');
    }

    public function trash(Request $request): View
    {
        $this->authorize('viewAny', BusinessModel::class);

        $search = trim($request->input('search', ''));
        $deletedBy = $request->input('deleted_by', '');
        $deletedPeriod = trim($request->input('deleted_period', ''));

        $businessModels = BusinessModel::onlyTrashed()
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

        $users = User::whereIn('id', BusinessModel::onlyTrashed()->whereNotNull('deleted_by')->distinct()->pluck('deleted_by'))
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        $trashedBusinessModelsCount = BusinessModel::onlyTrashed()->count();
        $deletedTodayBusinessModels = BusinessModel::onlyTrashed()->whereDate('deleted_at', today())->count();
        $deletedThisWeekBusinessModels = BusinessModel::onlyTrashed()->where('deleted_at', '>=', now()->subDays(7))->count();

        return view('admin.company.business-model.trash', compact(
            'businessModels',
            'search',
            'deletedBy',
            'deletedPeriod',
            'users',
            'trashedBusinessModelsCount',
            'deletedTodayBusinessModels',
            'deletedThisWeekBusinessModels',
        ));
    }

    public function restore(string $businessModel): RedirectResponse
    {
        $record = BusinessModel::onlyTrashed()
            ->where('slug', $businessModel)
            ->firstOrFail();

        $this->authorize('restore', $record);

        DB::transaction(function () use ($record) {
            $record->restore();

            $record->forceFill([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ])->save();
        });

        return redirect()->route('admin.business-models.trash')->with('success', 'Business model restored successfully.');
    }

    /**
     * Permanently delete a trashed business model.
     */
    public function forceDelete(string $businessModel): RedirectResponse
    {
        $record = BusinessModel::onlyTrashed()
            ->where('slug', $businessModel)
            ->firstOrFail();

        $this->authorize('forceDelete', $record);

        DB::transaction(function () use ($record) {
            $record->forceDelete();
        });

        return redirect()->route('admin.business-models.trash')->with('success', 'Business model permanently deleted.');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug ?: 'business-model';

        $slug = $baseSlug;
        $counter = 1;

        while (
            BusinessModel::withTrashed()
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