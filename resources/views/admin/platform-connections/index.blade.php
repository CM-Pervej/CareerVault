@extends('layouts.admin.app')

@section('title','Platform Connections')

@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="space-y-6">

    {{-- ========================= HEADER =========================== --}}
    <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
        <div class="min-w-0">
            <div class="flex items-start gap-3">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                    <i class="fa-brands fa-connectdevelop"></i>
                </div>

                <div class="min-w-0">
                    <h1 class="cv-admin-title text-3xl sm:text-4xl">Platform Connections</h1>
                    <p class="mt-1 text-sm text-base-content/60">Manage official cross-platform presence across CareerVault.</p>
                </div>
            </div>
        </div>

        <a href="{{ route('admin.platform-connections.create') }}"
        class="btn btn-primary gap-2 shadow-sm">
            <i class="fa-solid fa-plus"></i> Add Connection
        </a>
    </div>

    {{-- ===================== OVERVIEW STATS ======================= --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {{-- Platforms --}}
        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                            <i class="fa-solid fa-layer-group"></i> Platforms
                        </div>

                        <div class="mt-2 text-3xl font-black"> {{ number_format($totalPlatforms) }} </div>
                    </div>

                    <div class="flex size-10 items-center justify-center rounded-lg bg-info/10 text-info">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                    <i class="fa-solid fa-database"></i> Total directory platforms
                </div>
            </div>
        </div>

        {{-- Connected --}}
        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                            <i class="fa-solid fa-link"></i> Connected
                        </div>

                        <div class="mt-2 text-3xl font-black"> {{ number_format($platformsWithConnections) }} </div>
                    </div>

                    <div class="flex size-10 items-center justify-center rounded-lg bg-success/10 text-success">
                        <i class="fa-solid fa-link"></i>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                    <i class="fa-solid fa-circle-check"></i> Platforms with official presence
                </div>
            </div>
        </div>

        {{-- Connections --}}
        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                            <i class="fa-solid fa-arrows-left-right"></i> Connections
                        </div>

                        <div class="mt-2 text-3xl font-black"> {{ number_format($totalConnections) }} </div>
                    </div>

                    <div class="flex size-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <i class="fa-solid fa-share-nodes"></i>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                    <i class="fa-solid fa-building-columns"></i> Official cross-platform accounts
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                            <i class="fa-solid fa-signal"></i> Directory Status
                        </div>

                        <div class="mt-2 flex items-center gap-3">
                            <span class="text-2xl font-black"> {{ number_format($activePlatforms) }} </span>
                            <span class="text-xs font-semibold text-success">Active</span>
                        </div>
                    </div>

                    <div class="flex size-10 items-center justify-center rounded-lg bg-warning/10 text-warning">
                        <i class="fa-solid fa-toggle-on"></i>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ number_format($inactivePlatforms) }} inactive platforms
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== FILTER BAR =========================== --}}
    <div class="rounded-xl border border-base-300 bg-base-100 shadow-sm">
        <form method="GET" action="{{ route('admin.platform-connections.index') }}">
            <div class="border-b border-base-300 p-4 sm:p-5">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center">
                    {{-- Search --}}
                    <div class="form-control min-w-0 flex-1">
                        <div class="label py-0 pb-1.5"> 
                            <span class="label-text text-xs font-bold"> 
                                <i class="fa-brands fa-searchengin"></i> Search 
                            </span> 
                        </div>
                        
                        <label class="input input-bordered flex items-center gap-3">
                            <i class="fa-solid fa-magnifying-glass text-base-content/40"></i>
                            <input type="search" name="search" value="{{ $search }}" placeholder="Search platform name or slug..." class="grow"/>

                            @if($search)
                                <a href="{{ route('admin.platform-connections.index',request()->except('search')) }}" class="btn btn-ghost btn-xs btn-circle">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            @endif
                        </label>
                    </div>

                    {{-- Connection filter --}}
                    <label class="form-control w-full xl:w-52">
                        <div class="label py-0 pb-1.5"> 
                            <span class="label-text text-xs font-bold"> 
                                <i class="fa-solid fa-link mr-1"></i> Connection 
                            </span> 
                        </div>

                        <select name="connection_status" class="select select-bordered">
                            <option value="all" @selected($connectionStatus === 'all')>All platforms</option>
                            <option value="connected" @selected($connectionStatus === 'connected')>Connected</option>
                            <option value="unconnected" @selected($connectionStatus === 'unconnected')>No connections</option>
                        </select>
                    </label>

                    {{-- Activity filter --}}
                    <label class="form-control w-full xl:w-48">
                        <div class="label py-0 pb-1.5"> 
                            <span class="label-text text-xs font-bold"> 
                                <i class="fa-solid fa-power-off mr-1"></i> Status 
                            </span> 
                        </div>

                        <select name="activity_status" class="select select-bordered">
                            <option value="all" @selected($activityStatus === 'all')>All statuses</option>
                            <option value="active" @selected($activityStatus === 'active')>Active only</option>
                            <option value="inactive" @selected($activityStatus === 'inactive')>Inactive only</option>
                        </select>
                    </label>

                    {{-- Sort --}}
                    <label class="form-control w-full xl:w-52">
                        <div class="label py-0 pb-1.5"> 
                            <span class="label-text text-xs font-bold"> 
                                <i class="fa-solid fa-arrow-down-a-z mr-1"></i> Sort 
                            </span> 
                        </div>

                        <select name="sort" class="select select-bordered">
                            <option value="name_asc" @selected($sort === 'name_asc')>Name A–Z</option>
                            <option value="name_desc" @selected($sort === 'name_desc')>Name Z–A</option>
                            <option value="connections_desc" @selected($sort === 'connections_desc')>Most connections</option>
                            <option value="connections_asc" @selected($sort === 'connections_asc')>Least connections</option>
                        </select>
                    </label>
                </div>

                {{-- Filter actions --}}
                <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2 text-xs text-base-content/50">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-filter"></i> Filters applied:
                        </span>

                        @if($search)
                            <span class="badge badge-sm badge-primary gap-1.5">
                                <i class="fa-solid fa-magnifying-glass"></i> Search
                            </span>
                        @endif

                        @if($connectionStatus !== 'all')
                            <span class="badge badge-sm badge-info gap-1.5">
                                <i class="fa-solid fa-link"></i> {{ ucfirst($connectionStatus) }}
                            </span>
                        @endif

                        @if($activityStatus !== 'all')
                            <span class="badge badge-sm badge-warning gap-1.5">
                                <i class="fa-solid fa-power-off"></i> {{ ucfirst($activityStatus) }}
                            </span>
                        @endif

                        @if($search || $connectionStatus !== 'all' || $activityStatus !== 'all')
                            <a href="{{ route('admin.platform-connections.index') }}" class="link link-error font-semibold">Clear all</a>
                        @else
                            <span class="text-base-content/30">None</span>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary gap-2">
                        <i class="fa-solid fa-filter"></i> Apply Filters
                    </button>
                </div>
            </div>
        </form>

        {{-- Result summary --}}
        <div class="flex flex-col gap-2 px-5 py-3 text-sm sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-list text-base-content/40"></i>

                <span>
                    Showing <strong>{{ $platforms->count() }}</strong> {{ Str::plural('platform', $platforms->count()) }}
                </span>

                @if($search || $connectionStatus !== 'all' || $activityStatus !== 'all')
                    <span class="text-base-content/35">of</span>
                    <span class="font-semibold text-base-content/60"> {{ number_format($totalPlatforms) }} </span>
                @endif
            </div>

            <div class="text-xs text-base-content/40">
                <i class="fa-solid fa-circle-info mr-1"></i> Official platform accounts only
            </div>
        </div>
    </div>

    {{-- =================== PLATFORM DIRECTOR ====================== --}}
    <div class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
        {{-- Directory header --}}
        <div class="flex flex-col gap-3 border-b border-base-300 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 font-black">
                    <i class="fa-solid fa-diagram-project text-primary"></i> Platform Directory
                </div>

                <p class="mt-1 text-xs text-base-content/50">Each platform can have official accounts or profiles on other platforms.</p>
            </div>

            <div class="badge badge-outline gap-1.5">
                <i class="fa-solid fa-link"></i>
                {{ number_format($totalConnections) }}
                {{ Str::plural('connection',$totalConnections) }}
            </div>
        </div>

        {{-- Platform rows --}}
        <div class="divide-y divide-base-300">
            @forelse($platforms as $platform)
                <div class="group relative p-4 transition hover:bg-base-200/30 sm:p-5">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center">
                        
                        {{-- ==================== PLATFORM IDENTITY ======================= --}}
                        <div class="flex min-w-0 items-center gap-4 xl:w-80">
                            <div class="relative flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-base-300 bg-base-200 shadow-sm"
                                @if($platform->color)
                                    style="color: {{ $platform->color }}"
                                @endif>

                                @if($platform->logo)
                                    <img src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="size-full object-contain p-2">
                                @elseif($platform->icon)
                                    <i class="{{ $platform->icon }} text-xl"></i>
                                @else
                                    <span class="text-xl font-black"> {{ Str::upper(Str::substr($platform->name,0,1)) }} </span>
                                @endif

                                {{-- Activity indicator --}}
                                <span class="absolute bottom-1 right-1 size-2.5 rounded-full border-2 border-base-100 {{ $platform->is_active ? 'bg-success' : 'bg-warning' }}" title="{{ $platform->is_active ? 'Active' : 'Inactive' }}"></span>
                            </div>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="truncate font-black"> {{ $platform->name }} </span>

                                    @if($platform->is_active)
                                        <span class="tooltip" data-tip="Active platform">
                                            <i class="fa-solid fa-circle-check text-xs text-success"></i>
                                        </span>
                                    @else
                                        <span class="tooltip" data-tip="Inactive platform">
                                            <i class="fa-solid fa-circle-exclamation text-xs text-warning"></i>
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-0.5 flex items-center gap-1.5 truncate text-xs text-base-content/45">
                                    <i class="fa-solid fa-link text-[9px]"></i> {{ $platform->slug }}
                                </div>

                                <div class="mt-2 flex items-center gap-2">
                                    @if($platform->connected_platforms_count > 0)
                                        <span class="badge badge-success badge-sm gap-1">
                                            <i class="fa-solid fa-link"></i> {{ $platform->connected_platforms_count }}
                                        </span>

                                        <span class="text-[11px] text-base-content/45">
                                            {{ Str::plural('connection',$platform->connected_platforms_count) }}
                                        </span>
                                    @else
                                        <span class="badge badge-ghost badge-sm gap-1">
                                            <i class="fa-solid fa-link-slash"></i> None
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ======================= CONNECTION MAP ======================== --}}
                        <div class="min-w-0 flex-1">
                            @if($platform->connectedPlatforms->isNotEmpty())
                                <div class="mb-2 flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-base-content/40">
                                    <i class="fa-solid fa-share-nodes"></i> Official presence on
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @foreach($platform->connectedPlatforms as $connectedPlatform)
                                        <div class="group/connection flex items-center gap-2 rounded-lg border border-base-300 bg-base-200/50 px-2.5 py-2 transition hover:border-primary/40 hover:bg-base-200">
                                            <div class="flex size-7 shrink-0 items-center justify-center overflow-hidden rounded-md border border-base-300 bg-base-100">
                                                @if($connectedPlatform->logo)
                                                    <img src="{{ Storage::url($connectedPlatform->logo) }}" alt="{{ $connectedPlatform->name }}" class="size-full object-contain p-1">
                                                @elseif($connectedPlatform->icon)
                                                    <i class="{{ $connectedPlatform->icon }} text-xs"></i>
                                                @else
                                                    <span class="text-[10px] font-black"> {{ Str::upper(Str::substr($connectedPlatform->name,0,1)) }} </span>
                                                @endif
                                            </div>

                                            <span class="max-w-32 truncate text-xs font-bold"> {{ $connectedPlatform->name }} </span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-base-content/25 transition group-hover/connection:text-primary"></i>
                                        </div>
                                    @endforeach
                                </div>

                            @else
                                <div class="flex items-center gap-3 rounded-xl border border-dashed border-base-300 bg-base-200/20 px-4 py-3">
                                    <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-base-200 text-base-content/35">
                                        <i class="fa-solid fa-link-slash"></i>
                                    </div>

                                    <div>
                                        <div class="text-xs font-bold text-base-content/60">No connections configured</div>
                                        <div class="mt-0.5 text-[11px] text-base-content/40">Add the platform's official presence elsewhere.</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- ====================== ACTIONS ======================= --}}
                        <div class="flex shrink-0 items-center gap-2 xl:w-32 xl:justify-end">
                            <a href="{{ route('admin.platform-connections.edit',$platform) }}" class="btn btn-sm gap-2 {{ $platform->connected_platforms_count ? 'btn-outline' : 'btn-primary' }}">
                                @if($platform->connected_platforms_count)
                                    <i class="fa-solid fa-pen-to-square"></i> Manage
                                @else
                                    <i class="fa-solid fa-plus"></i> Add
                                @endif
                            </a>

                            <div class="dropdown dropdown-end">
                                <button tabindex="0" class="btn btn-sm btn-ghost btn-square">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>

                                <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-52 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                    <li>
                                        <a href="{{ route('admin.platform-connections.edit',$platform) }}">
                                            <i class="fa-solid fa-pen-to-square"></i> Manage connections
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('admin.platforms.index',['search'=>$platform->name]) }}">
                                            <i class="fa-solid fa-layer-group"></i> View platform
                                        </a>
                                    </li>

                                    @if($platform->connected_platforms_count)
                                        <li>
                                            <a href="{{ route('admin.platform-connections.edit',$platform) }}">
                                                <i class="fa-solid fa-list-check"></i> Review connections
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            @empty

                {{-- ======================= EMPTY STATE ========================= --}}
                <div class="px-6 py-20 text-center">
                    <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-base-200 text-base-content/30">
                        <i class="fa-solid fa-diagram-project text-2xl"></i>
                    </div>

                    @if($search || $connectionStatus !== 'all' || $activityStatus !== 'all')
                        <h3 class="mt-5 text-lg font-black">No matching platforms</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">No platforms match your current search and filter combination. Try changing your filters or clearing them.</p>

                        <a href="{{ route('admin.platform-connections.index') }}" class="btn btn-outline mt-5 gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    @else
                        <h3 class="mt-5 text-lg font-black">No platforms available</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">Add platforms to the directory before creating official cross-platform connections.</p>

                        <a href="{{ route('admin.platforms.index') }}" class="btn btn-primary mt-5 gap-2">
                            <i class="fa-solid fa-layer-group"></i> Platform Directory
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    {{-- ========================  FOOTER INFORMATION ============================== --}}
    @if($platforms->isNotEmpty())
        <div class="flex flex-col gap-3 rounded-xl border border-base-300 bg-base-100 px-5 py-4 text-xs text-base-content/50 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-success"></i>

                <span>
                    Connections represent <strong class="text-base-content/70">official platform accounts</strong>, not personal profiles.
                </span>
            </div>

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info"></i> CareerVault Platform Directory
            </div>
        </div>
    @endif
</div>
@endsection
