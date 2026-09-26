<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlatformPageRequest;
use App\Models\Platform;
use App\Models\PlatformPage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlatformPageController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View {
        $this->authorize('viewAny', PlatformPage::class);

        $search = trim($request->input('search', ''));
        $platformSlug = trim($request->input('platform', ''));
        $pageType = trim($request->input('page_type', ''));
        $status = $request->input('status');

        // Selected Platform
        $platform = null;

        if ($platformSlug !== '') {
            $platform = Platform::query()->where('slug', $platformSlug)->firstOrFail();
        }

        $platformId = $platform?->id;

        // Pages
        $pages = PlatformPage::query()
            ->with(['platform:id,name,slug,logo,icon,color',])
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('short_desc', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('page_type', 'like', "%{$search}%");
                })
            )
            ->when(
                $platformId, 
                fn ($query) => $query->where('platform_id', $platformId)
            )
            ->when(
                $pageType !== '', 
                fn ($query) => $query->where('page_type', $pageType)
            )
            ->when(
                $status === 'active', 
                fn ($query) => $query->where('is_active', true)
            )
            ->when(
                $status === 'inactive',
                fn ($query) => $query->where('is_active', false)
            )
            ->orderBy('platform_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        // Platform Filter Options
        $platforms = Platform::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug',]);

        // Page Type Filter Options
        $pageTypes = PlatformPage::query()
            ->whereNotNull('page_type')
            ->where('page_type', '!=', '')
            ->distinct()
            ->orderBy('page_type')
            ->pluck('page_type');

        // Summary Statistics
        $pageStatsQuery = PlatformPage::query()
            ->when(
                $platformId,
                fn ($query) => $query->where('platform_id', $platformId)
            );

        $totalPages = (clone $pageStatsQuery)->count();
        $activePages = (clone $pageStatsQuery)->where('is_active', true)->count();
        $inactivePages = (clone $pageStatsQuery)->where('is_active', false)->count();
        $trashedPagesCount = PlatformPage::onlyTrashed()->count();

        return view('admin.platform-pages.index', compact('pages', 'platforms', 'pageTypes', 'platform', 'platformSlug', 'platformId', 'totalPages', 'activePages', 'inactivePages', 'search', 'pageType', 'status', 'trashedPagesCount'));
    }

    public function create(Request $request): View {
        $this->authorize('create', PlatformPage::class);

        $selectedPlatform = null;

        if ($request->filled('platform')) {
            $selectedPlatform = Platform::query()
                ->where('slug', $request->input('platform'))
                ->firstOrFail();
        }

        $platforms = Platform::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'logo', 'icon', 'color',]);

        return view('admin.platform-pages.create', compact('platforms', 'selectedPlatform'));
    }

    public function store(PlatformPageRequest $request): RedirectResponse {
        $this->authorize('create', PlatformPage::class);

        $validated = $request->validated();

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['name'],
            $validated['platform_id']
        );

        $platformPage = PlatformPage::create($validated);

        return redirect()->route('admin.platform-pages.show', $platformPage)->with('success', 'Platform page created successfully.');
    }

    public function show(string $platformPage): View {
        $platformPage = PlatformPage::withTrashed()
            ->where('slug',$platformPage)
            ->firstOrFail();

        $this->authorize('view',$platformPage);

        $platformPage->load(['platform:id,name,slug,official_name,logo,cover_image,icon,color,is_bangladesh_focused',]);

        return view('admin.platform-pages.show', compact('platformPage'));
    }

    public function edit(PlatformPage $platformPage): View {
        $this->authorize('update', $platformPage);

        $platformPage->load('platform');

        $platforms = Platform::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'logo', 'icon', 'color',]);

        return view('admin.platform-pages.edit', compact('platformPage', 'platforms'));
    }

    public function update(PlatformPageRequest $request, PlatformPage $platformPage): RedirectResponse {
        $this->authorize('update', $platformPage);

        $validated = $request->validated();
        $platformChanged = (int) $validated['platform_id'] !== (int) $platformPage->platform_id;
        $nameChanged = $validated['name'] !== $platformPage->name;

        if ($platformChanged || $nameChanged) {
            $validated['slug'] = $this->generateUniqueSlug(
                $validated['name'],
                $validated['platform_id'],
                $platformPage->id
            );
        } else {
            $validated['slug'] = $platformPage->slug;
        }

        $platformPage->update($validated);

        return redirect()->route('admin.platform-pages.show', $platformPage)->with('success', 'Platform page updated successfully.');
    }

    public function destroy(PlatformPage $platformPage): RedirectResponse
    {
        $this->authorize('delete',$platformPage);

        $platformPage->update([
            'deleted_by' => Auth::id(),
        ]);

        $platformPage->delete();

        return redirect()
            ->route('admin.platform-pages.index')
            ->with('success','Platform page moved to trash.');
    }

    public function trash(): View
    {
        $this->authorize('viewAny', PlatformPage::class);

        $platforms = Platform::orderBy('name')
            ->get(['id', 'name']);

        $pageTypes = PlatformPage::onlyTrashed()
            ->whereNotNull('page_type')
            ->distinct()
            ->orderBy('page_type')
            ->pluck('page_type');

        $pages = PlatformPage::onlyTrashed()
            ->with([
                'platform:id,name,slug,logo,icon,color',
                'deletedBy:id,name,slug,role',
            ])
            ->when(request('platform'), fn ($query) => $query->where('platform_id', request('platform')))
            ->when(request('type'), fn ($query) => $query->where('page_type', request('type')))
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.platform-pages.trash', compact('pages', 'platforms', 'pageTypes'));
    }

    public function restore(string $platformPage): RedirectResponse
    {
        $platformPage=PlatformPage::onlyTrashed()
            ->where('slug',$platformPage)
            ->firstOrFail();

        $this->authorize('restore',$platformPage);

        $platformPage->update([
            'deleted_by' => null,
        ]);

        $platformPage->restore();

        return redirect()
            ->route('admin.platform-pages.trash')
            ->with('success','Platform page restored successfully.');
    }

    public function forceDelete(string $platformPage): RedirectResponse
    {
        $platformPage=PlatformPage::onlyTrashed()
            ->where('slug',$platformPage)
            ->firstOrFail();

        $this->authorize('forceDelete', $platformPage);

        $platformPage->forceDelete();

        return redirect()
            ->route('admin.platform-pages.trash')
            ->with('success','Platform page permanently deleted.');
    }

    private function generateUniqueSlug(string $name, int $platformId, ?int $ignoreId = null): string {
        $baseSlug = Str::slug($name) ?: 'page';

        $slug = $baseSlug;
        $counter = 2;

        while (
            PlatformPage::withTrashed()
                ->where('platform_id', $platformId)
                ->where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}