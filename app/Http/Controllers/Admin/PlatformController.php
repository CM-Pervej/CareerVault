<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlatformRequest;
use App\Models\Platform;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlatformController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Platform::class);

        $search = $request->input('search');
        $jobType = $request->input('job_type');
        $businessModel = $request->input('business_model');
        $status = $request->input('status');
        $bangladeshFocus = $request->input('bangladesh_focus', '');

        $platforms = Platform::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('official_name', 'like', "%{$search}%");
                });
            })
            ->when($jobType, function ($query) use ($jobType) {
                $query->where('job_type', $jobType);
            })
            ->when($businessModel, function ($query) use ($businessModel) {
                $query->where('business_model', $businessModel);
            })
            ->when($status !== null, function ($query) use ($status) {
                $query->where('is_active', $status === 'active');
            })
            ->when($bangladeshFocus, function ($query) use ($bangladeshFocus) {
                $query->where(
                    'is_bangladesh_focused',
                    $bangladeshFocus === 'focused'
                );
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $trashedPlatformsCount = Platform::onlyTrashed()->count();

        return view('admin.platforms.index', compact(
            'platforms',
            'search',
            'jobType',
            'businessModel',
            'status',
            'bangladeshFocus',
            'trashedPlatformsCount'
        ));
    }

    public function create(): View
    {
        $this->authorize('create', Platform::class);

        return view('admin.platforms.create');
    }

    public function store(PlatformRequest $request): RedirectResponse
    {
        $this->authorize('create', Platform::class);

        $validated = $request->validated();

        $platform = Platform::create([
            ...$validated,
            'slug' => $this->generateUniqueSlug($validated['name']),
        ]);

        return redirect()
            ->route('admin.platforms.show', $platform)
            ->with(
                'success',
                "Platform {$platform->name} created successfully."
            );
    }

    public function show(Platform $platform): View
    {
        $this->authorize('view', $platform);

        $platform->load([
            'companies',
            'platformPages',
            'connectedPlatforms',
            'connectedFromPlatforms',
        ]);

        return view(
            'admin.platforms.show',
            compact('platform')
        );
    }

    public function edit(Platform $platform): View
    {
        $this->authorize('update', $platform);

        return view(
            'admin.platforms.edit',
            compact('platform')
        );
    }

    public function update(
        PlatformRequest $request,
        Platform $platform
    ): RedirectResponse {
        $this->authorize('update', $platform);

        $validated = $request->validated();

        $platform->update([
            ...$validated,
            'slug' => $this->generateUniqueSlug(
                $validated['name'],
                $platform->id
            ),
        ]);

        return redirect()
            ->route('admin.platforms.show', $platform)
            ->with(
                'success',
                "Platform {$platform->name} updated successfully."
            );
    }

    public function destroy(Platform $platform): RedirectResponse
    {
        $this->authorize('delete', $platform);

        $name = $platform->name;

        DB::transaction(function () use ($platform) {
            $this->softDeletePlatformData($platform);

            $platform->delete();
        });

        return redirect()
            ->route('admin.platforms.index')
            ->with(
                'success',
                "Platform {$name} moved to trash."
            );
    }

    public function trash(): View
    {
        $this->authorize('viewAny', Platform::class);

        $platforms = Platform::onlyTrashed()
            ->latest('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.platforms.trash',
            compact('platforms')
        );
    }

    public function restore(string $platform): RedirectResponse
    {
        $platform = Platform::withTrashed()
            ->where('slug', $platform)
            ->firstOrFail();

        $this->authorize('restore', $platform);

        $name = $platform->name;

        DB::transaction(function () use ($platform) {
            $platform->restore();

            $this->restorePlatformData($platform);
        });

        return redirect()
            ->route('admin.platforms.trash')
            ->with(
                'success',
                "Platform {$name} restored successfully."
            );
    }

    public function forceDelete(string $platform): RedirectResponse
    {
        $platform = Platform::withTrashed()
            ->where('slug', $platform)
            ->firstOrFail();

        $this->authorize('forceDelete', $platform);

        $name = $platform->name;

        DB::transaction(function () use ($platform) {
            $this->forceDeletePlatformData($platform);

            $platform->forceDelete();
        });

        return redirect()
            ->route('admin.platforms.trash')
            ->with(
                'success',
                "Platform {$name} permanently deleted."
            );
    }

    private function softDeletePlatformData(Platform $platform): void
    {
        $platform->platformPages()->delete();

        DB::table('platform_platforms')
            ->where('platform_id', $platform->id)
            ->orWhere('connected_platform_id', $platform->id)
            ->delete();
    }

    private function restorePlatformData(Platform $platform): void
    {
        Platform::withoutEvents(function () use ($platform) {
            $platform->platformPages()
                ->withTrashed()
                ->restore();
        });
    }

    private function forceDeletePlatformData(Platform $platform): void
    {
        $platform->platformPages()
            ->withTrashed()
            ->forceDelete();

        DB::table('platform_platforms')
            ->where('platform_id', $platform->id)
            ->orWhere('connected_platform_id', $platform->id)
            ->delete();
    }

    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $slug = Str::slug($name);

        if ($slug === '') {
            $slug = 'platform';
        }

        $original = $slug;
        $counter = 1;

        while (
            Platform::where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where(
                        'id',
                        '!=',
                        $ignoreId
                    )
                )
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}