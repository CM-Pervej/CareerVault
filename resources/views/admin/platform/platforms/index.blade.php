@extends('layouts.admin.app')

@section('title','Platforms | CareerVault')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- =========================== HEADER =========================== --}}
    <x-admin.ui.page-header
        icon="fa-solid fa-tree text-lg"    
        title="Platforms"
        title-color="text-primary"
        title-bg-color="bg-primary/10 text-primary"
        description="Manage job platforms, career sources, and their availability."
        :create-route="route('admin.platforms.create')"
        create-text="Add Platform"
        :trash-route="route('admin.platforms.trash')"
        :trash-count="$trashedPlatformsCount"
    />

    {{-- ========================= STATISTICS ========================= --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="platformStates" open>
        <x-admin.ui.disclosure-summary 
            title="Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of platforms and their current status."
        />

        {{-- ===================== OVERVIEW STATS ====================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Total --}}
            <x-admin.ui.statistic
                title="Pages"
                title-icon="fa-solid fa-file-lines"
                main-icon="fa-solid fa-file-lines"
                main-icon-color="bg-info/10 text-info"
                :total="$totalPlatforms"
                total-description="Total platform pages"
                last-icon="fa-solid fa-database"
            />

            {{-- Active --}}
            <x-admin.ui.statistic
                title="Active"
                title-icon="fa-solid fa-circle-check"
                main-icon="fa-solid fa-toggle-on"
                main-icon-color="bg-success/10 text-success"
                :total="$activePlatforms"
                total-description="Currently active pages"
                last-icon="fa-solid fa-eye"
            />

            {{-- Inactive --}}
            <x-admin.ui.statistic
                title="Inactive"
                title-icon="fa-solid fa-circle-xmark"
                main-icon="fa-solid fa-toggle-off"
                main-icon-color="bg-warning/10 text-warning"
                :total="$inactivePlatforms"
                total-description="Currently inactive pages"
                last-icon="fa-solid fa-eye-slash"
            />

            {{-- Trash --}}
            <x-admin.ui.statistic
                title="Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$trashedPlatformsCount"
                total-description="Soft-deleted pages"
                last-icon="fa-solid fa-clock-rotate-left"
            />
        </div>
    </details>
    
    {{-- ========================== FILTERS =========================== --}}
    <x-admin.filters.panel
        title="Filter Platforms"
        description="Narrow the list by job type, business model, status, or Bangladesh focus"
        section="platformFilters"
        action="{{ route('admin.platforms.index') }}"
        clear-url="{{ route('admin.platforms.index') }}"
        result-label="platform"
        result-label-plural="platforms"
        summary="Career and job platforms"
        gridClass="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6"

        :count="$platforms->count()"
        :total="$totalPlatforms"

        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search platforms...',
                'value' => $search,
                'span' => 'col-span-2',
            ],
            [
                'type' => 'select',
                'name' => 'job_type',
                'label' => 'Job Type',
                'icon' => 'fa-solid fa-briefcase',
                'value' => $jobType,
                'options' => collect($jobTypes)->mapWithKeys(
                    fn ($type) => [$type => $type]
                )->toArray(),
            ],
            [
                'type' => 'select',
                'name' => 'business_model',
                'label' => 'Business Model',
                'icon' => 'fa-solid fa-coins',
                'value' => $businessModel,
                'options' => collect($businessModels)->mapWithKeys(
                    fn ($model) => [$model => $model]
                )->toArray(),
            ],
            [
                'type' => 'select',
                'name' => 'status',
                'label' => 'Status',
                'icon' => 'fa-solid fa-power-off',
                'value' => $status,
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ],
            ],
            [
                'type' => 'select',
                'name' => 'bangladesh_focus',
                'label' => 'Bangladesh Focus',
                'icon' => 'fa-solid fa-flag',
                'value' => $bangladeshFocus,
                'options' => [
                    'focused' => 'Yes',
                    'general' => 'No',
                ],
            ],
        ]"

        :active-filters="[
            [
                'show' => filled($search),
                'icon' => 'fa-solid fa-magnifying-glass',
                'label' => 'Search',
                'value' => $search,
                'color' => 'primary',
            ],
            [
                'show' => filled($jobType),
                'icon' => 'fa-solid fa-briefcase',
                'label' => 'Job Type',
                'value' => $jobType,
                'color' => 'info',
            ],
            [
                'show' => filled($businessModel),
                'icon' => 'fa-solid fa-coins',
                'label' => 'Business Model',
                'value' => $businessModel,
                'color' => 'secondary',
            ],
            [
                'show' => $status !== null && $status !== '',
                'icon' => 'fa-solid fa-power-off',
                'label' => 'Status',
                'value' => ucfirst($status),
                'color' => 'warning',
            ],
            [
                'show' => filled($bangladeshFocus),
                'icon' => 'fa-solid fa-flag',
                'label' => 'Bangladesh Focus',
                'value' => $bangladeshFocus === 'focused'
                    ? 'Bangladesh Focused'
                    : 'General',
                'color' => 'success',
            ],
        ]"
    />

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

    {{-- Table Footer // Pagination --}}
    @if($platforms->hasPages())
        <div class="py-1">
            {{ $platforms->links() }}
        </div>
    @endif
    
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