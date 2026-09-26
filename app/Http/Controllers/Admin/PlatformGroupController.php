<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlatformGroupRequest;
use App\Models\Platform;
use App\Models\PlatformGroup;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlatformGroupController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', PlatformGroup::class);

        $search = $request->input('search');
        $platformSlug = $request->input('platform');
        $groupType = $request->input('group_type');
        $accessType = $request->input('access_type');
        $status = $request->input('status');
        $bangladeshFocus = $request->input('bangladesh_focus', '');

        $platform = $platformSlug
            ? Platform::where('slug', $platformSlug)->first()
            : null;

        $platformId = $platform?->id;

        $groups = PlatformGroup::query()
            ->with('platform:id,name,slug')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('group_type', 'like', "%{$search}%")
                        ->orWhere('short_desc', 'like', "%{$search}%");
                });
            })
            ->when($platformId, function ($query) use ($platformId) {
                $query->where('platform_id', $platformId);
            })
            ->when($groupType, function ($query) use ($groupType) {
                $query->where('group_type', $groupType);
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
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $platforms = Platform::query()
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();

        $groupTypes = PlatformGroup::query()
            ->whereNotNull('group_type')
            ->where('group_type', '!=', '')
            ->when($platformId, function ($query) use ($platformId) {
                $query->where('platform_id', $platformId);
            })
            ->distinct()
            ->orderBy('group_type')
            ->pluck('group_type');

        $accessTypes = PlatformGroup::query()
            ->whereNotNull('access_type')
            ->where('access_type', '!=', '')
            ->distinct()
            ->orderBy('access_type')
            ->pluck('access_type');

        $total = PlatformGroup::query()
            ->when($platformId, fn ($query) =>
                $query->where('platform_id', $platformId)
            )
            ->count();

        $active = PlatformGroup::query()
            ->when($platformId, fn ($query) =>
                $query->where('platform_id', $platformId)
            )
            ->where('is_active', true)
            ->count();

        $inactive = $total - $active;

        $trashedGroupsCount = PlatformGroup::onlyTrashed()
            ->when($platformId, fn ($query) =>
                $query->where('platform_id', $platformId)
            )
            ->count();

        return view('admin.platform-groups.index', compact(
            'groups',
            'platform',
            'platformSlug',
            'platformId',
            'platforms',
            'groupTypes',
            'accessTypes',
            'search',
            'groupType',
            'accessType',
            'status',
            'bangladeshFocus',
            'total',
            'active',
            'inactive',
            'trashedGroupsCount',
        ));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', PlatformGroup::class);

        $platformSlug = $request->input('platform');

        $platform = $platformSlug
            ? Platform::where('slug', $platformSlug)->firstOrFail()
            : null;

        $platforms = Platform::query()
            ->orderBy('name')
            ->get();

        return view('admin.platform-groups.create', compact(
            'platform',
            'platforms',
            'platformSlug',
        ));
    }

    public function store(PlatformGroupRequest $request): RedirectResponse {
        $this->authorize('create', PlatformGroup::class);

        $validated = $request->validated();

        $platform = Platform::findOrFail($validated['platform_id']);

        $images = $this->storeImages($request);

        $group = DB::transaction(function () use (
            $validated,
            $platform,
            $images
        ) {
            return PlatformGroup::create([
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

        return redirect()
            ->route('admin.platform-groups.show', $group)
            ->with(
                'success',
                "Group {$group->name} created successfully."
            );
    }

    public function show(string $platformGroup): View
    {
        $platformGroup = PlatformGroup::withTrashed()
            ->where('slug', $platformGroup)
            ->firstOrFail();

        $this->authorize('view', $platformGroup);

        $platformGroup->load(['platform:id,name,slug,official_name,logo,cover_image,icon,color,is_bangladesh_focused',]);

        return view('admin.platform-groups.show', compact('platformGroup'));
    }

    public function edit(PlatformGroup $platformGroup): View
    {
        $this->authorize('update', $platformGroup);

        $platforms = Platform::query()
            ->orderBy('name')
            ->get();

        $platform = $platformGroup->platform;

        return view('admin.platform-groups.edit', compact(
            'platform',
            'platformGroup',
            'platforms'
        ));
    }

    public function update(PlatformGroupRequest $request, PlatformGroup $platformGroup): RedirectResponse {
        $this->authorize('update', $platformGroup);

        $validated = $request->validated();

        $platform = Platform::findOrFail(
            $validated['platform_id']
        );

        $oldImages = [
            'logo' => $platformGroup->logo,
            'cover_image' => $platformGroup->cover_image,
        ];

        $images = $this->updateImages(
            $request,
            $platformGroup
        );

        $validated = array_merge(
            $validated,
            $images
        );

        DB::transaction(function () use (
            $platformGroup,
            $validated,
            $platform
        ) {
            $platformGroup->update([
                ...$validated,
                'slug' => $this->generateUniqueSlug(
                    $validated['name'],
                    $platform->id,
                    $platformGroup->id
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

        return redirect()
            ->route('admin.platform-groups.show', $platformGroup)
            ->with(
                'success',
                "Group {$platformGroup->name} updated successfully."
            );
    }

    public function destroy(PlatformGroup $platformGroup): RedirectResponse {
        $this->authorize('delete', $platformGroup);

        $name = $platformGroup->name;
        $platformSlug = $platformGroup->platform?->slug;

        $platformGroup->update([
            'deleted_by' => Auth::id(),
        ]);

        $platformGroup->delete();

        return redirect()
            ->route('admin.platform-groups.index', ['platform' => $platformSlug,])
            ->with('success', "Group {$name} moved to trash.");
    }

    public function trash(Request $request): View
    {
        $this->authorize('viewAny', PlatformGroup::class);

        $platforms = Platform::orderBy('name')
            ->get(['id', 'name']);

        $groupTypes = PlatformGroup::onlyTrashed()
            ->whereNotNull('group_type')
            ->distinct()
            ->orderBy('group_type')
            ->pluck('group_type');

        $groups = PlatformGroup::onlyTrashed()
            ->with([
                'platform:id,name,slug,logo,icon,color',
                'deletedBy:id,name,slug,role',
            ])
            ->when(request('platform'), fn ($query) => $query->where('platform_id', request('platform')))
            ->when(request('type'), fn ($query) => $query->where('group_type', request('type')))
            ->when(request('access_type'), fn ($query) => $query->where('access_type',request('access_type')))
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.platform-groups.trash', compact('groups', 'platforms', 'groupTypes'));
    }

    public function restore( string $platformGroup): RedirectResponse 
    {
        $group = PlatformGroup::onlyTrashed()
            ->where('slug', $platformGroup)
            ->firstOrFail();

        $this->authorize('restore', $group);

        $group->update([
            'deleted_by' => null,
            'updated_by' => Auth::id(),
        ]);

        $group->restore();

        return redirect()
            ->route('admin.platform-groups.trash')
            ->with('success', "Group {$group->name} restored successfully.");
    }

    public function forceDelete(string $platformGroup): RedirectResponse 
    {
        $group = PlatformGroup::onlyTrashed()
            ->where('slug', $platformGroup)
            ->firstOrFail();

        $this->authorize('forceDelete', $group);

        $group->forceDelete();

        return redirect()
            ->route('admin.platform-groups.trash')
            ->with('success', "Platform Group permanently deleted.");
    }
    
    /** Store uploaded images for a new group. */
    private function storeImages(Request $request): array
    {
        return [
            'logo' => $request->hasFile('logo')
                ? $request->file('logo')->store(
                    'platform-groups/logos',
                    'public'
                )
                : null,

            'cover_image' => $request->hasFile('cover_image')
                ? $request->file('cover_image')->store(
                    'platform-groups/covers',
                    'public'
                )
                : null,
        ];
    }

    /** Store newly uploaded images when updating a group. Existing images are kept when no replacement file is uploaded. */
    private function updateImages( Request $request, PlatformGroup $platformGroup): array {
        $images = [];

        if ($request->hasFile('logo')) {
            $images['logo'] = $request->file('logo')->store(
                'platform-groups/logos',
                'public'
            );
        } else {
            $images['logo'] = $platformGroup->logo;
        }

        if ($request->hasFile('cover_image')) {
            $images['cover_image'] = $request->file('cover_image')->store(
                'platform-groups/covers',
                'public'
            );
        } else {
            $images['cover_image'] = $platformGroup->cover_image;
        }

        return $images;
    }

    /** Delete replaced images. The old image is deleted only when a new image has actually replaced it. */
    private function deleteImages(array $oldImages, array $newImages): void {
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

    private function generateUniqueSlug(string $name, int $platformId, ?int $ignoreId = null): string {
        $slug = Str::slug($name);

        if ($slug === '') {
            $slug = 'group';
        }

        $original = $slug;
        $counter = 1;

        while (
            PlatformGroup::withTrashed()
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