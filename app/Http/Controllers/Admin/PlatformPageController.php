<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlatformPageRequest;
use App\Models\Platform;
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

class PlatformPageController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', PlatformPage::class);

        $search = trim($request->input('search', ''));
        $platformSlug = trim($request->input('platform', ''));
        $pageType = trim($request->input('page_type', ''));
        $accessType = trim($request->input('access_type', ''));
        $status = $request->input('status');
        $bangladeshFocus = trim($request->input('bangladesh_focus', ''));

        // Selected Platform
        $platform = null;

        if ($platformSlug !== '') {
            $platform = Platform::query()
                ->where('slug', $platformSlug)
                ->firstOrFail();
        }

        $platformId = $platform?->id;

        // Pages
        $pages = PlatformPage::query()
            ->with('platform:id,name,slug,logo,icon,color')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('page_type', 'like', "%{$search}%")
                        ->orWhere('short_desc', 'like', "%{$search}%");
                });
            })
            ->when($platformId, function ($query) use ($platformId) {
                $query->where('platform_id', $platformId);
            })
            ->when($pageType, function ($query) use ($pageType) {
                $query->where('page_type', $pageType);
            })
            ->when($accessType, function ($query) use ($accessType) {
                $query->where('access_type', $accessType);
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
            ->orderBy('platform_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $platforms = Platform::query()
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();

        $pageTypes = PlatformPage::query()
            ->whereNotNull('page_type')
            ->where('page_type', '!=', '')
            ->when($platformId, function ($query) use ($platformId) {
                $query->where('platform_id', $platformId);
            })
            ->distinct()
            ->orderBy('page_type')
            ->pluck('page_type');

        $accessTypes = PlatformPage::query()
            ->whereNotNull('access_type')
            ->where('access_type', '!=', '')
            ->distinct()
            ->orderBy('access_type')
            ->pluck('access_type');

        // Summary Statistics
        $pageStatsQuery = PlatformPage::query()
            ->when($platformId, fn ($query) => $query->where('platform_id', $platformId));

        $totalPages = (clone $pageStatsQuery)->count();
        $activePages = (clone $pageStatsQuery)->where('is_active', true)->count();
        $inactivePages = (clone $pageStatsQuery)->where('is_active', false)->count();

        $trashedPagesCount = PlatformPage::onlyTrashed()
            ->when($platformId, fn ($query) => 
                $query->where('platform_id', $platformId)
            )
            ->count();

        return view('admin.platform-pages.index', compact(
            'pages',
            'platform',
            'platformSlug',
            'platformId',
            'platforms',
            'pageTypes',
            'accessTypes',
            'search',
            'pageType',
            'accessType',
            'status',
            'bangladeshFocus',
            'totalPages',
            'activePages',
            'inactivePages',
            'trashedPagesCount',
        ));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', PlatformPage::class);

        $platformSlug = $request->input('platform');

        $platform = $platformSlug
            ? Platform::where('slug', $platformSlug)->firstOrFail()
            : null;

        $platforms = Platform::query()
            ->orderBy('name')
            ->get();

        return view('admin.platform-pages.create', compact(
            'platform',
            'platforms',
            'platformSlug',
        ));
    }

    public function store(PlatformPageRequest $request): RedirectResponse
    {
        $this->authorize('create', PlatformPage::class);

        $validated = $request->validated();

        $platform = Platform::findOrFail($validated['platform_id']);

        $images = $this->storeImages($request);

        $page = DB::transaction(function () use (
            $validated,
            $platform,
            $images
        ) {
            return PlatformPage::create([
                ...$validated,
                'logo' => $images['logo'],
                'cover_image' => $images['cover_image'],
                'slug' => $this->generateUniqueSlug(
                    $validated['name'],
                    $platform->id
                ),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()->route('admin.platform-pages.show', $page)->with('success', "Page {$page->name} created successfully.");
    }

    public function show(string $platformPage): View
    {
        $platformPage = PlatformPage::withTrashed()
            ->where('slug', $platformPage)
            ->firstOrFail();

        $this->authorize('view', $platformPage);

        $platformPage->load(['platform:id,name,slug,official_name,logo,cover_image,icon,color,is_bangladesh_focused',]);

        return view('admin.platform-pages.show', compact('platformPage'));
    }

    public function edit(PlatformPage $platformPage): View
    {
        $this->authorize('update', $platformPage);

        $platforms = Platform::query()
            ->orderBy('name')
            ->get();

        $platform = $platformPage->platform;

        return view('admin.platform-pages.edit', compact(
            'platform',
            'platformPage',
            'platforms'
        ));
    }

    public function update(PlatformPageRequest $request, PlatformPage $platformPage): RedirectResponse 
    {
        $this->authorize('update', $platformPage);

        $validated = $request->validated();

        $platform = Platform::findOrFail(
            $validated['platform_id']
        );

        $oldImages = [
            'logo' => $platformPage->logo,
            'cover_image' => $platformPage->cover_image,
        ];

        $images = $this->updateImages(
            $request,
            $platformPage
        );

        $validated = array_merge(
            $validated,
            $images
        );

        DB::transaction(function () use (
            $platformPage,
            $validated,
            $platform
        ) {
            $platformPage->update([
                ...$validated,
                'slug' => $this->generateUniqueSlug(
                    $validated['name'],
                    $platform->id,
                    $platformPage->id
                ),
                'updated_by' => Auth::id(),
            ]);
        });

        /*
         * Delete previous images only after the database
         * update has completed successfully.
         */
        $this->deleteImages(
            $oldImages,
            $images
        );

        return redirect()->route('admin.platform-pages.show', $platformPage)->with('success', "Page {$platformPage->name} updated successfully.");
    }

    public function destroy(PlatformPage $platformPage): RedirectResponse
    {
        $this->authorize('delete', $platformPage);

        $name = $platformPage->name;
        $platformSlug = $platformPage->platform?->slug;

        $platformPage->update([
            'deleted_by' => Auth::id(),
        ]);

        $platformPage->delete();

        return redirect()->route('admin.platform-pages.index', ['platform' => $platformSlug,])->with('success', "Page {$name} moved to trash.");
    }

    public function trash(Request $request): View
    {
        $this->authorize('viewAny', PlatformPage::class);

        $platforms = Platform::orderBy('name')
            ->get(['id', 'name']);

        $pageTypes = PlatformPage::onlyTrashed()
            ->whereNotNull('page_type')
            ->distinct()
            ->orderBy('page_type')
            ->pluck('page_type');

        $deletedByUsers = User::whereIn(
            'id',
            PlatformPage::onlyTrashed()
                ->whereNotNull('deleted_by')
                ->distinct()
                ->pluck('deleted_by')
        )->orderBy('name')->get(['id', 'name', 'role']);

        $pages = PlatformPage::onlyTrashed()
            ->with([
                'platform:id,name,slug,logo,icon,color',
                'deletedBy:id,name,slug,role',
            ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%")
                        ->orWhere('short_desc', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('platform'), function ($query) use ($request) {
                $query->where('platform_id', $request->input('platform'));
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('page_type', $request->input('type'));
            })
            ->when($request->filled('access_type'), function ($query) use ($request) {
                $query->where('access_type', $request->input('access_type'));
            })
            ->when($request->filled('deleted_by'), function ($query) use ($request) {
                $query->where('deleted_by', $request->input('deleted_by'));
            })
            ->when($request->filled('deleted_period'), function ($query) use ($request) {
                match ($request->input('deleted_period')) {
                    'today' => $query->whereDate('deleted_at', today()),

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
            })
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.platform-pages.trash', compact(
            'pages',
            'platforms',
            'pageTypes',
            'deletedByUsers'
        ));
    }

    public function restore(string $platformPage): RedirectResponse
    {
        $page = PlatformPage::onlyTrashed()
            ->where('slug', $platformPage)
            ->firstOrFail();

        $this->authorize('restore', $page);

        $page->update([
            'deleted_by' => null,
            'updated_by' => Auth::id(),
        ]);

        $page->restore();

        return redirect()->route('admin.platform-pages.trash')->with('success', "Page {$page->name} restored successfully.");
    }

    public function forceDelete(string $platformPage): RedirectResponse
    {
        $page = PlatformPage::onlyTrashed()
            ->where('slug', $platformPage)
            ->firstOrFail();

        $this->authorize('forceDelete', $page);

        $page->forceDelete();

        return redirect()->route('admin.platform-pages.trash')->with('success', "Platform Page permanently deleted.");
    }

    /** Store uploaded images for a new page. */
    private function storeImages(Request $request): array
    {
        return [
            'logo' => $request->hasFile('logo')
                ? $request->file('logo')->store(
                    'platform-pages/logos',
                    'public'
                )
                : null,

            'cover_image' => $request->hasFile('cover_image')
                ? $request->file('cover_image')->store(
                    'platform-pages/covers',
                    'public'
                )
                : null,
        ];
    }

    /** Store newly uploaded images when updating a page. Existing images are kept when no replacement file is uploaded. */
    private function updateImages(Request $request, PlatformPage $platformPage): array {
        $images = [];

        if ($request->hasFile('logo')) {
            $images['logo'] = $request->file('logo')->store(
                'platform-pages/logos',
                'public'
            );
        } else {
            $images['logo'] = $platformPage->logo;
        }

        if ($request->hasFile('cover_image')) {
            $images['cover_image'] = $request->file('cover_image')->store(
                'platform-pages/covers',
                'public'
            );
        } else {
            $images['cover_image'] = $platformPage->cover_image;
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

    private function generateUniqueSlug(string $name, int $platformId, ?int $ignoreId = null): string 
    {
        $slug = Str::slug($name);

        if ($slug === '') {
            $slug = 'page';
        }

        $original = $slug;
        $counter = 1;

        while (
            PlatformPage::withTrashed()
                ->where('platform_id', $platformId)
                ->where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) =>
                        $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}