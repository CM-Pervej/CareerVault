
@extends('layouts.admin.app')

@section('title', 'Company Sizes Trash | CareerVault')
@section('page_title', 'Company Sizes / Trash')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <header>
        <x-admin.ui.page-header
            icon="fa-solid fa-trash-can text-lg"
            title="Company Sizes Trash"
            title-color="text-error"
            title-bg-color="bg-error/10 text-error"
            description="Deleted company sizes remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records."
            :back-route="route('admin.company-sizes.index')"
            backText="Back to Company Sizes"
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

    {{-- ========================= STATISTICS ========================= --}}
    <details class="cv-section overflow-hidden rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="companySizeTrashStates" open>
        <x-admin.ui.disclosure-summary
            title="Trash Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of deleted company sizes and deletion activity."
        />

        <div class="grid grid-cols-1 gap-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))] p-1 sm:grid-cols-3">
            {{-- Total in Trash --}}
            <x-admin.ui.statistic
                title="Total in Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$totalTrashedCompanySizes"
                total-description="Deleted company sizes"
                last-icon="fa-solid fa-database"
            />

            {{-- Deleted Today --}}
            <x-admin.ui.statistic
                title="Deleted Today"
                title-icon="fa-solid fa-calendar-day"
                main-icon="fa-solid fa-calendar-check"
                main-icon-color="bg-warning/10 text-warning"
                :total="$deletedTodayCompanySizes"
                total-description="Records deleted today"
                last-icon="fa-solid fa-clock"
            />

            {{-- Deleted This Week --}}
            <x-admin.ui.statistic
                title="Deleted This Week"
                title-icon="fa-solid fa-calendar-week"
                main-icon="fa-solid fa-clock-rotate-left"
                main-icon-color="bg-info/10 text-info"
                :total="$deletedThisWeekCompanySizes"
                total-description="Records deleted in the last 7 days"
                last-icon="fa-solid fa-calendar"
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

    {{-- ========================= FILTERS ========================= --}}
    <x-admin.filters.panel
        title="Filter Deleted Company Sizes"
        description="Narrow deleted records by name, deleting user, or deletion period."
        section="companySizeTrashFilters"
        action="{{ route('admin.company-sizes.trash') }}"
        clear-url="{{ route('admin.company-sizes.trash') }}"
        result-label="deleted company size"
        result-label-plural="deleted company sizes"
        summary="Deleted company size categories"
        gridClass="grid grid-cols-1 gap-4 sm:grid-cols-5"

        :count="$companySizes->count()"
        :total="$totalTrashedCompanySizes"

        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search company sizes...',
                'value' => $search,
                'span' => 'sm:col-span-3',
            ],
            [
                'type' => 'select',
                'name' => 'deleted_by',
                'label' => 'Deleted By',
                'icon' => 'fa-solid fa-user-shield',
                'value' => $deletedBy,
                'options' => $users->mapWithKeys(
                    fn ($user) => [
                        $user->id => $user->name . ($user->role ? ' (' . $user->role . ')' : '')
                    ]
                )->toArray(),
            ],
            [
                'type' => 'select',
                'name' => 'deleted_period',
                'label' => 'Deletion Period',
                'icon' => 'fa-solid fa-calendar-days',
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
                'show' => filled($deletedBy),
                'icon' => 'fa-solid fa-user-shield',
                'label' => 'Deleted By',
                'value' => $users->firstWhere('id', (int) $deletedBy)?->name ?? $deletedBy,
                'color' => 'info',
            ],
            [
                'show' => filled($deletedPeriod),
                'icon' => 'fa-solid fa-calendar-days',
                'label' => 'Deletion Period',
                'value' => [
                    'today' => 'Today',
                    'yesterday' => 'Yesterday',
                    'last_7_days' => 'Last 7 Days',
                    'last_30_days' => 'Last 30 Days',
                    'last_3_months' => 'Last 3 Months',
                    'this_year' => 'This Year',
                ][$deletedPeriod] ?? $deletedPeriod,
                'color' => 'warning',
            ],
        ]"
    />

    {{-- ======================= TRASH DIRECTORY ======================= --}}
    <div class="mt-5 overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
        {{-- Directory Header --}}
        <div class="flex flex-col gap-3 border-b border-base-300 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                    <i class="fa-solid fa-trash-can text-lg"></i>
                </div>

                <div class="min-w-0">
                    <h2 class="font-semibold text-base-content">Deleted Company Sizes</h2>
                    <p class="mt-1 text-xs text-base-content/55">
                        Showing {{ $companySizes->firstItem() ?? 0 }}–{{ $companySizes->lastItem() ?? 0 }}
                        of {{ number_format($companySizes->total()) }} matching records
                    </p>
                </div>
            </div>

            <span class="badge badge-error badge-outline gap-1.5 self-start sm:self-center">
                <i class="fa-solid fa-trash-can"></i> Trash
            </span>
        </div>

        {{-- ======================= DESKTOP TABLE ======================= --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="table w-full">
                <thead>
                    <tr class="bg-base-200/60 text-xs uppercase tracking-wide text-base-content/60">
                        <th class="py-3 pl-5">Company Size</th> <th>Employee Range</th> <th>Companies</th> <th>Deleted At</th> <th>Deleted By</th> <th class="pr-5 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-base-300">
                    @forelse ($companySizes as $companySize)
                        <tr class="transition-colors hover:bg-base-200/30">
                            {{-- Company Size --}}
                            <td class="max-w-xs py-4 pl-5">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                        <i class="fa-solid fa-users"></i>
                                    </div>

                                    <div class="w-48 min-w-0">
                                        <a href="{{ route('admin.company-sizes.show', $companySize) }}" class="font-semibold text-base-content transition hover:text-primary"> {{ $companySize->name }} </a>

                                        @if ($companySize->description)
                                            <p class="mt-1 line-clamp-2 w-full text-sm leading-relaxed text-base-content/65"> {{ $companySize->description }} </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Employee Range --}}
                            <td>
                                <span class="whitespace-nowrap text-sm font-medium text-base-content">
                                    {{ number_format($companySize->min_employees) }}
                                    <span class="text-base-content/40">–</span>
                                    {{ $companySize->max_employees === null ? 'Unlimited' : number_format($companySize->max_employees) }}
                                </span>
                            </td>

                            {{-- Assigned Companies --}}
                            <td>
                                <span class="inline-flex items-center gap-1.5 text-sm text-base-content/75">
                                    <i class="fa-solid fa-building text-xs text-base-content/40"></i> {{ number_format($companySize->companies_count ?? 0) }}
                                </span>
                            </td>

                            {{-- Deletion Date --}}
                            <td>
                                <div class="flex items-start gap-2">
                                    <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                        <i class="fa-solid fa-clock text-xs"></i>
                                    </div>

                                    <div class="whitespace-nowrap">
                                        <div class="text-sm font-bold">
                                            {{ $companySize->deleted_at?->format('d M Y, h:i A') }}
                                        </div>

                                        <div class="text-xs text-base-content/50">
                                            {{ $companySize->deleted_at?->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Deleted By --}}
                            <td class="py-4">
                                @if($companySize->deletedBy)
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                            <i class="fa-solid fa-user-shield text-xs"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold" title="{{ $companySize->deletedBy->name }}">
                                                {{ $companySize->deletedBy->name }}
                                            </div>

                                            <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40">
                                                {{ str_replace('_', ' ', $companySize->deletedBy->role) }}
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

                            {{-- Desktop Actions: Restore + Dropdown --}}
                            <td class="pr-5">
                                <div class="flex justify-between items-center gap-2 xl:justify-end">
                                    @can('restore', $companySize)
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-success btn-outline gap-2 flex-1 sm:flex-none"
                                            title="Restore company size"
                                            data-admin-action
                                            data-action-url="{{ route('admin.company-sizes.restore', $companySize->slug) }}"
                                            data-action-method="PATCH"
                                            data-action-type="success"
                                            data-action-title="Restore Company Size"
                                            data-action-description="Restore “{{ $companySize->name }}” and return it to the active company sizes."
                                            data-action-icon="fa-solid fa-rotate-left"
                                            data-action-confirm-icon="fa-solid fa-rotate-left"
                                            data-action-confirm-text="Restore Company Size"
                                        >
                                            <i class="fa-solid fa-rotate-left"></i>

                                            <span>Restore</span>
                                        </button>
                                    @endcan

                                    @can('forceDelete', $companySize)
                                        <button
                                            type="button"
                                            class="btn btn-error btn-sm btn-outline gap-2 flex-1 sm:flex-none"
                                            title="Delete permanently"
                                            data-admin-action
                                            data-action-url="{{ route('admin.company-sizes.force-delete', $companySize->slug) }}"
                                            data-action-method="DELETE"
                                            data-action-type="danger"
                                            data-action-title="Delete Company Size Permanently"
                                            data-action-description="Permanently delete “{{ $companySize->name }}”. This action cannot be undone."
                                            data-action-icon="fa-solid fa-triangle-exclamation"
                                            data-action-confirm-icon="fa-solid fa-trash-can"
                                            data-action-confirm-text="Delete Forever"
                                        >
                                            <i class="fa-solid fa-trash-can"></i>

                                            <span class="whitespace-nowrap">Delete Forever</span>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-base-200 text-base-content/30">
                                    <i class="fa-solid fa-trash-can text-xl"></i>
                                </div>

                                <h3 class="mt-4 font-semibold text-base-content">
                                    {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'No matching deleted company sizes' : 'Company Sizes Trash is empty' }}
                                </h3>

                                <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">
                                    {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'Try adjusting your filters or clearing them to see more records.' : 'Deleted company sizes will appear here when moved to trash.' }}
                                </p>

                                @if (filled($search) || filled($deletedBy) || filled($deletedPeriod))
                                    <a href="{{ route('admin.company-sizes.trash') }}" class="btn btn-sm btn-outline mt-4 gap-2">
                                        <i class="fa-solid fa-rotate-left"></i> Clear Filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ======================= MOBILE CARDS ======================= --}}
        <div class="divide-y divide-base-300 md:hidden">
            @forelse ($companySizes as $companySize)
                <article class="p-4 transition-colors hover:bg-base-200/20">
                    {{-- Identity --}}
                    <div class="flex items-start gap-3">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                            <i class="fa-solid fa-users text-lg"></i>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="break-words font-semibold text-base-content"> {{ $companySize->name }} </h3>
                                <span class="badge badge-error badge-outline badge-sm">Deleted</span>
                            </div>
                            <p class="mt-1 break-all text-xs text-base-content/50"> {{ $companySize->slug }} </p>

                            @if ($companySize->description)
                                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-base-content/60"> {{ $companySize->description }} </p>
                            @endif
                        </div>
                    </div>

                    {{-- Key Details --}}
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <div class="rounded-lg border border-base-300 bg-base-200/30 p-3">
                            <p class="flex items-center gap-1.5 text-xs text-base-content/55">
                                <i class="fa-solid fa-user-group"></i> Employee Range
                            </p>
                            <p class="mt-1.5 break-words text-sm font-semibold text-base-content">
                                {{ number_format($companySize->min_employees) }}
                                <span class="font-normal text-base-content/40">–</span>
                                {{ $companySize->max_employees === null ? 'Unlimited' : number_format($companySize->max_employees) }}
                            </p>
                        </div>

                        <div class="rounded-lg border border-base-300 bg-base-200/30 p-3">
                            <p class="flex items-center gap-1.5 text-xs text-base-content/55">
                                <i class="fa-solid fa-building"></i> Companies
                            </p>
                            <p class="mt-1.5 text-sm font-semibold text-base-content">
                                {{ number_format($companySize->companies_count ?? 0) }}
                                <span class="font-normal text-xs text-base-content/50">assigned</span>
                            </p>
                        </div>
                    </div>

                    {{-- Deletion Information --}}
                    <div class="mt-3 flex items-start gap-3 rounded-lg border border-base-300/70 px-3 py-2.5">
                        <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-base-200 text-base-content/55">
                            <i class="fa-solid fa-user-shield text-sm"></i>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-base-content/50">Deleted by</p>
                            <p class="mt-0.5 break-words text-sm font-medium text-base-content">
                                {{ $companySize->deletedBy?->name ?? 'Unknown user' }}
                                @if ($companySize->deletedBy?->role)
                                    <span class="font-normal text-base-content/50">
                                        · {{ $companySize->deletedBy->role }}
                                    </span>
                                @endif
                            </p>
                        </div>

                        <div class="shrink-0 text-right">
                            <p class="text-xs text-base-content/50">Deleted at</p>
                            <p class="mt-0.5 text-sm font-medium text-base-content"> {{ $companySize->deleted_at?->format('M d, Y') ?? '—' }} </p>
                            <p class="mt-0.5 text-xs text-base-content/50"> {{ $companySize->deleted_at?->format('h:i A') }} </p>
                        </div>
                    </div>

                    {{-- Mobile Actions: Same Restore + Delete Actions --}}
                    <div class="mt-4 flex items-center gap-2 border-t border-base-300/70 pt-3">
                        @can('restore', $companySize)
                            <button
                                type="button"
                                class="btn btn-sm btn-success btn-outline min-w-0 flex-1 gap-2"
                                data-admin-action
                                data-action-url="{{ route('admin.company-sizes.restore', $companySize->slug) }}"
                                data-action-method="PATCH"
                                data-action-type="success"
                                data-action-title="Restore Company Size"
                                data-action-description="Restore “{{ $companySize->name }}” and return it to the active company sizes."
                                data-action-icon="fa-solid fa-rotate-left"
                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                data-action-confirm-text="Restore Company Size"
                            >
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>Restore</span>
                            </button>
                        @endcan

                        @can('forceDelete', $companySize)
                            <button
                                type="button"
                                class="btn btn-sm btn-error btn-outline min-w-0 flex-1 gap-2"
                                data-admin-action
                                data-action-url="{{ route('admin.company-sizes.force-delete', $companySize->slug) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Delete Company Size Permanently"
                                data-action-description="Permanently delete “{{ $companySize->name }}”. This action cannot be undone."
                                data-action-icon="fa-solid fa-triangle-exclamation"
                                data-action-confirm-icon="fa-solid fa-trash-can"
                                data-action-confirm-text="Delete Forever"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                                <span class="whitespace-nowrap">Delete Forever</span>
                            </button>
                        @endcan
                    </div>
                </article>
            @empty
                <div class="px-5 py-14 text-center">
                    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-base-200 text-base-content/30">
                        <i class="fa-solid fa-trash-can text-xl"></i>
                    </div>

                    <h3 class="mt-4 font-semibold text-base-content">
                        {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'No matching deleted company sizes' : 'Company Sizes Trash is empty' }}
                    </h3>

                    <p class="mx-auto mt-1 max-w-sm text-sm text-base-content/50">
                        {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'Try adjusting your filters or clearing them to see more records.' : 'Deleted company sizes will appear here when moved to trash.' }}
                    </p>

                    @if (filled($search) || filled($deletedBy) || filled($deletedPeriod))
                        <a href="{{ route('admin.company-sizes.trash') }}" class="btn btn-sm btn-outline mt-4 gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- ======================== PAGINATION ======================== --}}
        @if ($companySizes->hasPages())
            <div class="border-t border-base-300 px-4 py-4 sm:px-5">
                {{ $companySizes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection