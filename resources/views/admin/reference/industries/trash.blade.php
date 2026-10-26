@extends('layouts.admin.app')

@section('title', 'Industry Trash | CareerVault')
@section('page_title', 'Industry / Trash')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- Header --}}
    <x-admin.ui.page-header
        icon="fa-solid fa-trash-can text-lg"    
        title="Industry Trash"
        title-bg-color="bg-error/10 text-error"
        title-color="text-error"
        description="Deleted industries remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records."
        :back-route="route('admin.industries.index')"
        backText="Back to Industry"
    />

    {{-- Statistics --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="industryTrashStates" open>
        <x-admin.ui.stat-summary description="Overview of deleted industries and their deletion activity." />

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">

            {{-- Trashed --}}
            <x-admin.ui.statistic
                title="Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$trashedIndustries"
                total-description="Total trashed industries"
                last-icon="fa-solid fa-database"
            />

            {{-- Recently Deleted --}}
            <x-admin.ui.statistic
                title="Recently Deleted"
                title-icon="fa-solid fa-clock"
                main-icon="fa-solid fa-calendar-xmark"
                main-icon-color="bg-warning/10 text-warning"
                :total="$recentlyDeleted"
                total-description="Deleted in the last 30 days"
                last-icon="fa-solid fa-clock-rotate-left"
            />

            {{-- Deleted Today --}}
            <x-admin.ui.statistic
                title="Deleted Today"
                title-icon="fa-solid fa-calendar-day"
                main-icon="fa-solid fa-trash-can-arrow-up"
                main-icon-color="bg-info/10 text-info"
                :total="$deletedToday"
                total-description="Deleted today"
                last-icon="fa-solid fa-calendar"
            />

            {{-- Deleted This Month --}}
            <x-admin.ui.statistic
                title="This Month"
                title-icon="fa-solid fa-calendar"
                main-icon="fa-solid fa-chart-column"
                main-icon-color="bg-secondary/10 text-secondary"
                :total="$deletedThisMonth"
                total-description="Deleted this month"
                last-icon="fa-solid fa-arrow-trend-up"
            />

            {{-- Access Control --}}
            <x-admin.ui.statistic
                title="Access Control"
                title-icon="fa-solid fa-calendar"
                main-icon="fa-solid fa-shield-halved"
                main-icon-color="bg-warning/10 text-warning"
                sp="Super Admin"
                total-description="Restore and permanent deletion permissions"
                last-icon="fa-solid fa-arrow-trend-up"
            />
        </div>
    </details>

    {{-- Filters --}}
    <x-admin.filters.panel
        title="Filter Trashed Industries"
        description="Narrow the trash by industry, parent, deletion user, or deletion period"
        section="industryTrashFilters"
        action="{{ route('admin.industries.trash') }}"
        clear-url="{{ route('admin.industries.trash') }}"
        result-label="industry"
        result-label-plural="industries"
        summary="Deleted industries and their deletion history"

        :count="$industries->count()"
        :total="$industries->total()"

        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search deleted industries...',
                'value' => $search,
                'span' => 'sm:col-span-2 lg:col-span-2',
            ],
            [
                'type' => 'select',
                'name' => 'parent',
                'label' => 'Parent Industry',
                'icon' => 'fa-solid fa-sitemap',
                'value' => $parent,
                'options' => $parents->mapWithKeys(
                    fn ($item) => [$item->id => $item->name]
                )->toArray(),
                'empty_label' => 'Parent Industries',
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
                'empty_label' => 'All Users',
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
                'empty_label' => 'All Periods',
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
                'show' => filled($parent),
                'icon' => 'fa-solid fa-sitemap',
                'label' => 'Parent Industry',
                'value' => $parents->firstWhere('id', $parent)?->name ?? $parent,
                'color' => 'info',
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

    <div class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-10 sm:mt-5">
        {{-- Toolbar --}}
        <div class="px-5 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2 font-black">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    Deleted Industries
                </div>
                <span class="badge badge-error badge-outline gap-1">
                    <i class="fa-solid fa-box-archive text-[10px]"></i> Trash
                </span>
            </div>

            <p class="mt-3 text-center sm:text-start text-xs text-base-content/50">
                {{ number_format($industries->count()) }} industry(s) shown
                •
                {{ number_format($industries->total()) }} total in current result
            </p>
        </div>

        @if ($industries->count())    
            {{-- ====================== Desktop Table ====================== --}}
            <div class="hidden overflow-x-auto md:block bg-base-100">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200/50 text-xs uppercase tracking-wider text-base-content/50">
                            <th>Industry</th> <th>Parent</th> <th>Status</th> <th>Deleted</th> <th>Deleted By</th> <th class="text-right">Actions</th>
                        </tr>
                    </thead>
        
                    <tbody>
                        @foreach($industries as $industry)
                            <tr class="hover:bg-base-200/40">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class=""relative flex size-10 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-base-300 bg-base-200 shadow-sm"">
                                                @if($industry->icon)
                                                    <i class="{{ $industry->icon }} text-lg"></i>
                                                @else
                                                    <i class="fa-solid fa-industry text-base-content/50"></i>
                                                @endif
                                            </div>
                                        </div>
        
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.industries.show', $industry) }}" class="font-bold transition hover:text-primary">
                                                {{ $industry->name }}
                                            </a>

                                            <div class="text-xs text-base-content/50"> {{ $industry->slug }} </div>
                                        </div>
                                    </div>
                                </td>
        
                                <td>
                                    @if($industry->parent)
                                        <span class="badge badge-info badge-outline"> {{ $industry->parent->name }} </span>
                                    @else
                                        <span class="text-base-content/40">Top Level</span>
                                    @endif
                                </td>
        
                                <td>
                                    @if($industry->is_active)
                                        <span class="badge badge-success gap-1">
                                            <i class="fa-solid fa-circle-check"></i> Active
                                        </span>
                                    @else
                                        <span class="badge badge-warning gap-1">
                                            <i class="fa-solid fa-circle-xmark"></i> Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- Deleted --}}
                                <td>
                                    <div class="flex items-start gap-2">
                                        <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                            <i class="fa-solid fa-clock text-xs"></i>
                                        </div>

                                        <div class="whitespace-nowrap">
                                            <div class="text-sm font-bold">
                                                {{ $industry->deleted_at?->format('d M Y, h:i A') }}
                                            </div>

                                            <div class="text-xs text-base-content/50">
                                                {{ $industry->deleted_at?->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Deleted By --}}
                                <td class="py-4">
                                    @if($industry->deletedBy)
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                                <i class="fa-solid fa-user-shield text-xs"></i>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold" title="{{ $industry->deletedBy->name }}">
                                                    {{ $industry->deletedBy->name }}
                                                </div>

                                                <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40">
                                                    {{ str_replace('_', ' ', $industry->deletedBy->role) }}
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
        
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-success btn-sm btn-outline gap-2"
                                            title="Restore page"
                                            data-admin-action
                                            data-action-url="{{ route('admin.industries.restore', $industry->slug) }}"
                                            data-action-method="PATCH"
                                            data-action-type="success"
                                            data-action-title="Restore Platform Page"
                                            data-action-description="Restore “{{ $industry->name }}” and return it to the active platform pages."
                                            data-action-icon="fa-solid fa-rotate-left"
                                            data-action-confirm-icon="fa-solid fa-rotate-left"
                                            data-action-confirm-text="Restore Page"
                                        >
                                            <i class="fa-solid fa-rotate-left"></i>
        
                                            <span class="hidden lg:inline">Restore</span>
                                        </button>
                                        
                                        <button
                                            type="button"
                                            class="btn btn-error btn-sm btn-outline gap-2"
                                            title="Delete permanently"
                                            data-admin-action
                                            data-action-url="{{ route('admin.industries.force-delete', $industry->slug) }}"
                                            data-action-method="DELETE"
                                            data-action-type="danger"
                                            data-action-title="Delete Platform Page Permanently"
                                            data-action-description="Permanently delete “{{ $industry->name }}”. This action cannot be undone."
                                            data-action-icon="fa-solid fa-triangle-exclamation"
                                            data-action-confirm-icon="fa-solid fa-trash-can"
                                            data-action-confirm-text="Delete Forever"
                                        >
                                            <i class="fa-solid fa-trash-can"></i>
        
                                            <span class="hidden lg:inline whitespace-nowrap">Delete Forever</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        
            {{-- ======================= Mobile Cards ======================== --}}
            <div class="md:hidden space-y-2">
                @foreach($industries as $industry)
                    <article class="rounded-lg border border-base-300 bg-base-100 shadow-sm overflow-hidden">
                        {{-- ===================== HEADER ===================== --}}
                        <div class="flex items-center gap-3 px-4 py-3">
                            {{-- Icon --}}
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-lg" style="{{ $industry->color ? "background-color: {$industry->color}15; color: {$industry->color};" : '' }}">
                                @if($industry->icon)
                                    <i class="{{ $industry->icon }} text-base"></i>
                                @else
                                    <i class="fa-solid fa-industry text-base-content/50"></i>
                                @endif
                            </div>
        
                            {{-- Name --}}
                            <div class="min-w-0 flex-1">
                                <div class="truncate font-semibold leading-tight"> {{ $industry->name }} </div>
                                <div class="mt-0.5 truncate text-xs text-base-content/50"> {{ $industry->slug }} </div>
                            </div>
        
                            {{-- Trash badge --}}
                            <span class="badge badge-error badge-sm shrink-0 gap-1">
                                <i class="fa-solid fa-trash-can text-[10px]"></i> Trash
                            </span>
                        </div>
        
                        {{-- ===================== DELETION INFO ===================== --}}
                        <div class="border-y border-base-300 bg-base-200/40 px-4 py-3">
                            <div class="flex items-center justify-between gap-4">
                                {{-- Deleted By --}}
                                <div class="min-w-0">
                                    <div class="text-[11px] font-medium uppercase tracking-wide text-base-content/50">Deleted By</div>
                                    <div class="mt-0.5 truncate text-sm font-medium">
                                        {{ $industry->deletedBy?->name ?? 'Unknown' }}
                                        @if($industry->deletedBy?->role)
                                            <span class="font-normal text-xs text-base-content/50">
                                                · {{ ucfirst(str_replace('_', ' ', $industry->deletedBy->role)) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                {{-- Deleted At --}}
                                <div class="shrink-0 text-right">
                                    <div class="text-[11px] font-medium uppercase tracking-wide text-base-content/50">Deleted</div>
                                    <div class="mt-0.5 text-sm font-medium"> {{ $industry->deleted_at?->format('d M Y') }} </div>
                                    <div class="text-xs text-base-content/50"> {{ $industry->deleted_at?->format('h:i A') }} </div>
                                </div>
                            </div>
                        </div>
        
                        {{-- ===================== META ===================== --}}
                        <div class="flex items-center gap-2 px-4 py-2.5 text-xs">
                            {{-- Parent --}}
                            @if($industry->parent)
                                <span class="badge badge-outline badge-sm gap-1 max-w-[60%] truncate">
                                    <i class="fa-solid fa-sitemap"></i> {{ $industry->parent->name }}
                                </span>
                            @else
                                <span class="badge badge-ghost badge-sm gap-1">
                                    <i class="fa-solid fa-layer-group"></i> Top Level
                                </span>
                            @endif
        
                            {{-- Status --}}
                            @if($industry->is_active)
                                <span class="badge badge-success badge-outline badge-sm gap-1">
                                    <i class="fa-solid fa-circle-check"></i> Active
                                </span>
                            @else
                                <span class="badge badge-warning badge-outline badge-sm gap-1">
                                    <i class="fa-solid fa-circle-xmark"></i> Inactive
                                </span>
                            @endif
                        </div>
        
                        {{-- ===================== ACTIONS ===================== --}}
                        <div class="flex items-center gap-2 border-t border-base-300 px-4 py-3">
                            <button
                                type="button"
                                class="btn btn-success btn-sm gap-2 flex-1"
                                title="Restore page"
                                data-admin-action
                                data-action-url="{{ route('admin.industries.restore', $industry->slug) }}"
                                data-action-method="PATCH"
                                data-action-type="success"
                                data-action-title="Restore Platform Page"
                                data-action-description="Restore “{{ $industry->name }}” and return it to the active platform pages."
                                data-action-icon="fa-solid fa-rotate-left"
                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                data-action-confirm-text="Restore Page"
                            >
                                <i class="fa-solid fa-rotate-left"></i>

                                <span>Restore</span>
                            </button>
                            
                            <button
                                type="button"
                                class="btn btn-error btn-sm gap-2 flex-1"
                                title="Delete permanently"
                                data-admin-action
                                data-action-url="{{ route('admin.industries.force-delete', $industry->slug) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Delete Platform Page Permanently"
                                data-action-description="Permanently delete “{{ $industry->name }}”. This action cannot be undone."
                                data-action-icon="fa-solid fa-triangle-exclamation"
                                data-action-confirm-icon="fa-solid fa-trash-can"
                                data-action-confirm-text="Delete Forever"
                            >
                                <i class="fa-solid fa-trash-can"></i>

                                <span class="whitespace-nowrap">Delete Forever</span>
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($industries->hasPages())
                <div class="border-t border-base-300 px-5 py-4">
                    {{ $industries->links() }}
                </div>
            @endif
            
        @else
            {{-- Empty State --}}
            <div class="px-8 py-20 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-base-200 text-base-content/35">
                    <i class="fa-solid fa-trash-can text-3xl"></i>
                </div>

                <x-admin.ui.empty-state
                    title="No deleted industries"
                    description="The trash is currently empty. Deleted industries will appear here and can be restored while they remain in the trash."
                    :action-route="route('admin.industries.index')"
                    action-text="View Industries"
                />
            </div>
        @endif
    </div>

    {{-- ===================== FOOTER INFORMATION ===================== --}}
    <x-admin.ui.footer-info
        :file-name="$industries"
        description="Industries represent <strong>official destinations and resources</strong> maintained in CareerVault."
    />
</div>
@endsection