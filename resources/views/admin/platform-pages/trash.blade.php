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
                        <h1 class="cv-admin-title text-3xl sm:text-4xl whitespace-nowrap">Platform Pages Trash</h1>
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
                    <span class="block truncate text-xs text-base-content/50">Tap a card to filter the list</span>
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

    {{-- Filters --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="filters" open>
        <summary class="flex items-center justify-between gap-3 p-4 hover:bg-base-200/40">
            <span class="flex min-w-0 items-center gap-2.5">
                <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary">
                    <i class="fa-solid fa-sliders text-xs"></i>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold">Filter Deleted Pages</span>
                    <span class="block truncate text-xs text-base-content/50">Narrow the trash list by platform or page type</span>
                </span>
            </span>

            <i class="fa-solid fa-chevron-down cv-chevron text-xs text-base-content/50"></i>
        </summary>

        <div class="p-5">
            <form method="GET" class="grid gap-4 lg:grid-cols-[1fr_1fr_auto]">
                {{-- Platform --}}
                <div>
                    <label class="label">
                        <span class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Platform</span>
                    </label>
    
                    <select name="platform" class="select select-bordered w-full">
                        <option value="">All Platforms</option>
    
                        @foreach($platforms as $platform)
                            <option value="{{ $platform->id }}" @selected(request('platform') == $platform->id)>
                                {{ $platform->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
    
                {{-- Page Type --}}
                <div>
                    <label class="label">
                        <span class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Page Type</span>
                    </label>
    
                    <select name="type" class="select select-bordered w-full">
                        <option value="">All Types</option>
    
                        @foreach($pageTypes as $type)
                            <option value="{{ $type }}" @selected(request('type') == $type)>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
    
                {{-- Actions --}}
                <div class="flex gap-2 lg:items-end">
                    <button type="submit" class="btn btn-primary gap-2">
                        <i class="fa-solid fa-filter"></i> Apply
                    </button>
    
                    @if(request()->filled('platform') || request()->filled('type'))
                        <a href="{{ route('admin.platform-pages.trash') }}" class="btn btn-ghost gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Active Filters --}}
            @if(request()->filled('platform') || request()->filled('type'))
                <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-base-200 pt-4">
                    <span class="mr-1 text-xs font-bold uppercase tracking-wider text-base-content/45">Active Filters</span>
    
                    @if(request('platform'))
                        @php
                            $selectedPlatform = $platforms->firstWhere('id', request('platform'));
                        @endphp
    
                        @if($selectedPlatform)
                            <span class="badge badge-primary badge-outline gap-1">
                                <i class="fa-solid fa-layer-group text-[10px]"></i> 
                                {{ $selectedPlatform->name }}
                            </span>
                        @endif
                    @endif
    
                    @if(request('type'))
                        <span class="badge badge-secondary badge-outline gap-1">
                            <i class="fa-solid fa-tag text-[10px]"></i>
                            {{ request('type') }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </details>

    {{-- Trash List --}}
    <div class="overflow-hidden sm:rounded-lg border border-base-300 shadow-sm">
        {{-- Toolbar --}}
        <div class="flex gap-3 border-b border-base-300 bg-base-100 px-5 py-4 items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                    <i class="fa-solid fa-trash-can text-sm"></i>
                </div>

                <div>
                    <h2 class="font-black">Deleted Platform Pages</h2>

                    <p class="mt-0.5 text-xs text-base-content/50">
                        {{ number_format($pages->count()) }} page(s) shown
                        •
                        {{ number_format($pages->total()) }} total in current result
                    </p>
                </div>
            </div>

            <span class="badge badge-error badge-outline gap-1">
                <i class="fa-solid fa-box-archive text-[10px]"></i> Trash
            </span>
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
                                                {{ $page->deleted_at?->format('d M Y') }}
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
            <div class="divide-y divide-base-200 md:hidden mb-3">
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

                    <div class="p-4 transition bg-base-100 shadow-sm mt-3">
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

                                    <span class="badge {{ $typeColor }} badge-sm shrink-0 whitespace-nowrap">
                                        {{ $page->page_type ?: 'Unknown' }}
                                    </span>
                                </div>

                                @if($page->short_desc)
                                    <p class="mt-1 line-clamp-2 text-xs leading-5 text-base-content/55">
                                        {{ $page->short_desc }}
                                    </p>
                                @endif
                            </div>
                        </div>

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
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            {{-- Platform --}}
                            <div class="rounded-xl border border-base-200 bg-base-200/40 p-3">
                                <div class="mb-1 flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-base-content/40">
                                    <i class="fa-solid fa-layer-group"></i> Platform
                                </div>

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
                            <div class="rounded-xl border border-base-200 bg-base-200/40 p-3">
                                <div class="mb-1 flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-base-content/40">
                                    <i class="fa-solid fa-clock"></i> Deleted
                                </div>

                                <div class="text-sm font-bold">
                                    {{ $page->deleted_at?->format('d M Y') }}
                                </div>

                                <div class="mt-0.5 text-[10px] text-base-content/50">
                                    {{ $page->deleted_at?->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        {{-- Deleted By --}}
                        <div class="mt-3 flex items-center justify-between rounded-xl border border-base-200 bg-base-200/40 px-3 py-2.5">
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
        <div class="flex flex-col gap-3 sm:rounded-lg shadow-sm border border-base-300 bg-base-100 px-5 py-4 text-xs text-base-content/50 sm:flex-row sm:items-center sm:justify-between">
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