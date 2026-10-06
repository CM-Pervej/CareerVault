@extends('layouts.admin.app')

@section('title', 'Platform Pages Trash | CareerVault')
@section('page_title', 'Pages / Trash')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- Header --}}
    <header class="relative overflow-hidden sm:mb-6">
        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">                
                <div>
                    <div class="flex gap-3 size-11 shrink-0 items-center rounded-lg bg-error/10">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                            <i class="fa-solid fa-trash-can"></i>
                        </div>
                        <h1 class="cv-admin-title text-3xl sm:text-4xl whitespace-nowrap text-error">Platform Pages Trash</h1>
                    </div>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-base-content/65 text-center sm:text-left">Deleted platform pages remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records.</p>

                    <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-2">
                        <span class="badge badge-error badge-outline gap-1">
                            <i class="fa-solid fa-trash text-[10px]"></i> Soft Deleted
                        </span>

                        <span class="badge badge-warning badge-outline gap-1">
                            <i class="fa-solid fa-lock text-[10px]"></i> Super Admin Access
                        </span>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.platform-pages.index') }}" class="btn btn-outline gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Pages
            </a>
        </div>
    </header>

    {{-- Statistics --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="pageTrashStates" open>
        <summary class="flex items-center justify-between gap-3 p-4 hover:bg-base-200/40">
            <span class="flex min-w-0 items-center gap-2.5">
                <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-base-200 text-base-content/60">
                    <i class="fa-solid fa-chart-simple text-xs"></i>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold">Statistics</span>
                    <span class="block truncate text-xs text-base-content/50">Overview of deleted groups</span>
                </span>
            </span>

            <i class="fa-solid fa-chevron-down cv-chevron text-xs text-base-content/50"></i>
        </summary>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 gap-0 sm:gap-1 md:grid-cols-3 p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Deleted Pages --}}
            <div class="group relative overflow-hidden rounded-lg border border-base-300 bg-base-100 p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-error/5 transition duration-300 group-hover:scale-125"></div>
    
                <div class="relative flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-[11px] font-black uppercase tracking-[0.16em] text-base-content/45">Deleted Pages</p>
    
                            <span class="tooltip" data-tip="Deleted pages matching the current filters">
                                <i class="fa-regular fa-circle-question text-xs text-base-content/30"></i>
                            </span>
                        </div>
    
                        <div class="mt-2 flex items-end gap-2">
                            <p class="text-3xl font-black tracking-tight"> {{ number_format($pages->total()) }} </p>
    
                            @if($pages->total() > 0)
                                <span class="mb-1 text-xs font-bold text-error">In Trash</span>
                            @endif
                        </div>
    
                        <p class="mt-1 text-xs text-base-content/45">Deleted pages matching the current filters</p>
                    </div>
    
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-2xl border border-error/15 bg-error/10 text-error transition duration-200 group-hover:scale-105">
                        <i class="fa-solid fa-trash-can text-lg"></i>
                    </div>
                </div>
            </div>
    
            {{-- Filtered Results --}}
            <div class="group relative overflow-hidden rounded-lg border border-base-300 bg-base-100 p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-primary/5 transition duration-300 group-hover:scale-125"></div>
    
                <div class="relative flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-[11px] font-black uppercase tracking-[0.16em] text-base-content/45">Filtered Results</p>
    
                            <span class="tooltip" data-tip="Deleted pages displayed on the current page">
                                <i class="fa-regular fa-circle-question text-xs text-base-content/30"></i>
                            </span>
                        </div>
    
                        <div class="mt-2 flex items-end gap-2">
                            <p class="text-3xl font-black tracking-tight"> {{ number_format($pages->count()) }} </p>
    
                            @if($pages->hasPages())
                                <span class="mb-1 text-xs font-bold text-primary">Page {{ $pages->currentPage() }} </span>
                            @endif
                        </div>
    
                        <p class="mt-1 text-xs text-base-content/45">Results currently displayed on this page</p>
                    </div>
    
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-2xl border border-primary/15 bg-primary/10 text-primary transition duration-200 group-hover:scale-105">
                        <i class="fa-solid fa-list-check text-lg"></i>
                    </div>
                </div>
            </div>
    
            {{-- Access Control --}}
            <div class="group relative overflow-hidden rounded-lg border border-base-300 bg-base-100 p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-warning/5 transition duration-300 group-hover:scale-125"></div>
    
                <div class="relative flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.16em] text-base-content/45">Access Control</p>
    
                        <div class="mt-2 flex items-center gap-2">
                            <p class="text-xl font-black whitespace-nowrap">Super Admin</p>
    
                            <span class="badge badge-warning badge-outline gap-1 text-[10px] font-bold">
                                <i class="fa-solid fa-lock text-[9px]"></i> Restricted
                            </span>
                        </div>
    
                        <p class="mt-1 text-xs text-base-content/45">Restore and permanent deletion permissions</p>
                    </div>
    
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-2xl border border-warning/15 bg-warning/10 text-warning transition duration-200 group-hover:scale-105">
                        <i class="fa-solid fa-shield-halved text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </details>

    {{-- =========================== Filters =========================== --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="pageFilters" open>
        <summary class="flex items-center justify-between gap-3 p-4 hover:bg-base-200/40">
            <span class="flex min-w-0 items-center gap-2.5">
                <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary">
                    <i class="fa-solid fa-sliders text-xs"></i>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold">Filter Pages</span>
                    <span class="block truncate text-xs text-base-content/50">Narrow the trash list by platform, type, access, or deletion details</span>
                </span>
            </span>

            <i class="fa-solid fa-chevron-down cv-chevron text-xs text-base-content/50"></i>
        </summary>

        <div class="rounded-lg border border-base-300 bg-base-100 shadow-sm">
            <form method="GET" action="{{ route('admin.platform-pages.trash') }}">
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
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search pages..." class="min-w-0 grow"/>

                                @if(request('search'))
                                    <a href="{{ route('admin.platform-pages.trash', request()->except('search')) }}" class="btn btn-ghost btn-xs btn-circle shrink-0">
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
                                    <option value="{{ $item->id }}" @selected(request('platform') == $item->id)>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </label>

                        {{-- Page Type --}}
                        <label class="form-control min-w-0">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-solid fa-shapes mr-1"></i> Page Type
                                </span>
                            </div>

                            <select name="type" class="select select-bordered w-full min-w-0">
                                <option value="">Select</option>

                                @foreach($pageTypes as $type)
                                    <option value="{{ $type }}" @selected(request('type') === $type)>
                                        {{ $type }}
                                    </option>
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
                                <option value="public" @selected(request('access_type') === 'public')>Public</option>
                                <option value="members_only" @selected(request('access_type') === 'members_only')>Members Only</option>
                                <option value="private" @selected(request('access_type') === 'private')>Private</option>
                            </select>
                        </label>

                        {{-- Deleted By --}}
                        <label class="form-control min-w-0">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-solid fa-user-xmark mr-1"></i> Deleted By
                                </span>
                            </div>

                            <select name="deleted_by" class="select select-bordered w-full min-w-0">
                                <option value="">Select</option>

                                @foreach($deletedByUsers as $user)
                                    <option value="{{ $user->id }}" @selected(request('deleted_by') == $user->id)>
                                        {{ $user->name }}
                                        @if($user->role)
                                            ({{ ucfirst(str_replace('_', ' ', $user->role)) }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </label>

                        {{-- Deleted Period --}}
                        <label class="form-control min-w-0">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-solid fa-calendar-days mr-1"></i> Deleted Period
                                </span>
                            </div>

                            <select name="deleted_period" class="select select-bordered w-full min-w-0">
                                <option value="">Select</option>
                                <option value="today" @selected(request('deleted_period') === 'today')>Today</option>
                                <option value="yesterday" @selected(request('deleted_period') === 'yesterday')>Yesterday</option>
                                <option value="last_7_days" @selected(request('deleted_period') === 'last_7_days')>Last 7 Days</option>
                                <option value="last_30_days" @selected(request('deleted_period') === 'last_30_days')>Last 30 Days</option>
                                <option value="last_3_months" @selected(request('deleted_period') === 'last_3_months')>Last 3 Months</option>
                                <option value="this_year" @selected(request('deleted_period') === 'this_year')>This Year</option>
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

                            {{-- Search --}}
                            @if(request('search'))
                                <span class="badge badge-sm badge-primary gap-1.5">
                                    <i class="fa-solid fa-magnifying-glass"></i> Search
                                </span>
                            @endif

                            {{-- Platform --}}
                            @if(request('platform'))
                                @php
                                    $selectedPlatform = $platforms->firstWhere('id', request('platform'));
                                @endphp

                                <span class="badge badge-sm badge-info gap-1.5">
                                    <i class="fa-solid fa-layer-group"></i> {{ $selectedPlatform?->name ?? request('platform') }}
                                </span>
                            @endif

                            {{-- Page Type --}}
                            @if(request('type'))
                                <span class="badge badge-sm badge-secondary gap-1.5">
                                    <i class="fa-solid fa-shapes"></i> {{ request('type') }}
                                </span>
                            @endif

                            {{-- Access --}}
                            @if(request('access_type'))
                                <span class="badge badge-sm badge-accent gap-1.5">
                                    <i class="fa-solid fa-lock"></i> {{ ucfirst(str_replace('_', ' ', request('access_type'))) }}
                                </span>
                            @endif

                            {{-- Deleted By --}}
                            @if(request('deleted_by'))
                                @php
                                    $selectedDeletedBy = $deletedByUsers->firstWhere('id', request('deleted_by'));
                                @endphp

                                <span class="badge badge-sm badge-warning gap-1.5">
                                    <i class="fa-solid fa-user-xmark"></i> {{ $selectedDeletedBy?->name ?? request('deleted_by') }}
                                </span>
                            @endif

                            {{-- Deleted Period --}}
                            @if(request('deleted_period'))
                                @php
                                    $deletedPeriodLabels = [
                                        'today' => 'Today',
                                        'yesterday' => 'Yesterday',
                                        'last_7_days' => 'Last 7 Days',
                                        'last_30_days' => 'Last 30 Days',
                                        'last_3_months' => 'Last 3 Months',
                                        'this_year' => 'This Year',
                                    ];
                                @endphp

                                <span class="badge badge-sm badge-success gap-1.5">
                                    <i class="fa-solid fa-calendar-days"></i> {{ $deletedPeriodLabels[request('deleted_period')] ?? request('deleted_period') }}
                                </span>
                            @endif

                            {{-- Clear All --}}
                            @if(request('search') || request('platform') || request('type') || request('access_type') || request('deleted_by') || request('deleted_period'))
                                <a href="{{ route('admin.platform-pages.trash') }}" class="link link-error font-semibold">Clear all</a>
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
                            Showing <strong>{{ $pages->count() }}</strong> {{ $pages->count() === 1 ? 'page' : 'pages' }}
                        </span>

                        @if(request('search') || request('platform') || request('type') || request('access_type') || request('deleted_by') || request('deleted_period'))
                            <span class="text-base-content/35">of</span>
                            <span class="font-semibold text-base-content/60">{{ number_format($pages->total()) }}</span>
                        @endif
                    </div>

                    <div class="text-xs text-base-content/40">
                        <i class="fa-solid fa-circle-info mr-1"></i> Deleted platform pages
                    </div>
                </div>
            </form>
        </div>
    </details>

    {{-- ======================= ACTIVE FILTERS ===================== --}}
    @if(request('search') || request('platform') || request('type') || request('access_type') || request('deleted_by') || request('deleted_period'))
        <div class="flex flex-wrap items-center gap-2 px-1">
            <span class="text-xs font-semibold text-base-content/50">Active filters:</span>

            {{-- Search --}}
            @if(request('search'))
                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Search: {{ request('search') }}
                </span>
            @endif

            {{-- Platform --}}
            @if(request('platform'))
                @php
                    $selectedPlatform = $platforms->firstWhere('id', request('platform'));
                @endphp

                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-layer-group"></i> Platform: {{ $selectedPlatform?->name ?? request('platform') }}
                </span>
            @endif

            {{-- Page Type --}}
            @if(request('type'))
                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-shapes"></i> Type: {{ request('type') }}
                </span>
            @endif

            {{-- Access --}}
            @if(request('access_type'))
                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-lock"></i> Access: {{ ucfirst(str_replace('_', ' ', request('access_type'))) }}
                </span>
            @endif

            {{-- Deleted By --}}
            @if(request('deleted_by'))
                @php
                    $selectedDeletedBy = $deletedByUsers->firstWhere('id', request('deleted_by'));
                @endphp

                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-user-xmark"></i> Deleted By: {{ $selectedDeletedBy?->name ?? request('deleted_by') }}
                </span>
            @endif

            {{-- Deleted Period --}}
            @if(request('deleted_period'))
                @php
                    $deletedPeriodLabels = [
                        'today' => 'Today',
                        'yesterday' => 'Yesterday',
                        'last_7_days' => 'Last 7 Days',
                        'last_30_days' => 'Last 30 Days',
                        'last_3_months' => 'Last 3 Months',
                        'this_year' => 'This Year',
                    ];
                @endphp

                <span class="badge badge-sm badge-outline gap-1">
                    <i class="fa-solid fa-calendar-days"></i> Period: {{ $deletedPeriodLabels[request('deleted_period')] ?? request('deleted_period') }}
                </span>
            @endif
        </div>
    @endif

    {{-- Trash List --}}
    <div class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-10 sm:mt-5">
        {{-- Toolbar --}}
        <div class="px-5 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2 font-black">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    Deleted Platform Pages
                </div>
                <span class="badge badge-error badge-outline gap-1">
                    <i class="fa-solid fa-box-archive text-[10px]"></i> Trash
                </span>
            </div>

            <p class="mt-3 text-center sm:text-start text-xs text-base-content/50">
                {{ number_format($pages->count()) }} page(s) shown
                •
                {{ number_format($pages->total()) }} total in current result
            </p>
        </div>

        @if($pages->count())
            {{-- ========================= Desktop Table ====================== --}}
            <div class="hidden overflow-x-auto md:block bg-base-100">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200/50 text-xs uppercase tracking-wider text-base-content/50">
                            <th>Page</th> <th>Platform</th> <th>Type</th> <th>Deleted</th> <th>Deleted By</th> <th class="text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($pages as $page)
                            <tr class="hover:bg-base-200/40">
                                {{-- Page --}}
                                <td>
                                    <div class="flex min-w-[280px] items-start gap-3">
                                        {{-- Page Icon --}}
                                        <div class="relative flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-base-300 bg-base-200 shadow-sm">
                                            @if($page->platform?->logo)
                                                <img src="{{ Storage::url($page->platform->logo) }}" alt="{{ $page->platform->name }}" class="size-full object-contain p-2">
                                            @elseif($page->platform?->icon)
                                                <i class="{{ $page->platform->icon }} text-5xl"
                                                @if($page->platform->color)
                                                    style="color: {{ $page->platform->color }}"
                                                @endif>
                                                </i>
                                            @else
                                                <i class="fa-solid fa-file-lines text-xl text-primary"></i>
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <a href="{{ route('admin.platform-pages.show', $page) }}" class="font-bold transition hover:text-primary">
                                                {{ $page->name }}
                                            </a>

                                            @if($page->short_desc)
                                                <p class="mt-1 line-clamp-2 max-w-[200px] text-xs leading-5 text-base-content/55">
                                                    {{ $page->short_desc }}
                                                </p>
                                            @endif

                                            @if($page->url)
                                                <div class="mt-2 flex max-w-[200px] items-center gap-1.5 text-[11px] text-base-content/45">
                                                    <i class="fa-solid fa-link shrink-0"></i>

                                                    <span class="truncate" title="{{ $page->url }}">
                                                        {{ parse_url($page->url, PHP_URL_HOST) }}{{ parse_url($page->url, PHP_URL_PATH) ?: '/' }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Platform --}}
                                <td>
                                    @if($page->platform)
                                        <a href="{{ route('admin.platforms.show', $page->platform) }}" class="inline-flex items-center gap-2 rounded-xl border border-base-300 bg-base-200 px-2.5 py-1.5 text-xs font-bold transition hover:border-base-content/20 hover:bg-base-300">
                                            @if($page->platform->icon)
                                                <i class="{{ $page->platform->icon }}" style="{{ $page->platform->color ? 'color: '.$page->platform->color : '' }}"></i>
                                            @else
                                                <i class="fa-solid fa-globe" style="{{ $page->platform->color ? 'color: '.$page->platform->color : '' }}"></i>
                                            @endif

                                            {{ $page->platform->name }}
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs text-base-content/40">
                                            <i class="fa-solid fa-circle-exclamation"></i> Platform unavailable
                                        </span>
                                    @endif
                                </td>

                                {{-- Type --}}
                                <td>
                                    @php
                                        $typeColor = match(strtolower($page->page_type ?? '')) {
                                            'jobs' => 'badge-success',
                                            'company' => 'badge-info',
                                            'network' => 'badge-primary',
                                            'career' => 'badge-secondary',
                                            default => 'badge-ghost',
                                        };
                                    @endphp

                                    <span class="badge {{ $typeColor }} badge-sm whitespace-nowrap">
                                        {{ $page->page_type ?: 'Unknown' }}
                                    </span>
                                </td>

                                {{-- Deleted --}}
                                <td>
                                    <div class="flex items-start gap-2">
                                        <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                            <i class="fa-solid fa-clock text-xs"></i>
                                        </div>

                                        <div class="whitespace-nowrap">
                                            <div class="text-sm font-bold">
                                                {{ $page->deleted_at?->format('d M Y, h:i A') }}
                                            </div>

                                            <div class="text-xs text-base-content/50">
                                                {{ $page->deleted_at?->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Deleted By --}}
                                <td class="py-4">
                                    @if($page->deletedBy)
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                                <i class="fa-solid fa-user-shield text-xs"></i>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold" title="{{ $page->deletedBy->name }}">
                                                    {{ $page->deletedBy->name }}
                                                </div>

                                                <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40">
                                                    {{ str_replace('_', ' ', $page->deletedBy->role) }}
                                                </div>
                                            </div>
                                        </div>

                                    @else
                                        <div class="flex items-center gap-2 text-xs text-base-content/35">
                                            <i class="fa-solid fa-user-slash"></i>
                                            <span>Unknown user</span>
                                        </div>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td>
                                    <div class="flex justify-end gap-2">
                                        @can('restore', $page)
                                            <button
                                                type="button"
                                                class="btn btn-success btn-sm btn-outline gap-2"
                                                title="Restore page"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platform-pages.restore', $page->slug) }}"
                                                data-action-method="PATCH"
                                                data-action-type="success"
                                                data-action-title="Restore Platform Page"
                                                data-action-description="Restore “{{ $page->name }}” and return it to the active platform pages."
                                                data-action-icon="fa-solid fa-rotate-left"
                                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                                data-action-confirm-text="Restore Page"
                                            >
                                                <i class="fa-solid fa-rotate-left"></i>

                                                <span class="hidden lg:inline">Restore</span>
                                            </button>
                                        @endcan

                                        @can('forceDelete', $page)
                                            <button
                                                type="button"
                                                class="btn btn-error btn-sm btn-outline gap-2"
                                                title="Delete permanently"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platform-pages.force-delete', $page->slug) }}"
                                                data-action-method="DELETE"
                                                data-action-type="danger"
                                                data-action-title="Delete Platform Page Permanently"
                                                data-action-description="Permanently delete “{{ $page->name }}”. This action cannot be undone."
                                                data-action-icon="fa-solid fa-triangle-exclamation"
                                                data-action-confirm-icon="fa-solid fa-trash-can"
                                                data-action-confirm-text="Delete Forever"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>

                                                <span class="hidden lg:inline whitespace-nowrap">Delete Forever</span>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ======================= Mobile Cards ============================ --}}
            <div class="divide-y divide-base-200 md:hidden">
                @foreach($pages as $page)
                    @php
                        $typeColor = match(strtolower($page->page_type ?? '')) {
                            'jobs' => 'badge-success',
                            'company' => 'badge-info',
                            'network' => 'badge-primary',
                            'career' => 'badge-secondary',
                            default => 'badge-ghost',
                        };
                    @endphp

                    <div class="p-4 transition bg-base-100 border border-gray-300 shadow-sm mt-3">
                        {{-- Card Header --}}
                        <div class="flex items-start gap-3">
                            {{-- Platform Logo --}}
                            <div class="relative flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-base-300 bg-base-200 shadow-sm">
                                @if($page->platform?->logo)
                                    <img src="{{ Storage::url($page->platform->logo) }}" alt="{{ $page->platform->name }}" class="size-full object-contain p-2">
                                @elseif($page->platform?->icon)
                                    <i class="{{ $page->platform->icon }} text-5xl"
                                       @if($page->platform->color)
                                           style="color: {{ $page->platform->color }}"
                                       @endif>
                                    </i>
                                @else
                                    <i class="fa-solid fa-file-lines text-xl text-primary"></i>
                                @endif
                            </div>

                            {{-- Page Identity --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <a href="{{ route('admin.platform-pages.show', $page) }}" class="line-clamp-2 font-bold leading-5 transition hover:text-primary">
                                        {{ $page->name }}
                                    </a>
                                </div>

                                <span class="badge {{ $typeColor }} badge-sm shrink-0 whitespace-nowrap">
                                    {{ $page->page_type ?: 'Unknown' }}
                                </span>
                            </div>
                        </div>

                        @if($page->short_desc)
                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-base-content/55">
                                {{ $page->short_desc }}
                            </p>
                        @endif

                        {{-- URL --}}
                        @if($page->url)
                            <div class="mt-3 flex items-center gap-2 rounded-xl bg-base-200/60 px-3 py-2 text-xs text-base-content/55">
                                <i class="fa-solid fa-link shrink-0 text-[10px]"></i>

                                <span class="truncate" title="{{ $page->url }}">
                                    {{ parse_url($page->url, PHP_URL_HOST) }}{{ parse_url($page->url, PHP_URL_PATH) ?: '/' }}
                                </span>
                            </div>
                        @endif

                        {{-- Metadata --}}
                        <div class="grid grid-cols-1 gap-2 my-2">
                            {{-- Platform --}}
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-base-content/40">
                                    <i class="fa-solid fa-layer-group"></i> Platform
                                </div>

                                <div class="h-px flex-1 bg-gray-300"></div>

                                @if($page->platform)
                                    <a href="{{ route('admin.platforms.show', $page->platform) }}" class="flex min-w-0 items-center gap-2 text-sm font-bold hover:text-primary">
                                        @if($page->platform->icon)
                                            <i class="{{ $page->platform->icon }} shrink-0" style="{{ $page->platform->color ? 'color: '.$page->platform->color : '' }}"></i>
                                        @else
                                            <i class="fa-solid fa-globe shrink-0" style="{{ $page->platform->color ? 'color: '.$page->platform->color : '' }}"></i>
                                        @endif

                                        <span class="truncate">
                                            {{ $page->platform->name }}
                                        </span>
                                    </a>
                                @else
                                    <span class="text-xs text-base-content/40">Platform unavailable</span>
                                @endif
                            </div>

                            {{-- Deleted --}}
                            <div class="flex flex-row-reverse items-center gap-3">
                                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-base-content/40">
                                    <i class="fa-solid fa-clock"></i> Deleted At
                                </div>

                                <div class="h-px flex-1 bg-gray-300"></div>

                                <div>
                                    <div class="text-sm font-bold">
                                        {{ $page->deleted_at?->format('d M, Y') }}
                                    </div>
    
                                    <div class="mt-0.5 text-[10px] text-base-content/50">
                                        {{ $page->deleted_at?->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Deleted By --}}
                        <div class="flex flex-row-reverse items-center gap-3">
                            <div class="flex min-w-0 items-center gap-2.5">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                    <i class="fa-solid fa-user-shield text-xs"></i>
                                </div>

                                @if($page->deletedBy)
                                    <div class="min-w-0">
                                        <div class="truncate text-xs font-bold" title="{{ $page->deletedBy->name }}">
                                            {{ $page->deletedBy->name }}
                                        </div>

                                        <div class="mt-0.5 text-[9px] font-semibold uppercase tracking-wider text-base-content/40">
                                            {{ str_replace('_', ' ', $page->deletedBy->role) }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-xs text-base-content/40">Unknown user</div>
                                @endif
                            </div>

                            <div class="h-px flex-1 bg-gray-300"></div>

                            <span class="text-[10px] font-semibold uppercase tracking-wider text-base-content/35">Deleted By</span>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            @can('restore', $page)
                                <button
                                    type="button"
                                    class="btn btn-success btn-sm btn-outline gap-2"
                                    title="Restore page"
                                    data-admin-action
                                    data-action-url="{{ route('admin.platform-pages.restore', $page->slug) }}"
                                    data-action-method="PATCH"
                                    data-action-type="success"
                                    data-action-title="Restore Platform Page"
                                    data-action-description="Restore “{{ $page->name }}” and return it to the active platform pages."
                                    data-action-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-text="Restore Page"
                                >
                                    <i class="fa-solid fa-rotate-left"></i> Restore
                                </button>
                            @endcan

                            @can('forceDelete', $page)
                                <button
                                    type="button"
                                    class="btn btn-error btn-sm btn-outline gap-2"
                                    title="Delete permanently"
                                    data-admin-action
                                    data-action-url="{{ route('admin.platform-pages.force-delete', $page->slug) }}"
                                    data-action-method="DELETE"
                                    data-action-type="danger"
                                    data-action-title="Delete Platform Page Permanently"
                                    data-action-description="Permanently delete “{{ $page->name }}”. This action cannot be undone."
                                    data-action-icon="fa-solid fa-triangle-exclamation"
                                    data-action-confirm-icon="fa-solid fa-trash-can"
                                    data-action-confirm-text="Delete Forever"
                                >
                                    <i class="fa-solid fa-trash-can"></i> Delete Forever
                                </button>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($pages->hasPages())
                <div class="border-t border-base-300 px-5 py-4">
                    {{ $pages->links() }}
                </div>
            @endif
            
        @else

            {{-- Empty State --}}
            <div class="px-8 py-20 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-base-200 text-base-content/35">
                    <i class="fa-solid fa-trash-can text-3xl"></i>
                </div>

                <h3 class="mt-6 text-xl font-black">No deleted platform pages</h3>
                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-base-content/55">The trash is currently empty. Deleted platform pages will appear here and can be restored while they remain in the trash.</p>

                <div class="mt-6 flex justify-center">
                    <a href="{{ route('admin.platform-pages.index') }}" class="btn btn-primary gap-2">
                        <i class="fa-solid fa-file-lines"></i> View Platform Pages
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- ====================== FOOTER INFORMATION ===================== --}}
    @if($pages->isNotEmpty())
        <div class="flex flex-col gap-3 py-4 sm:py-2 text-xs text-base-content/50 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-success"></i>
                <span>
                    Platform pages represent <strong class="text-base-content/70">official platform destinations</strong> maintained in CareerVault.
                </span>
            </div>

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info"></i> CareerVault Platform Directory
            </div>
        </div>
    @endif
</div>
@endsection