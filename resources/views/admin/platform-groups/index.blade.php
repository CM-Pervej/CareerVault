@extends('layouts.admin.app')

@section('title', 'Platform Groups')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <header class="relative overflow-hidden bg-white">
        <div class="relative flex flex-col gap-6 lg:flex-row justify-center items-center lg:justify-between">
            <div class="flex flex-col">
                <div class="flex justify-center sm:justify-start gap-3 shrink-0 items-center rounded-lg">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-users text-lg"></i>
                    </div>
                    <h1 class="cv-admin-title text-3xl sm:text-4xl whitespace-nowrap text-primary">Platform Groups</h1>
                </div>

                <p class="mt-2 text-sm leading-6 text-base-content/65 text-center sm:text-left">Manage groups and communities associated with job platforms.</p>
            </div>

            <div class="flex w-full gap-2 sm:w-auto">
                <a href="{{ route('admin.platform-groups.trash') }}" class="btn btn-ghost min-w-0 flex-1 gap-2 border border-base-300 sm:flex-none sm:border-transparent">
                    <i class="fa-solid fa-trash-can"></i> Trash
                    @if($trashedGroupsCount > 0)
                        <span class="badge badge-error badge-sm"> {{ number_format($trashedGroupsCount) }} </span>
                    @endif
                </a>

                <a href="{{ route('admin.platform-groups.create') }}" class="btn btn-primary min-w-0 flex-1 gap-2 whitespace-nowrap sm:flex-none">
                    <i class="fa-solid fa-plus"></i> Add Group
                </a>
            </div>
        </div>
    </header>

    {{-- ========================= STATISTICS ========================= --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="groupStates" open>
        <summary class="flex items-center justify-between gap-3 p-4 hover:bg-base-200/40">
            <span class="flex min-w-0 items-center gap-2.5">
                <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-base-200 text-base-content/60">
                    <i class="fa-solid fa-chart-simple text-xs"></i>
                </span>

                <span class="min-w-0">
                    <span class="block text-sm font-semibold">Statistics</span>
                    <span class="block truncate text-xs text-base-content/50">Overview of platform groups</span>
                </span>
            </span>

            <i class="fa-solid fa-chevron-down cv-chevron text-xs text-base-content/50"></i>
        </summary>

        {{-- ===================== OVERVIEW STATS ====================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Total --}}
            <div class="sm:card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-users"></i> Groups
                            </div>
                            <div class="mt-2 text-3xl font-black"> {{ number_format($total) }} </div>
                        </div>
                        <div class="flex size-10 items-center justify-center rounded-lg bg-info/10 text-info">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-database"></i> Total platform groups
                    </div>
                </div>
            </div>

            {{-- Active --}}
            <div class="sm:card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-circle-check"></i> Active
                            </div>
                            <div class="mt-2 text-3xl font-black"> {{ number_format($active) }} </div>
                        </div>
                        <div class="flex size-10 items-center justify-center rounded-lg bg-success/10 text-success">
                            <i class="fa-solid fa-toggle-on"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-eye"></i> Currently active groups
                    </div>
                </div>
            </div>

            {{-- Inactive --}}
            <div class="sm:card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-circle-exclamation"></i> Inactive
                            </div>
                            <div class="mt-2 text-3xl font-black"> {{ number_format($inactive) }} </div>
                        </div>
                        <div class="flex size-10 items-center justify-center rounded-lg bg-warning/10 text-warning">
                            <i class="fa-solid fa-toggle-off"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-eye-slash"></i> Hidden or inactive groups
                    </div>
                </div>
            </div>

            {{-- Trash --}}
            <a href="{{ route('admin.platform-groups.trash', $platformSlug ? ['platform' => $platformSlug] : []) }}" class="sm:card border border-base-300 bg-base-100 shadow-sm hover:border-error/30 hover:bg-error/[0.03]">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-trash-can"></i> Trash
                            </div>
                            <div class="mt-2 text-3xl font-black"> {{ number_format($trashedGroupsCount) }} </div>
                        </div>
                        <div class="flex size-10 items-center justify-center rounded-lg bg-error/10 text-error">
                            <i class="fa-solid fa-recycle"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-clock-rotate-left"></i> Soft-deleted groups
                    </div>
                </div>
            </a>
        </div>
    </details>

    {{-- ========================= FILTERS ========================= --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="groupFilters" open>
        <summary class="flex items-center justify-between gap-3 p-4 hover:bg-base-200/40">
            <span class="flex min-w-0 items-center gap-2.5">
                <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary">
                    <i class="fa-solid fa-sliders text-xs"></i>
                </span>

                <span class="min-w-0">
                    <span class="block text-sm font-semibold">Filter Groups</span>
                    <span class="block truncate text-xs text-base-content/50">Narrow the list by platform, type, access, or status</span>
                </span>
            </span>

            <i class="fa-solid fa-chevron-down cv-chevron text-xs text-base-content/50"></i>
        </summary>

        <div class="rounded-lg border border-base-300 bg-base-100 shadow-sm">
            <form method="GET" action="{{ route('admin.platform-groups.index') }}">
                {{-- Filter Fields --}}
                <div class="border-b border-base-300 p-4 sm:p-5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-7">
                        {{-- Search --}}
                        <div class="form-control min-w-0 sm:col-span-2 lg:col-span-2">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-brands fa-searchengin mr-1"></i> Search
                                </span>
                            </div>

                            <label class="input input-bordered flex w-full min-w-0 items-center gap-3">
                                <i class="fa-solid fa-magnifying-glass shrink-0 text-base-content/40"></i>
                                <input type="text" name="search" value="{{ $search }}" placeholder="Search groups..." class="min-w-0 grow"/>

                                @if($search)
                                    <a href="{{ route('admin.platform-groups.index', request()->except('search')) }}" class="btn btn-ghost btn-xs btn-circle shrink-0">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                @endif
                            </label>
                        </div>

                        {{-- Platform --}}
                        <label class="form-control min-w-0">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-solid fa-layer-group mr-1"></i> Platform
                                </span>
                            </div>

                            <select name="platform" class="select select-bordered w-full min-w-0">
                                <option value="">Select</option>
                                @foreach($platforms as $item)
                                    <option value="{{ $item->slug }}" @selected($platformSlug === $item->slug)> {{ $item->name }} </option>
                                @endforeach
                            </select>
                        </label>

                        {{-- Group Type --}}
                        <label class="form-control min-w-0">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-solid fa-shapes mr-1"></i> Group Type
                                </span>
                            </div>

                            <select name="group_type" class="select select-bordered w-full min-w-0">
                                <option value="">Select</option>
                                @foreach($groupTypes as $type)
                                    <option value="{{ $type }}" @selected($groupType === $type)> {{ $type }} </option>
                                @endforeach
                            </select>
                        </label>

                        {{-- Access --}}
                        <label class="form-control min-w-0">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-solid fa-lock mr-1"></i> Access
                                </span>
                            </div>

                            <select name="access_type" class="select select-bordered w-full min-w-0">
                                <option value="">Select</option>
                                <option value="public" @selected($accessType === 'public')>Public</option>
                                <option value="members_only" @selected($accessType === 'members_only')>Members Only</option>
                                <option value="private" @selected($accessType === 'private')>Private</option>
                            </select>
                        </label>

                        {{-- Status --}}
                        <label class="form-control min-w-0">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-solid fa-power-off mr-1"></i> Status
                                </span>
                            </div>

                            <select name="status" class="select select-bordered w-full min-w-0">
                                <option value="">Select</option>
                                <option value="active" @selected($status === 'active')>Active</option>
                                <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                            </select>
                        </label>

                        {{-- Bangladesh Focus --}}
                        <label class="form-control min-w-0">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-solid fa-flag mr-1"></i> Bangladesh Focus
                                </span>
                            </div>

                            <select name="bangladesh_focus" class="select select-bordered w-full min-w-0">
                                <option value="">Select</option>
                                <option value="focused" @selected($bangladeshFocus === 'focused')>Yes</option>
                                <option value="general" @selected($bangladeshFocus === 'general')>No</option>
                            </select>
                        </label>
                    </div>

                    {{-- Filter Actions --}}
                    <div class="mt-5 flex flex-col gap-4 border-t border-base-200 pt-4 sm:flex-row sm:items-center sm:justify-between">
                        {{-- Applied Filters --}}
                        <div class="flex min-w-0 flex-wrap items-center gap-2 text-xs text-base-content/50">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-filter"></i>
                                <span>Filters applied:</span>
                            </span>

                            @if($search)
                                <span class="badge badge-sm badge-primary gap-1.5">
                                    <i class="fa-solid fa-magnifying-glass"></i> Search
                                </span>
                            @endif

                            @if($platformSlug)
                                <span class="badge badge-sm badge-info gap-1.5">
                                    <i class="fa-solid fa-layer-group"></i> {{ $platform->name ?? $platformSlug }}
                                </span>
                            @endif

                            @if($groupType)
                                <span class="badge badge-sm badge-secondary gap-1.5">
                                    <i class="fa-solid fa-shapes"></i> {{ $groupType }}
                                </span>
                            @endif

                            @if($accessType)
                                <span class="badge badge-sm badge-accent gap-1.5">
                                    <i class="fa-solid fa-lock"></i> {{ str_replace('_', ' ', ucfirst($accessType)) }}
                                </span>
                            @endif

                            @if($status !== null)
                                <span class="badge badge-sm badge-warning gap-1.5">
                                    <i class="fa-solid fa-power-off"></i> {{ ucfirst($status) }}
                                </span>
                            @endif

                            @if($bangladeshFocus)
                                <span class="badge badge-sm badge-success gap-1.5">
                                    <i class="fa-solid fa-flag"></i> {{ $bangladeshFocus === 'focused' ? 'Bangladesh Focused' : 'General' }}
                                </span>
                            @endif

                            @if($search || $platformSlug || $groupType || $accessType || $status !== null || $bangladeshFocus)
                                <a href="{{ route('admin.platform-groups.index') }}" class="link link-error font-semibold">Clear all</a>
                            @else
                                <span class="text-base-content/30">None</span>
                            @endif
                        </div>

                        {{-- Apply --}}
                        <button type="submit" class="btn btn-sm btn-primary w-full shrink-0 gap-2 sm:w-auto">
                            <i class="fa-solid fa-filter"></i> Apply Filters
                        </button>
                    </div>
                </div>

                {{-- Result Summary --}}
                <div class="flex flex-col gap-2 px-5 py-3 text-sm sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-list text-base-content/40"></i>

                        <span>
                            Showing <strong>{{ $groups->count() }}</strong> {{ $groups->count() === 1 ? 'group' : 'groups' }}
                        </span>

                        @if($search || $platformSlug || $groupType || $accessType || $status !== null || $bangladeshFocus)
                            <span class="text-base-content/35">of</span>
                            <span class="font-semibold text-base-content/60"> {{ number_format($total) }} </span>
                        @endif
                    </div>

                    <div class="text-xs text-base-content/40">
                        <i class="fa-solid fa-circle-info mr-1"></i> Platform groups and communities
                    </div>
                </div>
            </form>
        </div>
    </details>

    {{-- ======================= ACTIVE FILTERS ===================== --}}
    @if($search || $platform || $groupType || $accessType || $status !== null || $bangladeshFocus)
        <div class="flex flex-wrap items-center gap-2 px-1">
            <span class="text-xs font-semibold text-base-content/50">Active filters:</span>

            @if($search)
                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Search: {{ $search }}
                </span>
            @endif

            @if($platform)
                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-layer-group"></i> Platform: {{ $platform->name }}
                </span>
            @endif

            @if($groupType)
                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-shapes"></i> Type: {{ $groupType }}
                </span>
            @endif

            @if($accessType)
                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-lock"></i> Access: {{ str_replace('_', ' ', ucfirst($accessType)) }}
                </span>
            @endif

            @if($status !== null)
                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-power-off"></i> Status: {{ ucfirst($status) }}
                </span>
            @endif

            @if($bangladeshFocus)
                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-flag"></i> {{ $bangladeshFocus === 'focused' ? 'Bangladesh Focused' : 'General' }}
                </span>
            @endif
        </div>
    @endif

    {{-- ======================= GROUP DIRECTORY ===================== --}}
    <div class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-10 sm:mt-5">
        {{-- Directory Header --}}
        <div class="px-5 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-users"></i>
                    </div>

                    <span class="whitespace-nowrap">Platform Group Directory</span>

                    <button type="button" class="btn btn-ghost btn-xs btn-circle text-base-content/50 hover:text-primary" onclick="document.getElementById('platform-access-info-modal').showModal()" aria-label="Platform access information" title="About platform access">
                        <i class="fa-solid fa-circle-info"></i>
                    </button>
                </div>

                <div class="badge badge-outline gap-1.5">
                    <i class="fa-solid fa-users"></i>
                    {{ number_format($groups->total()) }}
                    {{ $groups->total() === 1 ? 'group' : 'groups' }}
                </div>
            </div>

            <p class="mt-3 text-center sm:text-start text-xs text-base-content/50">Groups and communities associated with job platforms.</p>
        </div>

        {{-- ======================= DESKTOP TABLE ======================= --}}
        <div class="divide-y divide-base-300">
            @forelse($groups as $group)
                <div class="group relative p-4 transition hover:bg-base-200/30 sm:p-5 border border-gray-300 sm:border-none shadow-sm sm:shadow-none my-2 sm:my-0">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center">
                        {{-- ================= PAGE IDENTITY ================= --}}
                        <div class="flex min-w-0 items-center gap-4 xl:w-[360px]">
                            {{-- Page Icon --}}
                            <div class="relative flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-base-300 bg-base-200 shadow-sm">
                                @if($group->logo)
                                    <img src="{{ Storage::url($group->logo) }}" alt="{{ $group->name }}" class="size-full object-contain p-2">
                                @elseif($group->platform?->logo)
                                    <img src="{{ Storage::url($group->platform->logo) }}" alt="{{ $group->platform->name }}" class="size-full object-contain p-2">
                                @elseif($group->platform?->icon)
                                    <i class="{{ $group->platform->icon }} text-5xl"
                                       @if($group->platform->color)
                                           style="color: {{ $group->platform->color }}"
                                       @endif>
                                    </i>
                                @else
                                    <i class="fa-solid fa-file-lines text-xl text-primary"></i>
                                @endif

                                {{-- Status Indicator --}}
                                <span class="absolute bottom-1 right-1 size-2.5 rounded-full border-2 border-base-100 {{ $group->is_active ? 'bg-success' : 'bg-warning' }}" title="{{ $group->is_active ? 'Active' : 'Inactive' }}"></span>
                            </div>

                            {{-- Page Information --}}
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 w-62">
                                    <a href="{{ route('admin.platform-groups.show',$group) }}" class="truncate font-black"> {{ $group->name }} </a>

                                    @if($group->is_active)
                                        <span class="tooltip" data-tip="Active group">
                                            <i class="fa-solid fa-circle-check text-xs text-success"></i>
                                        </span>
                                    @else
                                        <span class="tooltip" data-tip="Inactive group">
                                            <i class="fa-solid fa-circle-exclamation text-xs text-warning"></i>
                                        </span>
                                    @endif
                                </div>

                                @if($group->short_desc)
                                    <div class="mt-1.5 line-clamp-1 text-xs text-base-content/50"> {{ $group->short_desc }} </div>
                                @elseif($group->platform)
                                <div class="mt-0.5 flex items-center gap-1.5 truncate text-xs text-base-content/45">
                                    <i class="fa-solid fa-layer-group text-[9px]"></i> {{ $group->platform?->name ?? 'Unassigned platform' }}
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- ================= Group DETAILS ================= --}}
                        <div class="min-w-0 flex-1">
                            <div class="mb-2 flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-base-content/40 whitespace-nowrap">
                                <i class="fa-solid fa-circle-info"></i> Group Details
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                {{-- Type --}}
                                @php
                                    $groupTypeIcon = match(Str::lower($group->group_type ?? '')) {
                                        'career' => 'fa-solid fa-briefcase',
                                        'jobs' => 'fa-solid fa-briefcase',
                                        'job' => 'fa-solid fa-briefcase',
                                        'company' => 'fa-solid fa-building',
                                        'profile' => 'fa-solid fa-user',
                                        'social' => 'fa-solid fa-share-nodes',
                                        'community' => 'fa-solid fa-users',
                                        'support' => 'fa-solid fa-life-ring',
                                        'help' => 'fa-solid fa-circle-question',
                                        'blog' => 'fa-solid fa-newspaper',
                                        'resource' => 'fa-solid fa-book-open',
                                        default => 'fa-solid fa-file-lines',
                                    };
                                @endphp

                                <div class="flex flex-wrap items-center gap-2 rounded-2xl px-1" style="border: 1px solid {{ $group->platform->color }}">
                                    <div class="relative flex size-6 shrink-0 items-center justify-center overflow-hidden rounded-lg">
                                        @if($group->platform?->icon)
                                            <i class="{{ $group->platform->icon }}"
                                            @if($group->platform->color)
                                                style="color: {{ $group->platform->color }}"
                                            @endif>
                                            </i>
                                        @else
                                            <i class="fa-solid fa-file-lines text-xl text-primary"></i>
                                        @endif
                                    </div>
                                    <span class="text-sm font-medium" style="color: {{ $group->platform->color }}">{{ $group->platform->name }}</span>
                                </div>

                                <span class="badge badge-sm badge-outline gap-1.5">
                                    <i class="{{ $groupTypeIcon }}"></i> {{ $group->group_type ?: 'General' }}
                                </span>

                                {{-- Status --}}
                                @if($group->is_active)
                                    <span class="badge badge-sm badge-success gap-1.5">
                                        <i class="fa-solid fa-circle-check"></i> Active
                                    </span>
                                @else
                                    <span class="badge badge-sm badge-warning gap-1.5">
                                        <i class="fa-solid fa-circle-exclamation"></i> Inactive
                                    </span>
                                @endif

                                <span class="badge badge-sm badge-outline gap-1.5">
                                    <i class="fa-solid fa-lock"></i>
                                    {{ $group->access_type }}
                                </span>

                                <span>
                                    @if($group->is_bangladesh_focused)
                                        <span class="badge badge-success badge-sm gap-1">
                                            <i class="fa-solid fa-flag"></i>
                                            Bangladesh
                                        </span>
                                    @else
                                        <span class="badge badge-sm badge-outline text-base-content/40">International</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- ======================= Desktop ACTIONS ======================= --}}
                        <div class="hidden sm:flex shrink-0 items-center gap-2 xl:w-36 xl:justify-end">
                            <a href="{{ route('admin.platform-groups.show',$group) }}" class="btn btn-sm btn-outline gap-2">
                                <i class="fa-solid fa-eye"></i> View
                            </a>

                            <div class="dropdown dropdown-center dropdown-left">
                                <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>

                                <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                    <li>
                                        <a href="{{ route('admin.platform-groups.show',$group) }}">
                                            <i class="fa-solid fa-eye"></i> View group
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('admin.platform-groups.edit',$group) }}">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit group
                                        </a>
                                    </li>

                                    @if($group->platform)
                                        <li>
                                            <a href="{{ route('admin.platforms.show',$group->platform) }}">
                                                <i class="fa-solid fa-layer-group"></i> View platform
                                            </a>
                                        </li>
                                    @endif

                                    <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                        <span>Danger zone</span>
                                    </li>

                                    <li>
                                        @can('delete',$group)
                                            <button
                                                {{-- type="button" --}}
                                                class="text-error flex items-center gap-2 hover:bg-error/10"
                                                title="Move group to trash"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platform-groups.destroy',$group->slug) }}"
                                                data-action-method="DELETE"
                                                data-action-type="danger"
                                                data-action-title="Move Platform group to Trash"
                                                data-action-description="Move <strong>“{{ $group->name }}”</strong> to trash. You can restore it later from the Trash group."
                                                data-action-icon="fa-solid fa-trash-can"
                                                data-action-confirm-icon="fa-solid fa-trash-can"
                                                data-action-confirm-text="Move to Trash"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>
                                                <span class="hidden sm:inline"> Move to Trash</span>
                                            </button>
                                        @endcan
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- ======================= Mobile ACTIONS ======================= --}}
                        <div class="sm:hidden flex items-center justify-between">
                            <a href="{{ route('admin.platform-groups.show',$group) }}" class="btn btn-sm btn-outline">
                                <i class="fa-solid fa-eye"></i> View
                            </a>

                            <a href="{{ route('admin.platform-groups.edit',$group) }}" class="btn btn-sm btn-outline">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>

                            <a href="{{ route('admin.platforms.show',$group->platform) }}" class="btn btn-sm btn-outline">
                                <i class="fa-solid fa-layer-group"></i> platform
                            </a>

                            @can('delete',$group)
                                <button
                                    {{-- type="button" --}}
                                    class="text-error flex items-center hover:bg-error/10 btn btn-sm btn-outline border-error"
                                    title="Move group to trash"
                                    data-admin-action
                                    data-action-url="{{ route('admin.platform-groups.destroy',$group->slug) }}"
                                    data-action-method="DELETE"
                                    data-action-type="danger"
                                    data-action-title="Move Platform Group to Trash"
                                    data-action-description="Move <strong>“{{ $group->name }}”</strong> to trash. You can restore it later from the Trash group."
                                    data-action-icon="fa-solid fa-trash-can"
                                    data-action-confirm-icon="fa-solid fa-trash-can"
                                    data-action-confirm-text="Move to Trash"
                                >
                                    <i class="fa-solid fa-trash-can"></i> Trash
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>

            @empty
                {{-- ======================= EMPTY STATE ======================= --}}
                <div class="px-6 py-20 text-center">
                    <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-base-200 text-base-content/30">
                        <i class="fa-solid fa-file-circle-xmark text-2xl"></i>
                    </div>

                    @if($search || $platformSlug || $groupType || $status)
                        <h3 class="mt-5 text-lg font-black">No matching groups</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">No platform groups match your current search and filter combination. Try changing your filters or clearing them.</p>
                        <a href="{{ route('admin.platform-groups.index') }}" class="btn btn-outline mt-5 gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    @else
                        <h3 class="mt-5 text-lg font-black">No platform groups available</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">Create the first official groups or resource for your platform directory.</p>
                        <a href="{{ route('admin.platform-groups.create') }}" class="btn btn-primary mt-5 gap-2">
                            <i class="fa-solid fa-plus"></i> Add Platform Page
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- ======================== PAGINATION ======================== --}}
        @if($groups->hasPages())
            <div class="border-t border-base-300 px-5 py-4"> {{ $groups->withQueryString()->links() }} </div>
        @endif
    </div>

    {{-- ====================== FOOTER INFORMATION ===================== --}}
    @if($groups->isNotEmpty())
        <div class="flex flex-col gap-3 py-4 sm:py-2 text-xs text-base-content/50 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-users text-primary"></i>

                <span>
                    Platform groups represent <strong class="text-base-content/70">communities and group destinations</strong> maintained in CareerVault.
                </span>
            </div>

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info"></i> CareerVault Platform Directory
            </div>
        </div>
    @endif
</div>

{{-- ================= PLATFORM ACCESS INFORMATION ================= --}}
<dialog id="platform-access-info-modal" class="modal">
    <div class="modal-box max-w-lg overflow-hidden rounded-2xl p-0 shadow-2xl">
        {{-- Header --}}
        <div class="border-b border-base-300 px-6 py-5">
            <div class="flex items-start gap-4">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                    <i class="fa-solid fa-circle-info text-lg"></i>
                </div>

                <div class="min-w-0">
                    <h3 class="text-lg font-black">About External Pages & Groups</h3>
                    <p class="mt-1 text-xs leading-5 text-base-content/50">A quick note about access to destinations listed in CareerVault.</p>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="space-y-4 px-6 py-5">
            <p class="text-sm leading-6 text-base-content/70">CareerVault links to destinations hosted on external platforms. Access requirements are determined by those platforms.</p>

            {{-- Pages --}}
            <div class="rounded-xl border border-success/20 bg-success/5 p-4">
                <div class="flex items-start gap-3.5">
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>

                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold">Pages</h4>
                            <span class="badge badge-success badge-xs">Generally Open</span>
                        </div>

                        <p class="mt-1.5 text-xs leading-5 text-base-content/60">Pages are generally open to visit without joining or subscribing. Some actions or content may still require an account on the external platform.</p>
                    </div>
                </div>
            </div>

            {{-- Groups --}}
            <div class="rounded-xl border border-warning/20 bg-warning/5 p-4">
                <div class="flex items-start gap-3.5">
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-warning/10 text-warning">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold">Groups</h4>
                            <span class="badge badge-warning badge-xs">Access Varies</span>
                        </div>
                        <p class="mt-1.5 text-xs leading-5 text-base-content/60">Group access depends on the platform's privacy and membership rules. Joining, subscribing, or approval may be required.</p>
                    </div>
                </div>
            </div>

            {{-- Disclaimer --}}
            <div class="flex items-start gap-3 rounded-xl bg-base-200/60 px-4 py-3.5">
                <i class="fa-solid fa-shield-halved mt-0.5 text-xs text-base-content/45"></i>
                <p class="text-xs leading-5 text-base-content/50">CareerVault does not control external platform access requirements, membership rules, privacy settings, or account requirements.</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end border-t border-base-300 bg-base-200/30 px-6 py-4">
            <form method="dialog">
                <button type="submit" class="btn btn-primary btn-sm gap-2">
                    <i class="fa-solid fa-check"></i> Got it
                </button>
            </form>
        </div>
    </div>

    {{-- Backdrop --}}
    <form method="dialog" class="modal-backdrop">
        <button type="submit" aria-label="Close information">close</button>
    </form>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded',function(){
        const modal=document.getElementById('platform-access-info-modal');

        if(!modal)return;

        if(!sessionStorage.getItem('careervault_platform_access_info_seen')){
            modal.showModal();

            modal.addEventListener('close',function(){
                sessionStorage.setItem('careervault_platform_access_info_seen','1');
            },{once:true});
        }
    });
</script>
@endsection