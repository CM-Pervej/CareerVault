@extends('layouts.admin.app')

@section('title','Platform Pages')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <header class="relative overflow-hidden bg-white">
        <div class="relative flex flex-col gap-6 lg:flex-row justify-center items-center lg:justify-between">
            <div class="flex flex-col">
                <div class="flex justify-center sm:justify-start gap-3 shrink-0 items-center rounded-lg">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-file-lines text-lg"></i>
                    </div>
                    <h1 class="cv-admin-title text-3xl sm:text-4xl whitespace-nowrap">Platform Pages</h1>
                </div>

                <p class="mt-2 text-sm leading-6 text-base-content/65 text-center sm:text-left">Manage official pages, profiles, and resources belonging to CareerVault platforms.</p>
            </div>

            <div class="flex w-full gap-2 sm:w-auto">
                <a href="{{ route('admin.platform-pages.trash') }}" class="btn btn-ghost min-w-0 flex-1 gap-2 border border-base-300 sm:flex-none sm:border-transparent">
                    <i class="fa-solid fa-trash-can"></i> Trash
                    @if($trashedPagesCount > 0)
                        <span class="badge badge-error badge-sm">{{ number_format($trashedPagesCount) }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.platform-pages.create') }}" class="btn btn-primary min-w-0 flex-1 gap-2 whitespace-nowrap sm:flex-none">
                    <i class="fa-solid fa-plus"></i> Add Page
                </a>
            </div>
        </div>
    </header>

    {{-- Statistics --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="pageStates" open>
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
    
        {{-- ===================== OVERVIEW STATS ====================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Total --}}
            <div class="sm:card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-file-lines"></i> Pages
                            </div>
    
                            <div class="mt-2 text-3xl font-black"> {{ number_format($totalPages) }} </div>
                        </div>
    
                        <div class="flex size-10 items-center justify-center rounded-lg bg-info/10 text-info">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                    </div>
    
                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-database"></i> Total directory pages
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
    
                            <div class="mt-2 text-3xl font-black"> {{ number_format($activePages) }} </div>
                        </div>
    
                        <div class="flex size-10 items-center justify-center rounded-lg bg-success/10 text-success">
                            <i class="fa-solid fa-toggle-on"></i>
                        </div>
                    </div>
    
                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-eye"></i> Currently visible pages
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
    
                            <div class="mt-2 text-3xl font-black"> {{ number_format($inactivePages) }} </div>
                        </div>
    
                        <div class="flex size-10 items-center justify-center rounded-lg bg-warning/10 text-warning">
                            <i class="fa-solid fa-toggle-off"></i>
                        </div>
                    </div>
    
                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-eye-slash"></i> Hidden from the directory
                    </div>
                </div>
            </div>
    
            {{-- Trash --}}
            <a href="{{ route('admin.platform-pages.trash') }}" class="sm:card border border-base-300 bg-base-100 shadow-sm hover:border-error/30 hover:bg-error/[0.03]">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-trash-can"></i> Trash
                            </div>
                            <div class="mt-2 text-3xl font-black"> {{ number_format($trashedPagesCount) }} </div>
                        </div>
    
                        <div class="flex size-10 items-center justify-center rounded-lg bg-error/10 text-error">
                            <i class="fa-solid fa-recycle"></i>
                        </div>
                    </div>
    
                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-clock-rotate-left"></i> Soft-deleted pages
                    </div>
                </div>
            </a>
        </div>
    </details>

    {{-- Filters --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="pageFilters" open>
        <summary class="flex items-center justify-between gap-3 p-4 hover:bg-base-200/40">
            <span class="flex min-w-0 items-center gap-2.5">
                <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary">
                    <i class="fa-solid fa-sliders text-xs"></i>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold">Filter Pages</span>
                    <span class="block truncate text-xs text-base-content/50">Narrow the trash list by platform or page type</span>
                </span>
            </span>

            <i class="fa-solid fa-chevron-down cv-chevron text-xs text-base-content/50"></i>
        </summary>
    
        <div class="rounded-lg border border-base-300 bg-base-100 shadow-sm">
            <form method="GET" action="{{ route('admin.platform-pages.index') }}">
                <div class="border-b border-base-300 p-4 sm:p-5">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-end">
                        {{-- Search --}}
                        <div class="form-control min-w-0 flex-1">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-brands fa-searchengin mr-1"></i> Search
                                </span>
                            </div>
    
                            <label class="input input-bordered flex items-center gap-3">
                                <i class="fa-solid fa-magnifying-glass text-base-content/40"></i>
                                <input type="search" name="search" value="{{ $search }}" placeholder="Search page name, description or platform..." class="grow"/>
    
                                @if($search)
                                    <a href="{{ route('admin.platform-pages.index',request()->except('search')) }}" class="btn btn-ghost btn-xs btn-circle">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                @endif
                            </label>
                        </div>
    
                        {{-- Platform --}}
                        <label class="form-control w-full xl:w-56">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-solid fa-layer-group mr-1"></i> Platform
                                </span>
                            </div>
    
                            <select name="platform" class="select select-bordered">
                                <option value="">All platforms</option>
    
                                @foreach($platforms as $item)
                                    <option value="{{ $item->slug }}" @selected($platformSlug === $item->slug)>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </label>
    
                        {{-- Page Type --}}
                        <label class="form-control w-full xl:w-52">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-solid fa-shapes mr-1"></i> Page Type
                                </span>
                            </div>
    
                            <select name="page_type" class="select select-bordered">
                                <option value="">All types</option>
    
                                @foreach($pageTypes as $type)
                                    <option value="{{ $type }}" @selected($pageType === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </label>
    
                        {{-- Status --}}
                        <label class="form-control w-full xl:w-44">
                            <div class="label py-0 pb-1.5">
                                <span class="label-text text-xs font-bold">
                                    <i class="fa-solid fa-power-off mr-1"></i> Status
                                </span>
                            </div>
    
                            <select name="status" class="select select-bordered">
                                <option value="">All statuses</option>
                                <option value="active" @selected($status === 'active')>Active only</option>
                                <option value="inactive" @selected($status === 'inactive')>Inactive only</option>
                            </select>
                        </label>
                    </div>
    
                    {{-- Filter Actions --}}
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
    
                            @if($platformSlug)
                                <span class="badge badge-sm badge-info gap-1.5">
                                    <i class="fa-solid fa-layer-group"></i> {{ $platform->name ?? $platformSlug }}
                                </span>
                            @endif
    
                            @if($pageType)
                                <span class="badge badge-sm badge-secondary gap-1.5">
                                    <i class="fa-solid fa-shapes"></i> {{ $pageType }}
                                </span>
                            @endif
    
                            @if($status)
                                <span class="badge badge-sm badge-warning gap-1.5">
                                    <i class="fa-solid fa-power-off"></i> {{ ucfirst($status) }}
                                </span>
                            @endif
    
                            @if($search || $platformSlug || $pageType || $status)
                                <a href="{{ route('admin.platform-pages.index') }}" class="link link-error font-semibold">Clear all</a>
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
    
            {{-- Result Summary --}}
            <div class="flex flex-col gap-2 px-5 py-3 text-sm sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-list text-base-content/40"></i>
    
                    <span>
                        Showing <strong>{{ $pages->count() }}</strong> {{ Str::plural('page',$pages->count()) }}
                    </span>
    
                    @if($search || $platformSlug || $pageType || $status)
                        <span class="text-base-content/35">of</span>
                        <span class="font-semibold text-base-content/60"> {{ number_format($totalPages) }} </span>
                    @endif
                </div>
    
                <div class="text-xs text-base-content/40">
                    <i class="fa-solid fa-circle-info mr-1"></i> Official platform pages and resources
                </div>
            </div>
        </div>
    </details>

    {{-- ======================= PAGE DIRECTORY ===================== --}}
    <div class="rounded-lg mt-y sm:border sm:border-gray-200 sm:shadow-sm">
        {{-- Directory Header --}}
        <div class="px-5 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2 font-black">
                    <i class="fa-solid fa-file-circle-check text-primary"></i> Platform Page Directory
                </div>
                <div class="badge badge-outline gap-1.5">
                    <i class="fa-solid fa-file-lines"></i>
                    {{ number_format($totalPages) }}
                    {{ Str::plural('page',$totalPages) }}
                </div>
            </div>

            <p class="mt-3 text-center sm:text-start text-xs text-base-content/50">Official pages, profiles, career resources, and other platform-linked destinations.</p>
        </div>

        {{-- Page Rows --}}
        <div class="divide-y divide-base-300">
            @forelse($pages as $page)
                <div class="group relative p-4 transition hover:bg-base-200/30 sm:p-5 border border-gray-300 sm:border-none shadow-sm sm:shadow-none my-2 sm:my-0">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center">
                        {{-- ================= PAGE IDENTITY ================= --}}
                        <div class="flex min-w-0 items-center gap-4 xl:w-[360px]">
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

                                {{-- Status Indicator --}}
                                <span class="absolute bottom-1 right-1 size-2.5 rounded-full border-2 border-base-100 {{ $page->is_active ? 'bg-success' : 'bg-warning' }}" title="{{ $page->is_active ? 'Active' : 'Inactive' }}"></span>
                            </div>

                            {{-- Page Information --}}
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 w-62">
                                    <a href="{{ route('admin.platform-pages.show',$page) }}" class="truncate font-black"> {{ $page->name }} </a>

                                    @if($page->is_active)
                                        <span class="tooltip" data-tip="Active page">
                                            <i class="fa-solid fa-circle-check text-xs text-success"></i>
                                        </span>
                                    @else
                                        <span class="tooltip" data-tip="Inactive page">
                                            <i class="fa-solid fa-circle-exclamation text-xs text-warning"></i>
                                        </span>
                                    @endif
                                </div>

                                @if($page->short_desc)
                                    <div class="mt-1.5 line-clamp-1 text-xs text-base-content/50"> {{ $page->short_desc }} </div>
                                @elseif($page->platform)
                                <div class="mt-0.5 flex items-center gap-1.5 truncate text-xs text-base-content/45">
                                    <i class="fa-solid fa-layer-group text-[9px]"></i> {{ $page->platform?->name ?? 'Unassigned platform' }}
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- ================= PAGE DETAILS ================= --}}
                        <div class="min-w-0 flex-1">
                            <div class="mb-2 flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-base-content/40 whitespace-nowrap">
                                <i class="fa-solid fa-circle-info"></i> Page Details
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                {{-- Type --}}
                                @php
                                    $pageTypeIcon = match(Str::lower($page->page_type ?? '')) {
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

                                <span class="badge badge-sm badge-outline gap-1.5">
                                    <i class="{{ $pageTypeIcon }}"></i> {{ $page->page_type ?: 'General' }}
                                </span>

                                {{-- Status --}}
                                @if($page->is_active)
                                    <span class="badge badge-sm badge-success gap-1.5">
                                        <i class="fa-solid fa-circle-check"></i> Active
                                    </span>
                                @else
                                    <span class="badge badge-sm badge-warning gap-1.5">
                                        <i class="fa-solid fa-circle-exclamation"></i> Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- ======================= Desktop ACTIONS ======================= --}}
                        <div class="hidden sm:flex shrink-0 items-center gap-2 xl:w-36 xl:justify-end">
                            <a href="{{ route('admin.platform-pages.show',$page) }}" class="btn btn-sm btn-outline gap-2">
                                <i class="fa-solid fa-eye"></i> View
                            </a>

                            <div class="dropdown dropdown-center dropdown-left">
                                <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>

                                <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                    <li>
                                        <a href="{{ route('admin.platform-pages.show',$page) }}">
                                            <i class="fa-solid fa-eye"></i> View page
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('admin.platform-pages.edit',$page) }}">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit page
                                        </a>
                                    </li>

                                    @if($page->platform)
                                        <li>
                                            <a href="{{ route('admin.platforms.show',$page->platform) }}">
                                                <i class="fa-solid fa-layer-group"></i> View platform
                                            </a>
                                        </li>
                                    @endif

                                    <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                        <span>Danger zone</span>
                                    </li>

                                    <li>
                                        @can('delete',$page)
                                            <button
                                                {{-- type="button" --}}
                                                class="text-error flex items-center gap-2 hover:bg-error/10"
                                                title="Move page to trash"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platform-pages.destroy',$page->slug) }}"
                                                data-action-method="DELETE"
                                                data-action-type="danger"
                                                data-action-title="Move Platform Page to Trash"
                                                data-action-description="Move <strong>“{{ $page->name }}”</strong> to trash. You can restore it later from the Trash page."
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
                            <a href="{{ route('admin.platform-pages.show',$page) }}" class="btn btn-sm btn-outline">
                                <i class="fa-solid fa-eye"></i> View
                            </a>

                            <a href="{{ route('admin.platform-pages.edit',$page) }}" class="btn btn-sm btn-outline">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>

                            <a href="{{ route('admin.platforms.show',$page->platform) }}" class="btn btn-sm btn-outline">
                                <i class="fa-solid fa-layer-group"></i> platform
                            </a>

                            @can('delete',$page)
                                <button
                                    {{-- type="button" --}}
                                    class="text-error flex items-center hover:bg-error/10 btn btn-sm btn-outline border-error"
                                    title="Move page to trash"
                                    data-admin-action
                                    data-action-url="{{ route('admin.platform-pages.destroy',$page->slug) }}"
                                    data-action-method="DELETE"
                                    data-action-type="danger"
                                    data-action-title="Move Platform Page to Trash"
                                    data-action-description="Move <strong>“{{ $page->name }}”</strong> to trash. You can restore it later from the Trash page."
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

                    @if($search || $platformSlug || $pageType || $status)
                        <h3 class="mt-5 text-lg font-black">No matching pages</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">No platform pages match your current search and filter combination. Try changing your filters or clearing them.</p>
                        <a href="{{ route('admin.platform-pages.index') }}" class="btn btn-outline mt-5 gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    @else
                        <h3 class="mt-5 text-lg font-black">No platform pages available</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">Create the first official page or resource for your platform directory.</p>
                        <a href="{{ route('admin.platform-pages.create') }}" class="btn btn-primary mt-5 gap-2">
                            <i class="fa-solid fa-plus"></i> Add Platform Page
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- ======================== PAGINATION ======================== --}}
        @if($pages->hasPages())
            <div class="border-t border-base-300 px-5 py-4"> {{ $pages->withQueryString()->links() }} </div>
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