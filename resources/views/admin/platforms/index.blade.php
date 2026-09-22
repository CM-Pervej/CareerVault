@extends('layouts.admin.app')

@section('title','Platforms | CareerVault')
@section('page_title','Platforms')

@section('content')
<div class="mx-auto space-y-2 sm:space-y-5">
    {{-- Header --}}
    <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between px-4 sm:px-0 mb-5">
        <div class="min-w-0">
            <div class="flex items-start gap-3">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                    <i class="fa-solid fa-tree"></i>
                </div>

                <div class="min-w-0">
                    <h1 class="cv-admin-title text-3xl sm:text-4xl">Platforms</h1>
                    <p class="mt-1 text-sm text-base-content/60">Manage job platforms, career sources, and their availability.</p>
                </div>
            </div>
        </div>

        <div class="flex w-full gap-2 sm:w-auto">
            <a href="{{ route('admin.platforms.trash') }}" class="btn btn-ghost min-w-0 flex-1 gap-2 border border-base-300 sm:flex-none sm:border-transparent">
                <i class="fa-solid fa-trash-can"></i>
                <span>Trash</span>

                @php
                    $trashedPlatformsCount=\App\Models\Platform::onlyTrashed()->count();
                @endphp

                @if($trashedPlatformsCount>0)
                    <span class="badge badge-error badge-sm"> {{ $trashedPlatformsCount }} </span>
                @endif
            </a>

            <a href="{{ route('admin.platforms.create') }}" class="btn btn-primary min-w-0 flex-1 gap-2 whitespace-nowrap sm:flex-none">
                <i class="fa-solid fa-plus"></i>
                <span>Add Platform</span>
            </a>
        </div>
    </header>

    {{-- Statistics --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="platformStats" open>
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

        <div class="grid grid-cols-2 gap-0 sm:gap-1 lg:grid-cols-3 p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Total --}}
            <div class="card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-file-lines"></i> Total
                            </div>

                            <div class="mt-2 text-3xl font-black"> {{ number_format(\App\Models\Platform::count()) }} </div>
                        </div>

                        <div class="flex size-10 items-center justify-center rounded-lg bg-info/10 text-info">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-database"></i> Registered platforms
                    </div>
                </div>
            </div>

            {{-- Active --}}
            <div class="card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-circle-check"></i> Active
                            </div>

                            <div class="mt-2 text-3xl font-black"> {{ number_format(\App\Models\Platform::where('is_active',true)->count()) }} </div>
                        </div>

                        <div class="flex size-10 items-center justify-center rounded-lg bg-info/10 text-info">
                            <i class="fa-solid fa-toggle-on"></i>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-eye"></i> Currently available
                    </div>
                </div>
            </div>

            {{-- Inactive --}}
            <div class="card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-circle-exclamation"></i> Inactive
                            </div>

                            <div class="mt-2 text-3xl font-black"> {{ number_format(\App\Models\Platform::where('is_active',false)->count()) }} </div>
                        </div>

                        <div class="flex size-10 items-center justify-center rounded-lg bg-info/10 text-info">
                            <i class="fa-solid fa-toggle-off"></i>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-eye-slash"></i> Hidden from the directory
                    </div>
                </div>
            </div>

            {{-- Remote --}}
            <div class="card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-map"></i> Remote
                            </div>

                            <div class="mt-2 text-3xl font-black"> {{ number_format(\App\Models\Platform::whereIn('job_type',['Remote','Both'])->count()) }} </div>
                        </div>

                        <div class="flex size-10 items-center justify-center rounded-lg bg-info/10 text-info">
                            <i class="fa-solid fa-globe text-info"></i>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-regular fa-map"></i> Remote-friendly
                    </div>
                </div>
            </div>

            {{-- Bangladesh --}}
            <div class="card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-map"></i> Bangladesh
                            </div>

                            <div class="mt-2 text-3xl font-black"> {{ number_format(\App\Models\Platform::where('is_bangladesh_focused',true)->count()) }} </div>
                        </div>

                        <div class="flex size-10 items-center justify-center rounded-lg bg-info/10 text-info">
                            <i class="fa-solid fa-flag text-success"></i>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-regular fa-flag"></i> Bangladesh focused
                    </div>
                </div>
            </div>

            {{-- Trash --}}
            <a href="{{ route('admin.platforms.trash') }}" class="card border border-base-300 bg-base-100 shadow-sm hover:border-error/30 hover:bg-error/[0.03]">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-trash-can"></i> Trash
                            </div>
                            <div class="mt-2 text-3xl font-black"> {{ number_format(\App\Models\Platform::onlyTrashed()->count()) }} </div>
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
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="platfromFilters" open>
        <summary class="flex items-center justify-between gap-3 p-4 hover:bg-base-200/40">
            <span class="flex min-w-0 items-center gap-2.5">
                <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-base-200 text-base-content/60">
                    <i class="fa-solid fa-sliders text-xs"></i>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold">Filters</span>
                    <span class="block truncate text-xs text-base-content/50">Narrowing the list</span>
                </span>
            </span>
 
            <i class="fa-solid fa-chevron-down cv-chevron text-xs text-base-content/50"></i>
        </summary>

        <section>
            {{-- Filters --}}
            <div class="rounded-lg border border-base-300 bg-base-100 shadow-sm">
                <form method="GET" action="{{ route('admin.platforms.index') }}">
                    <div class="border-b border-base-300 p-4 sm:p-5">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-end">
                            {{-- Search --}}
                            <div class="form-control min-w-0 flex-1">
                                <div class="label py-0 pb-1.5">
                                    <span class="label-text text-xs font-bold">
                                        <i class="fa-brands fa-searchengin mr-1"></i> Search
                                    </span>
                                </div>
        
                                <label class="input input-bordered flex items-center gap-2">
                                    <i class="fa-solid fa-magnifying-glass text-base-content/40"></i>
                                    <input type="text" name="search" value="{{ $search }}" placeholder="Search platforms..." class="grow"/>
        
                                    @if($search)
                                        <a href="{{ route('admin.platforms.index',request()->except('search')) }}" class="text-base-content/40 hover:text-base-content" title="Clear search">
                                            <i class="fa-solid fa-xmark"></i>
                                        </a>
                                    @endif
                                </label>
                            </div>
        
                            {{-- Job Type --}}
                            <div class="form-control min-w-0 flex-1">
                                <div class="label py-0 pb-1.5">
                                    <span class="label-text text-xs font-bold">
                                        <i class="fa-solid fa-globe mr-1"></i> Job Type
                                    </span>
                                </div>
        
                                <select name="job_type" class="select select-bordered w-full" onchange="this.form.submit()">
                                    <option value="">All job types</option>
                                    <option value="Remote" @selected($jobType==='Remote')>Remote</option>
                                    <option value="Onsite" @selected($jobType==='Onsite')>On-site</option>
                                    <option value="Both" @selected($jobType==='Both')>Remote & On-site</option>
                                </select>
                            </div>
        
                            {{-- Business --}}
                            <div class="form-control min-w-0 flex-1">
                                <div class="label py-0 pb-1.5">
                                    <span class="label-text text-xs font-bold">
                                        <i class="fa-solid fa-business-time mr-1"></i></i> Business Model
                                    </span>
                                </div>
        
                                <select name="business_model" class="select select-bordered w-full" onchange="this.form.submit()">
                                    <option value="">All business models</option>
                                    <option value="Free" @selected($businessModel==='Free')>Free</option>
                                    <option value="Freemium" @selected($businessModel==='Freemium')>Freemium</option>
                                    <option value="Paid" @selected($businessModel==='Paid')>Paid</option>
                                </select>
                            </div>
        
                            {{-- Bangladesh Focus --}}
                            <div class="form-control min-w-0 flex-1">
                                <div class="label py-0 pb-1.5">
                                    <span class="label-text text-xs font-bold">
                                        <i class="fa-solid fa-flag mr-1"></i> Bangladesh Focus
                                    </span>
                                </div>
        
                                <select name="bangladesh_focus" class="select select-bordered w-full" onchange="this.form.submit()">
                                    <option value="">All locations</option>
                                    <option value="focused" @selected($bangladeshFocus==='focused')>Bangladesh focused</option>
                                    <option value="international" @selected($bangladeshFocus==='international')>International</option>
                                </select>
                            </div>
        
                            {{-- Status --}}
                            <div class="form-control min-w-0 flex-1">
                                <div class="label py-0 pb-1.5">
                                    <span class="label-text text-xs font-bold">
                                        <i class="fa-solid fa-power-off mr-1"></i> Status
                                    </span>
                                </div>
        
                                <select name="status" class="select select-bordered w-full" onchange="this.form.submit()">
                                    <option value="">All statuses</option>
                                    <option value="active" @selected($status==='active')>Active</option>
                                    <option value="inactive" @selected($status==='inactive')>Inactive</option>
                                </select>
                            </div>
                        </div>
        
                        {{-- Filter Actions --}}
                        <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-2 text-xs text-base-content/50">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-filter"></i> Filters applied:
                                </span>
        
                                @if($search)
                                    <span class="badge badge-primary badge-outline gap-1">
                                        <i class="fa-solid fa-magnifying-glass"></i> {{ $search }}
                                    </span>
                                @endif
        
                                @if($bangladeshFocus)
                                    <span class="badge badge-success badge-outline gap-1">
                                        <i class="fa-solid fa-flag"></i> {{ $bangladeshFocus==='focused' ? 'Bangladesh focused' : 'International' }}
                                    </span>
                                @endif
        
                                @if($jobType)
                                    <span class="badge badge-info badge-outline">
                                        <i class="fa-solid fa-globe text-info"></i> {{ $jobType }}
                                    </span>
                                @endif
        
                                @if($businessModel)
                                    <span class="badge badge-secondary badge-outline">
                                        <i class="fa-solid fa-business-time"></i> {{ $businessModel }}
                                    </span>
                                @endif
        
                                @if($status)
                                    <span class="badge badge-outline">
                                        <i class="fa-solid fa-power-off"></i> {{ ucfirst($status) }}
                                    </span>
                                @endif
        
                                @if($search || $jobType || $businessModel || $status || $bangladeshFocus)
                                    <a href="{{ route('admin.platforms.index') }}" class="link link-error text-xs">Clear all</a>
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
                {{-- Result information --}}
                <div class="flex flex-col gap-2 px-5 py-3 text-sm sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-list text-base-content/40"></i>
                        
                        Showing <span class="font-semibold text-base-content"> {{ $platforms->firstItem() ?? 0 }} </span>
                        – <span class="font-semibold text-base-content"> {{ $platforms->lastItem() ?? 0 }} </span>
                        of <span class="font-semibold text-base-content"> {{ number_format($platforms->total()) }} </span>
                        platforms
                    </div>
            
                    <div class="text-xs text-base-content/40">
                        <i class="fa-solid fa-circle-info mr-1"></i> Official platform pages
                    </div>
                </div>
            </div>
        </section>
    </details>

    {{-- Desktop Platform Directory --}}
    <div class="hidden overflow-hidden rounded-lg border border-base-300/70 bg-base-100 shadow-[0_8px_40px_-20px_rgba(0,0,0,0.18)] lg:block">
        {{-- Table Header --}}
        <div class="flex items-center justify-between border-b border-base-300/70 bg-base-200/30 p-4">
            <div class="flex items-center gap-3">
                <div class="grid size-9 place-items-center rounded-xl bg-primary/10 text-primary">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </div>

                <div>
                    <h2 class="text-sm font-bold tracking-tight">Platform Directory</h2>
                    <p class="mt-0.5 text-xs text-base-content/45">Your configured career and job-search sources</p>
                </div>
            </div>

            <div class="hidden items-center gap-2 xl:flex">
                <span class="text-xs text-base-content/40"> {{ number_format($platforms->total()) }} platforms</span>
                <span class="size-1 rounded-full bg-base-content/20"></span>

                <span class="flex items-center gap-1.5 text-xs text-success">
                    <span class="size-1.5 rounded-full bg-success"></span> Live directory
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                {{-- Head --}}
                <thead>
                    <tr class="border-b border-base-300/70 bg-base-200/20 text-[11px] uppercase tracking-wider text-base-content/40 text-center font-semibold">
                        <th class="text-left">Platform</th> <th>Job Type</th> <th>Access</th> <th>Coverage</th> <th>Status</th> <th>Actions</th>
                    </tr>
                </thead>

                {{-- Body --}}
                <tbody>
                    @forelse($platforms as $platform)
                        <tr class="hover">
                            {{-- Platform --}}
                            <td>
                                <div class="flex items-center gap-3.5">
                                    {{-- Logo --}}
                                    <div class="relative grid size-12 shrink-0 place-items-center overflow-hidden rounded-lg transition duration-200 group-hover:scale-[1.03] group-hover:shadow-md">
                                        @if($platform->logo)
                                            <img src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="size-full object-contain p-3">
                                        @elseif($platform->icon)
                                                <i class="{{ $platform->icon }} text-4xl" style="{{ $platform->color ? 'color:'.$platform->color : '' }}"></i>
                                        @else
                                            <span class="text-4xl font-black" style="color: {{ $platform->color ?: '#4f46e5' }}">
                                                {{ strtoupper(substr($platform->name, 0, 1)) }}
                                            </span>
                                        @endif

                                        {{-- Active indicator --}}
                                        @if($platform->is_active)
                                            <span class="absolute bottom-1 right-1 size-2 rounded-full bg-success ring-2 ring-base-100"></span>
                                        @endif
                                    </div>

                                    {{-- Identity --}}
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.platforms.show',$platform) }}" class="truncate text-[15px] font-bold tracking-tight transition-colors hover:text-primary"> {{ $platform->name }} </a>

                                            @if($platform->is_bangladesh_focused)
                                                <span class="inline-flex shrink-0 items-center gap-1 rounded-md bg-success/10 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-success" title="Bangladesh focused">
                                                    <i class="fa-solid fa-location-dot text-[8px]"></i> BD
                                                </span>
                                            @endif
                                        </div>

                                        @if($platform->official_name)
                                            <p class="mt-0.5 max-w-xs truncate text-xs text-base-content/45"> {{ $platform->official_name }} </p>
                                        @elseif($platform->short_desc)
                                            <p class="mt-0.5 max-w-xs truncate text-xs text-base-content/45"> {{ $platform->short_desc }} </p>
                                        @else
                                            <p class="mt-0.5 text-xs italic text-base-content/30">No description available</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Job Type --}}
                            <td>
                                @if($platform->job_type==='Remote')
                                    <div class="inline-flex items-center gap-2 rounded-xl border border-info/20 bg-info/5 px-2.5 py-1.5 text-xs font-semibold text-info">
                                        <span class="grid size-5 place-items-center rounded-md bg-info/10">
                                            <i class="fa-solid fa-house-laptop text-[9px]"></i>
                                        </span>
                                        Remote
                                    </div>

                                @elseif($platform->job_type==='Onsite')
                                    <div class="inline-flex items-center gap-2 rounded-xl border border-warning/20 bg-warning/5 px-2.5 py-1.5 text-xs font-semibold text-warning">
                                        <span class="grid size-5 place-items-center rounded-md bg-warning/10">
                                            <i class="fa-solid fa-building text-[9px]"></i>
                                        </span>
                                        Onsite
                                    </div>
                                @else

                                    <div class="inline-flex items-center gap-2 rounded-xl border border-primary/20 bg-primary/5 px-2.5 py-1.5 text-xs font-semibold text-primary">
                                        <span class="grid size-5 place-items-center rounded-md bg-primary/10">
                                            <i class="fa-solid fa-arrows-left-right text-[9px]"></i>
                                        </span>
                                        Both
                                    </div>
                                @endif
                            </td>

                            {{-- Business Model --}}
                            <td>
                                @if($platform->business_model==='Free')
                                    <div class="flex items-center gap-2">
                                        <span class="grid size-7 place-items-center rounded-lg bg-success/10 text-success">
                                            <i class="fa-solid fa-gift text-[10px]"></i>
                                        </span>

                                        <div>
                                            <div class="text-xs font-semibold">Free</div>
                                            <div class="text-[10px] text-base-content/35">No payment</div>
                                        </div>
                                    </div>

                                @elseif($platform->business_model==='Freemium')
                                    <div class="flex items-center gap-2">
                                        <span class="grid size-7 place-items-center rounded-lg bg-warning/10 text-warning">
                                            <i class="fa-solid fa-layer-group text-[10px]"></i>
                                        </span>

                                        <div>
                                            <div class="text-xs font-semibold">Freemium</div>
                                            <div class="text-[10px] text-base-content/35">Free + paid</div>
                                        </div>
                                    </div>

                                @else
                                    <div class="flex items-center gap-2">
                                        <span class="grid size-7 place-items-center rounded-lg bg-error/10 text-error">
                                            <i class="fa-solid fa-credit-card text-[10px]"></i>
                                        </span>

                                        <div>
                                            <div class="text-xs font-semibold">Paid</div>
                                            <div class="text-[10px] text-base-content/35">Subscription</div>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            {{-- Coverage --}}
                            <td class="px-4 py-4">
                                @if($platform->is_bangladesh_focused)
                                    <div class="flex items-center gap-2.5">
                                        <div class="grid size-8 place-items-center rounded-xl bg-success/10 text-success">
                                            <i class="fa-solid fa-flag text-xs"></i>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold">Bangladesh</div>
                                            <div class="text-[10px] text-base-content/40">Local focused</div>
                                        </div>
                                    </div>

                                @else
                                    <div class="flex items-center gap-2.5">
                                        <div class="grid size-8 place-items-center rounded-xl bg-base-200 text-base-content/50">
                                            <i class="fa-solid fa-earth-americas text-xs"></i>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold">International</div>
                                            <div class="text-[10px] text-base-content/40">Global source</div>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($platform->is_active)
                                    <div class="inline-flex items-center gap-2 rounded-full border border-success/20 bg-success/5 px-2.5 py-1.5">
                                        <span class="relative flex size-2">
                                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success opacity-40"></span>
                                            <span class="relative inline-flex size-2 rounded-full bg-success"></span>
                                        </span>

                                        <span class="text-xs font-semibold text-success">Active</span>
                                    </div>

                                @else
                                    <div class="inline-flex items-center gap-2 rounded-full border border-base-300 bg-base-200/50 px-2.5 py-1.5">
                                        <span class="size-2 rounded-full bg-base-content/25"></span>
                                        <span class="text-xs font-semibold text-base-content/45">Inactive</span>
                                    </div>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="flex items-center gap-2 xl:justify-end">
                                    @can('view',$platform)
                                        <a href="{{ route('admin.platforms.show',$platform) }}" class="btn btn-sm btn-outline gap-2" title="View">
                                            <i class="fa-regular fa-eye"></i> View
                                        </a>
                                    @endcan

                                    <div class="dropdown dropdown-center dropdown-left">
                                        <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>

                                        <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                            <li>
                                                <a href="{{ route('admin.platforms.show',$platform) }}">
                                                    <i class="fa-solid fa-eye"></i> View page
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ route('admin.platforms.edit',$platform) }}">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit page
                                                </a>
                                            </li>

                                            <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                                <span>Danger zone</span>
                                            </li>

                                            <li>
                                                <div>
                                                    @can('delete',$platform)
                                                        <button
                                                            type="button"
                                                            class="text-error gap-2"
                                                            title="Move platform to trash"
                                                            data-admin-action
                                                            data-action-url="{{ route('admin.platforms.destroy',$platform->slug) }}"
                                                            data-action-method="DELETE"
                                                            data-action-type="danger"
                                                            data-action-title="Move Platform to Trash"
                                                            data-action-description="Move “{{ $platform->name }}” to trash. You can restore it later from the Trash page."
                                                            data-action-icon="fa-solid fa-trash-can"
                                                            data-action-confirm-icon="fa-solid fa-trash-can"
                                                            data-action-confirm-text="Move to Trash"
                                                        >
                                                            <i class="fa-solid fa-trash-can"></i>
                                                            <span class="hidden lg:inline">Move to Trash</span>
                                                        </button>
                                                    @endcan
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">
                                <div class="flex min-h-[360px] flex-col items-center justify-center px-6 py-16 text-center">
                                    <div class="relative">
                                        <div class="absolute -inset-4 rounded-full bg-primary/5 blur-xl"></div>
                                        <div class="relative grid size-20 place-items-center rounded-3xl border border-base-300 bg-base-200/60 text-3xl text-primary shadow-sm">
                                            <i class="fa-solid fa-layer-group"></i>
                                        </div>
                                    </div>

                                    <h3 class="mt-6 text-xl font-black tracking-tight">No platforms found</h3>
                                    <p class="mt-2 max-w-md text-sm leading-6 text-base-content/45">Nothing matches your current search and filter criteria. Try adjusting your filters or add a new platform.</p>

                                    <div class="mt-6 flex gap-2">
                                        <a href="{{ route('admin.platforms.index') }}" class="btn btn-ghost rounded-xl">
                                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                                        </a>

                                        <a href="{{ route('admin.platforms.create') }}" class="btn btn-primary rounded-xl px-5 shadow-lg shadow-primary/20">
                                            <i class="fa-solid fa-plus"></i> Add Platform
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer // Pagination --}}
        @if($platforms->hasPages())
            <div class="px-4 py-1">
                {{ $platforms->links() }}
            </div>
        @endif
    </div>

    {{-- Mobile Cards --}}
    <div class="space-y-2 lg:hidden">
        @forelse($platforms as $platform)
            <div class="sm:rounded-lg border border-base-300 bg-base-100 p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="grid h-12 w-12 shrink-0 place-items-center overflow-hidden rounded-xl border border-base-300 bg-base-200" @if($platform->color) style="color:{{ $platform->color }}" @endif>
                        @if($platform->logo)
                            <img src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="size-full object-contain p-3">
                        @elseif($platform->icon)
                                <i class="{{ $platform->icon }} text-4xl" style="{{ $platform->color ? 'color:'.$platform->color : '' }}"></i>
                        @else
                            <span class="text-4xl font-black" style="color: {{ $platform->color ?: '#4f46e5' }}">
                                {{ strtoupper(substr($platform->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.platforms.show',$platform) }}" class="truncate font-semibold hover:text-primary"> {{ $platform->name }} </a>

                            @if($platform->is_bangladesh_focused)
                                <span class="badge badge-success badge-xs shrink-0">BD</span>
                            @endif
                        </div>

                        <p class="mt-0.5 truncate text-xs opacity-50"> {{ $platform->official_name ?: $platform->short_desc ?: 'No description available' }} </p>
                    </div>

                    @if($platform->is_active)
                        <span class="badge badge-success badge-xs shrink-0">Active</span>
                    @else
                        <span class="badge badge-ghost badge-xs shrink-0">Inactive</span>
                    @endif
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    @if($platform->job_type==='Remote')
                        <span class="badge badge-info badge-outline gap-1">
                            <i class="fa-solid fa-house-laptop text-[9px]"></i> Remote
                        </span>
                    @elseif($platform->job_type==='Onsite')
                        <span class="badge badge-warning badge-outline gap-1">
                            <i class="fa-solid fa-building text-[9px]"></i> Onsite
                        </span>
                    @else
                        <span class="badge badge-primary badge-outline gap-1">
                            <i class="fa-solid fa-arrows-left-right text-[9px]"></i> Both
                        </span>
                    @endif

                    <span class="badge badge-outline"> {{ $platform->business_model }} </span>
                    
                    <div>
                        @if($platform->is_bangladesh_focused)
                            <span class="text-blue-500 text-sm font-semibold">Bangladesh</span>
                        @else
                            <span class="text-green-500 text-sm font-semibold">International</span>
                        @endif
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-2 border-t border-base-300 pt-3">
                    <a href="{{ route('admin.platforms.show',$platform) }}" class="btn btn-ghost btn-sm gap-1">
                        <i class="fa-regular fa-eye"></i> View
                    </a>

                    @can('update',$platform)
                        <a href="{{ route('admin.platforms.edit',$platform) }}" class="btn btn-ghost btn-sm gap-1">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                    @endcan

                    @can('delete',$platform)
                        <button
                            type="button"
                            class="text-error gap-2"
                            title="Move platform to trash"
                            data-admin-action
                            data-action-url="{{ route('admin.platforms.destroy',$platform->slug) }}"
                            data-action-method="DELETE"
                            data-action-type="danger"
                            data-action-title="Move Platform to Trash"
                            data-action-description="Move “{{ $platform->name }}” to trash. You can restore it later from the Trash page."
                            data-action-icon="fa-solid fa-trash-can"
                            data-action-confirm-icon="fa-solid fa-trash-can"
                            data-action-confirm-text="Move to Trash"
                        >
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Delete</span>
                        </button>
                    @endcan
                </div>
            </div>

        @empty

            <div class="rounded-2xl border border-base-300 bg-base-100 px-5 py-16 text-center shadow-sm">
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-primary/10 text-2xl text-primary">
                    <i class="fa-solid fa-layer-group"></i>
                </div>

                <h3 class="mt-4 text-lg font-bold">No platforms found</h3>
                <p class="mt-1 text-sm opacity-50">Try changing your filters or create a new platform.</p>

                <a href="{{ route('admin.platforms.create') }}" class="btn btn-primary mt-5 gap-2">
                    <i class="fa-solid fa-plus"></i> Add Platform
                </a>
            </div>
        @endforelse
    </div>
    
    {{-- ====================== FOOTER INFORMATION ===================== --}}
    @if($platforms->isNotEmpty())
        <div class="flex flex-col gap-3 sm:rounded-lg shadow-sm border border-base-300 bg-base-100 px-5 py-4 text-xs text-base-content/50 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-success"></i>
                <span>
                    Platform represent <strong class="text-base-content/70">official platform destinations</strong> maintained in CareerVault.
                </span>
            </div>

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info"></i> CareerVault Platform Directory
            </div>
        </div>
    @endif
</div>
@endsection