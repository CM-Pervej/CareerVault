<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlatformConnectionRequest;
use App\Models\Platform;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PlatformConnectionController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize('viewAny', Platform::class);

        $platforms = Platform::query()
            ->withCount('connectedPlatforms')
            ->with([
                'connectedPlatforms:id,name,slug,logo,icon,color'
            ])
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
                'logo',
                'icon',
                'color',
                'is_active',
            ]);

        return view(
            'admin.platform-connections.index',
            compact('platforms')
        );
    }

    public function create(): View
    {
        $this->authorize('create', Platform::class);

        $platforms = $this->availablePlatforms();

        return view(
            'admin.platform-connections.create',
            compact('platforms')
        );
    }

    public function store(
        PlatformConnectionRequest $request
    ): RedirectResponse {
        $this->authorize('create', Platform::class);

        $validated = $request->validated();

        $platformId = $validated['platform_id'];
        $connections = $validated['connections'] ?? [];

        DB::transaction(function () use ($platformId, $connections) {
            foreach ($connections as $connection) {
                DB::table('platform_platforms')->insert([
                    'platform_id' => $platformId,
                    'connected_platform_id' => $connection['connected_platform_id'],
                    'account_url' => $connection['account_url'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('admin.platform-connections.index')
            ->with(
                'success',
                count($connections) . ' platform connection(s) added successfully.'
            );
    }

    public function edit(Platform $platform): View
    {
        $this->authorize('update', $platform);

        $platform->load([
            'connectedPlatforms',
        ]);

        $platforms = $this->availablePlatforms($platform->id);

        return view(
            'admin.platform-connections.edit',
            compact('platform', 'platforms')
        );
    }

    public function update(
        PlatformConnectionRequest $request,
        Platform $platform
    ): RedirectResponse {
        $this->authorize('update', $platform);

        $validated = $request->validated();
        $connections = $validated['connections'] ?? [];

        DB::transaction(function () use ($platform, $connections) {
            $platform->connectedPlatforms()->detach();

            foreach ($connections as $connection) {
                $platform->connectedPlatforms()->attach(
                    $connection['connected_platform_id'],
                    [
                        'account_url' => $connection['account_url'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.platform-connections.index')
            ->with(
                'success',
                $platform->name . ' connections updated successfully.'
            );
    }

    private function availablePlatforms(?int $excludeId = null)
    {
        return Platform::query()
            ->when(
                $excludeId,
                fn ($query) => $query->where('id', '!=', $excludeId)
            )
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
                'logo',
                'icon',
                'color',
                'is_active',
            ]);
    }
}