@extends('layouts.admin.app')

@section('title', 'Platform Trash | CareerVault')
@section('page_title', 'Platforms / Trash')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- Header --}}
    <header>
        <x-admin.ui.page-header
            icon="fa-solid fa-trash-can text-lg"    
            title="Platforms Trash"
            title-bg-color="bg-error/10 text-error"
            title-color="text-error"
            description="Deleted platforms remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records."
            :back-route="route('admin.platforms.index')"
            backText="Back to Platforms"
        />
    
        <div class="my-4 flex flex-wrap justify-center sm:justify-start gap-2">
            <span class="badge badge-error badge-outline gap-1">
                <i class="fa-solid fa-trash text-[10px]"></i> Soft Deleted
            </span>
    
            <span class="badge badge-warning badge-outline gap-1">
                <i class="fa-solid fa-lock text-[10px]"></i> Super Admin Access
            </span>
        </div>
    </header>

    {{-- Statistics --}}
    <details class="cv-section overflow-hidden sm:rounded-sm border border-base-300 bg-base-100 shadow-sm" data-section="platformTrashStates" open>
        <x-admin.ui.disclosure-summary 
            title="Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of platforms and their current status."
        />

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 gap-0 sm:gap-1 md:grid-cols-3 p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Deleted Pages --}}
            <x-admin.ui.statistic
                title="Deleted Platforms"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-trash-can"
                main-icon-color="bg-error/10 text-error"
                :total="$platforms->total()"
                total-description="Deleted platforms matching the current filters"
                last-icon="fa-solid fa-database"
            />
    
            {{-- Filtered Results --}}
            <x-admin.ui.statistic
                title="Filtered Results"
                title-icon="fa-solid fa-sliders"
                main-icon="fa-solid fa-list-check"
                main-icon-color="bg-primary/10 text-primary"
                :total="$platforms->count()"
                total-description="Results currently displayed on this page"
                last-icon="fa-solid fa-database"
            />
    
            {{-- Access Control --}}
            <x-admin.ui.statistic
                title="Access Control"
                title-icon="fa-solid fa-shield-halved"
                main-icon="fa-solid fa-shield-halved"
                main-icon-color="bg-warning/10 text-warning"
                sp="Super Admin"
                total-description="Restore and permanent deletion permissions"
                last-icon="fa-solid fa-arrow-trend-up"
            />
        </div>
    </details>

    {{-- =========================== Filters =========================== --}}
    <x-admin.filters.panel
        title="Filter Trashed Platforms"
        description="Narrow the trash by job type, business model, deletion user, or deletion period"
        section="platformFilters"
        action="{{ route('admin.platforms.trash') }}"
        clear-url="{{ route('admin.platforms.trash') }}"
        result-label="platform"
        result-label-plural="platforms"
        summary="Deleted career and job platforms"
        gridClass="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6"

        :count="$platforms->count()"
        :total="$platforms->total()"

        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search deleted platforms...',
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
                'name' => 'deleted_by',
                'label' => 'Deleted By',
                'icon' => 'fa-solid fa-user-xmark',
                'value' => $deletedBy,
                'options' => $deletedByUsers->mapWithKeys(
                    fn ($user) => [
                        $user->id => $user->name . ($user->role
                            ? ' (' . ucfirst(str_replace('_', ' ', $user->role)) . ')'
                            : ''),
                    ]
                )->toArray(),
            ],
            [
                'type' => 'select',
                'name' => 'deleted_period',
                'label' => 'Deleted Period',
                'icon' => 'fa-solid fa-calendar-xmark',
                'value' => $deletedPeriod,
                'options' => [
                    'today' => 'Today',
                    'yesterday' => 'Yesterday',
                    'last_7_days' => 'Last 7 Days',
                    'last_30_days' => 'Last 30 Days',
                    'last_3_months' => 'Last 3 Months',
                    'this_year' => 'This Year',
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
                'show' => filled($deletedBy),
                'icon' => 'fa-solid fa-user-xmark',
                'label' => 'Deleted By',
                'value' => ($user = $deletedByUsers->firstWhere('id', $deletedBy))
                    ? $user->name . ($user->role
                        ? ' (' . ucfirst(str_replace('_', ' ', $user->role)) . ')'
                        : '')
                    : $deletedBy,
                'color' => 'warning',
            ],
            [
                'show' => filled($deletedPeriod),
                'icon' => 'fa-solid fa-calendar-xmark',
                'label' => 'Deleted Period',
                'value' => match ($deletedPeriod) {
                    'today' => 'Today',
                    'yesterday' => 'Yesterday',
                    'last_7_days' => 'Last 7 Days',
                    'last_30_days' => 'Last 30 Days',
                    'last_3_months' => 'Last 3 Months',
                    'this_year' => 'This Year',
                    default => $deletedPeriod,
                },
                'color' => 'success',
            ],
        ]"
    />

    {{-- Trash Table --}}
    <div class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-10 sm:mt-5">
        {{-- Table Header --}}
        <div class="px-5 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2 font-black">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    Deleted Platforms
                </div>

                @if($platforms->total())
                    <span class="badge badge-error badge-outline gap-1.5">
                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                        {{ $platforms->total() }} {{ Str::plural('item', $platforms->total()) }}
                    </span>
                @endif
            </div>

            <p class="mt-3 text-xs text-base-content/50 text-center sm:text-start">
                Deleted platforms remain recoverable until permanently removed.
                <span class="font-bold text-gray-500">
                    {{ number_format($platforms->count()) }} platform(s) shown
                    •
                    {{ number_format($platforms->total()) }} total in current result
                </span>
            </p>
        </div>

        @if($platforms->count())
            {{-- ====================== Desktop Table ====================== --}}
            <div class="hidden overflow-x-auto md:block bg-base-100">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200/50 text-xs uppercase tracking-wider text-base-content/50">
                            <th>Platform</th> <th>Job Type</th> <th>Business Model</th> <th>Deleted AT</th> <th>Deleted BY</th> <th class="text-right whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($platforms as $platform)
                            <tr class="hover:bg-base-200/40">
                                {{-- Platform --}}
                                <td>
                                    <div class="flex min-w-[250px] items-center gap-3">
                                        @if($platform->logo)
                                            <img src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="h-11 w-11 shrink-0 rounded-xl border border-base-300 bg-base-200 object-contain p-1">
                                        @else
                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-base-300 bg-base-200" style="{{ $platform->color ? 'color: '.$platform->color : '' }}">
                                                <i class="{{ $platform->icon ?: 'fa-solid fa-globe' }}"></i>
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <a href="{{ route('admin.platforms.show',$platform) }}" class="truncate text-[15px] font-bold tracking-tight transition-colors hover:text-primary"> {{ $platform->name }} </a>

                                            @if($platform->official_name)
                                                <div class="mt-0.5 max-w-[300px] truncate text-xs text-base-content/50"> {{ $platform->official_name }} </div>
                                            @else
                                                <div class="mt-1 text-[11px] text-base-content/40"> {{ $platform->slug }} </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Job Type --}}
                                <td>
                                    @if($platform->job_type)
                                        <span class="badge badge-ghost badge-sm"> {{ $platform->job_type }} </span>
                                    @else
                                        <span class="text-xs text-base-content/40">—</span>
                                    @endif
                                </td>

                                {{-- Business Model --}}
                                <td>
                                    @if($platform->business_model)
                                        <span class="badge badge-ghost badge-sm"> {{ $platform->business_model }} </span>
                                    @else
                                        <span class="text-xs text-base-content/40">—</span>
                                    @endif
                                </td>

                                {{-- Deleted --}}
                                <td>
                                    <div class="flex items-start gap-2">
                                        <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                            <i class="fa-solid fa-clock text-xs"></i>
                                        </div>

                                        <div class="whitespace-nowrap">
                                            <div class="text-sm font-bold"> {{ $platform->deleted_at?->format('d M Y, h:i A') }} </div>
                                            <div class="text-xs text-base-content/50"> {{ $platform->deleted_at?->diffForHumans() }} </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Deleted By --}}
                                <td class="py-4">
                                    @if($platform->deletedBy)
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                                <i class="fa-solid fa-user-shield text-xs"></i>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold" title="{{ $platform->deletedBy->name }}"> {{ $platform->deletedBy->name }} </div>

                                                <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40"> {{ str_replace('_', ' ', $platform->deletedBy->role) }} </div>
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
                                <td class="whitespace-nowrap">
                                    <div class="flex justify-end gap-2">
                                        {{-- Restore --}}
                                        @can('restore', $platform)
                                            <button
                                                type="button"
                                                class="btn btn-success btn-sm btn-outline gap-2"
                                                title="Restore platform"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platforms.restore', $platform->slug) }}"
                                                data-action-method="PATCH"
                                                data-action-type="success"
                                                data-action-title="Restore Platform"
                                                data-action-description="Restore “{{ $platform->name }}” and bring its platform data back from trash."
                                                data-action-confirm="Restore Platform"
                                            >
                                                <i class="fa-solid fa-rotate-left"></i>
                                                <span class="hidden lg:inline">Restore</span>
                                            </button>
                                        @endcan

                                        {{-- Permanent Delete --}}
                                        @can('forceDelete', $platform)
                                            <button
                                                type="button"
                                                class="btn btn-error btn-sm btn-outline gap-2"
                                                title="Delete permanently"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platforms.force-delete', $platform->slug) }}"
                                                data-action-method="DELETE"
                                                data-action-type="danger"
                                                data-action-title="Permanently Delete Platform"
                                                data-action-description="Permanently delete “{{ $platform->name }}”. This will permanently remove the platform and its owned data. This action cannot be undone."
                                                data-action-confirm="Delete Permanently"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>
                                                <span class="hidden lg:inline">Delete Forever</span>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($platforms->hasPages())
                <div class="border-t border-base-300 px-5 py-4">
                    {{ $platforms->links() }}
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="px-8 py-20 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-base-200 text-base-content/35">
                    <i class="fa-solid fa-trash-can text-3xl"></i>
                </div>

                <x-admin.ui.empty-state
                    title="No deleted platforms"
                    description="The trash is currently empty. Deleted platforms will appear here and can be restored while they remain in the trash."
                    :action-route="route('admin.platforms.index')"
                    action-text="View Platforms"
                />
            </div>
        @endif
    </div>
    
    {{-- ====================== FOOTER INFORMATION ===================== --}}
    <x-admin.ui.footer-info
        :file-name="$platforms"
        description="Platforms represent <strong>official destinations and resources</strong> maintained in CareerVault."
    />
</div>
@endsection