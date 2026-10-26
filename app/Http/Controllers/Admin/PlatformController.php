<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlatformRequest;
use App\Models\PlatformConnection;
use App\Models\Platform;
use App\Models\PlatformGroup;
use App\Models\PlatformPage;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlatformController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Platform::class);

        $search = trim($request->input('search', ''));
        $jobType = trim($request->input('job_type', ''));
        $businessModel = trim($request->input('business_model', ''));
        $status = $request->input('status');
        $bangladeshFocus = trim($request->input('bangladesh_focus', ''));

        $platforms = Platform::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere(
                            'official_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'short_desc',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'description',
                            'like',
                            "%{$search}%"
                        );
                });
            })
            ->when($jobType, function ($query) use ($jobType) {
                $query->where('job_type', $jobType);
            })
            ->when($businessModel, function ($query) use ($businessModel) {
                $query->where('business_model', $businessModel);
            })
            ->when(
                $status !== null && $status !== '',
                function ($query) use ($status) {
                    $query->where(
                        'is_active',
                        $status === 'active'
                    );
                }
            )
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

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $jobTypes = Platform::query()
            ->whereNotNull('job_type')
            ->where('job_type', '!=', '')
            ->distinct()
            ->orderBy('job_type')
            ->pluck('job_type');

        $businessModels = Platform::query()
            ->whereNotNull('business_model')
            ->where('business_model', '!=', '')
            ->distinct()
            ->orderBy('business_model')
            ->pluck('business_model');

        /*
        |--------------------------------------------------------------------------
        | Summary Statistics
        |--------------------------------------------------------------------------
        */

        $platformStatsQuery = Platform::query();

        $totalPlatforms = (clone $platformStatsQuery)->count();

        $activePlatforms = (clone $platformStatsQuery)
            ->where('is_active', true)
            ->count();

        $inactivePlatforms = (clone $platformStatsQuery)
            ->where('is_active', false)
            ->count();

        $trashedPlatformsCount = Platform::onlyTrashed()->count();

        return view(
            'admin.platform.platforms.index',
            compact(
                'platforms',
                'jobTypes',
                'businessModels',
                'search',
                'jobType',
                'businessModel',
                'status',
                'bangladeshFocus',
                'totalPlatforms',
                'activePlatforms',
                'inactivePlatforms',
                'trashedPlatformsCount'
            )
        );
    }

    public function create(): View
    {
        $this->authorize('create', Platform::class);

        return view('admin.platform.platforms.create');
    }

    public function store(PlatformRequest $request): RedirectResponse
    {
        $this->authorize('create', Platform::class);

        $validated = $request->validated();

        $images = $this->storeImages($request);

        $platform = Platform::create([
            ...$validated,
            'logo' => $images['logo'],
            'cover_image' => $images['cover_image'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        // $platform = DB::transaction(function () use (
        //     $validated,
        //     $images,
        // ){
        //     return Platform::create([
        //         ...$validated,
        //         'logo' => $images['logo'],
        //         'cover_image' => $images['cover_image'],
        //         'slug' => $this->generateUniqueSlug($validated['name']),
        //         'created_by' => Auth::id(),
        //         'updated_by' => Auth::id(),
        //     ]);
        // });

        return redirect()
            ->route('admin.platforms.show', $platform)
            ->with(
                'success',
                "Platform {$platform->name} created successfully."
            );
    }

    public function show(string $platform): View
    {
        $platform = Platform::withTrashed()
            ->where('slug', $platform)
            ->firstOrFail();

        $this->authorize('view', $platform);

        $platform->load([
            'companies',
            'platformPages',
            'connectedPlatforms',
            'connectedFromPlatforms',
        ]);

        return view(
            'admin.platform.platforms.show',
            compact('platform')
        );
    }

    public function edit(Platform $platform): View
    {
        $this->authorize('update', $platform);

        return view(
            'admin.platform.platforms.edit',
            compact('platform')
        );
    }

    public function update(PlatformRequest $request, Platform $platform): RedirectResponse {
        $this->authorize('update', $platform);

        $validated = $request->validated();

        $oldImages = [
            'logo' => $platform->logo,
            'cover_image' => $platform->cover_image,
        ];

        $images = $this->updateImages(
            $request,
            $platform
        );

        $validated = array_merge(
            $validated,
            $images
        );

        $platform->update([
            ...$validated,
            'slug' => $this->generateUniqueSlug(
                $validated['name'],
                $platform->id
            ),
            'updated_by' => Auth::id(),
        ]);

        /*
         * Delete previous images only after the database
         * update has completed successfully.
         */
        $this->deleteImages(
            $oldImages,
            $images
        );

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

            $platform->update([
                'deleted_by' => Auth::id(),
            ]);

            $platform->delete();
        });

        return redirect()
            ->route('admin.platforms.index')
            ->with(
                'success',
                "Platform {$name} moved to trash."
            );
    }

    public function trash(Request $request): View
    {
        $this->authorize('viewAny', Platform::class);

        $search = trim($request->input('search', ''));
        $jobType = trim($request->input('job_type', ''));
        $businessModel = trim(
            $request->input('business_model', '')
        );
        $deletedBy = trim($request->input('deleted_by', ''));
        $deletedPeriod = trim(
            $request->input('deleted_period', '')
        );

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $jobTypes = Platform::onlyTrashed()
            ->whereNotNull('job_type')
            ->where('job_type', '!=', '')
            ->distinct()
            ->orderBy('job_type')
            ->pluck('job_type');

        $businessModels = Platform::onlyTrashed()
            ->whereNotNull('business_model')
            ->where('business_model', '!=', '')
            ->distinct()
            ->orderBy('business_model')
            ->pluck('business_model');

        $deletedByUsers = User::query()
            ->whereIn(
                'id',
                Platform::onlyTrashed()
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
        | Trashed Platforms
        |--------------------------------------------------------------------------
        */

        $platforms = Platform::onlyTrashed()
            ->with([
                'deletedBy:id,name,slug,role',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where(
                        'name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'slug',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'official_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'base_url',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'job_url',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'short_desc',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'description',
                            'like',
                            "%{$search}%"
                        );
                });
            })
            ->when($jobType, function ($query) use ($jobType) {
                $query->where('job_type', $jobType);
            })
            ->when(
                $businessModel,
                function ($query) use ($businessModel) {
                    $query->where(
                        'business_model',
                        $businessModel
                    );
                }
            )
            ->when($deletedBy, function ($query) use ($deletedBy) {
                $query->where('deleted_by', $deletedBy);
            })
            ->when(
                $deletedPeriod,
                function ($query) use ($deletedPeriod) {
                    match ($deletedPeriod) {
                        'today' => $query->whereDate(
                            'deleted_at',
                            today()
                        ),

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
                }
            )
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.platform.platforms.trash',
            compact(
                'platforms',
                'jobTypes',
                'businessModels',
                'search',
                'jobType',
                'businessModel',
                'deletedBy',
                'deletedPeriod',
                'deletedByUsers'
            )
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
            $platform->update([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

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

    /** Store uploaded images for a new page. */
    private function storeImages(Request $request): array
    {
        return [
            'logo' => $request->hasFile('logo')
                ? $request->file('logo')->store(
                    'platforms/logos',
                    'public'
                )
                : null,

            'cover_image' => $request->hasFile('cover_image')
                ? $request->file('cover_image')->store(
                    'platforms/covers',
                    'public'
                )
                : null,
        ];
    }

    /** Store newly uploaded images when updating a page. Existing images are kept when no replacement file is uploaded. */
    private function updateImages(Request $request, Platform $platform): array {
        $images = [];

        if ($request->hasFile('logo')) {
            $images['logo'] = $request->file('logo')->store(
                'platforms/logos',
                'public'
            );
        } else {
            $images['logo'] = $platform->logo;
        }

        if ($request->hasFile('cover_image')) {
            $images['cover_image'] = $request->file('cover_image')->store(
                'platforms/covers',
                'public'
            );
        } else {
            $images['cover_image'] = $platform->cover_image;
        }

        return $images;
    }

    /** Delete replaced images. The old image is deleted only when a new image has actually replaced it. */
    private function deleteImages(array $oldImages, array $newImages): void 
    {
        if (
            !empty($oldImages['logo']) &&
            !empty($newImages['logo']) &&
            $oldImages['logo'] !== $newImages['logo']
        ) {
            Storage::disk('public')->delete(
                $oldImages['logo']
            );
        }

        if (
            !empty($oldImages['cover_image']) &&
            !empty($newImages['cover_image']) &&
            $oldImages['cover_image'] !== $newImages['cover_image']
        ) {
            Storage::disk('public')->delete(
                $oldImages['cover_image']
            );
        }
    }

    private function softDeletePlatformData(Platform $platform): void
    {
        $platform->platformPages()
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'deleted_by' => Auth::id(),
                'updated_at' => now(),
            ]);
        $platform->platformGroups()
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'deleted_by' => Auth::id(),
                'updated_at' => now(),
            ]);
        PlatformConnection::query()
            ->where(function ($query) use ($platform) {
                $query->where('platform_id', $platform->id)
                    ->orWhere('connected_platform_id', $platform->id);
            })
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'deleted_by_platform_id' => $platform->id,
                'updated_at' => now(),
            ]);
    }
    private function restorePlatformData(Platform $platform): void
    {
        Platform::withoutEvents(function () use ($platform) {
            $platform->platformPages()
                ->withTrashed()
                ->whereNotNull('deleted_at')
                ->restore();
            $platform->platformGroups()
                ->withTrashed()
                ->whereNotNull('deleted_at')
                ->restore();
        });
        $connections = PlatformConnection::onlyTrashed()
            ->where('deleted_by_platform_id', $platform->id)
            ->get();
        foreach ($connections as $connection) {
            $otherPlatformId = (int) $connection->platform_id === (int) $platform->id
                ? $connection->connected_platform_id
                : $connection->platform_id;
            $otherPlatform = Platform::withTrashed()->find($otherPlatformId);
            if (!$otherPlatform || $otherPlatform->trashed()) {
                if ($otherPlatform && $otherPlatform->trashed()) {
                    $connection->update([
                        'deleted_by_platform_id' => $otherPlatform->id,
                    ]);
                }
                continue;
            }
            $connection->restore();
            $connection->update([
                'deleted_by_platform_id' => null,
            ]);
        }
    }
    private function forceDeletePlatformData(Platform $platform): void
    {
        $platform->platformPages()
            ->withTrashed()
            ->forceDelete();
        $platform->platformGroups()
            ->withTrashed()
            ->forceDelete();
        PlatformConnection::withTrashed()
            ->where(function ($query) use ($platform) {
                $query->where('platform_id', $platform->id)
                    ->orWhere('connected_platform_id', $platform->id);
            })
            ->forceDelete();
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string {
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