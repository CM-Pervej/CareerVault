
@extends('layouts.admin.app')

@section('title', 'Ownership Types Trash | CareerVault')
@section('page_title', 'Ownership Types / Trash')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <header>
        <x-admin.ui.page-header
            icon="fa-solid fa-trash-can text-lg"
            title="Ownership Types Trash"
            title-color="text-error"
            title-bg-color="bg-error/10 text-error"
            description="Deleted ownership types remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records."
            :back-route="route('admin.ownership-types.index')"
            backText="Back to Ownership Types"
        />

        <div class="my-4 flex flex-wrap justify-center gap-2 sm:justify-start">
            <span class="badge badge-error badge-outline gap-1">
                <i class="fa-solid fa-trash text-[10px]"></i> Soft Deleted
            </span>
            <span class="badge badge-warning badge-outline gap-1">
                <i class="fa-solid fa-lock text-[10px]"></i> Super Admin Access
            </span>
        </div>
    </header>

    {{-- ========================= STATISTICS ========================= --}}
    <details class="cv-section overflow-hidden rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="ownershipTypeTrashStates" open>
        <x-admin.ui.disclosure-summary
            title="Trash Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of deleted ownership types and deletion activity."
        />

        <div class="grid grid-cols-1 gap-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))] p-1 sm:grid-cols-3">
            {{-- Total in Trash --}}
            <x-admin.ui.statistic
                title="Total in Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$stats['trashed']"
                total-description="Deleted ownership types"
                last-icon="fa-solid fa-database"
            />

            {{-- Deleted Today --}}
            <x-admin.ui.statistic
                title="Deleted Today"
                title-icon="fa-solid fa-calendar-day"
                main-icon="fa-solid fa-calendar-check"
                main-icon-color="bg-warning/10 text-warning"
                :total="$stats['deleted_today']"
                total-description="Records deleted today"
                last-icon="fa-solid fa-clock"
            />

            {{-- Deleted This Week --}}
            <x-admin.ui.statistic
                title="Deleted This Week"
                title-icon="fa-solid fa-calendar-week"
                main-icon="fa-solid fa-clock-rotate-left"
                main-icon-color="bg-info/10 text-info"
                :total="$stats['deleted_this_week']"
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
        title="Filter Deleted Ownership Types"
        description="Narrow deleted records by name, deleting user, or deletion period."
        section="ownershipTypeTrashFilters"
        action="{{ route('admin.ownership-types.trash') }}"
        clear-url="{{ route('admin.ownership-types.trash') }}"
        result-label="deleted ownership type"
        result-label-plural="deleted ownership types"
        summary="Deleted ownership type categories"
        gridClass="grid grid-cols-1 gap-4 sm:grid-cols-5"
        :count="$ownershipTypes->count()"
        :total="$stats['trashed']"
        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search ownership types...',
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
                    <h2 class="font-semibold text-base-content">Deleted Ownership Types</h2>
                    <p class="mt-1 text-xs text-base-content/55">
                        Showing {{ $ownershipTypes->firstItem() ?? 0 }}–{{ $ownershipTypes->lastItem() ?? 0 }}
                        of {{ number_format($ownershipTypes->total()) }} matching records
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
                        <th class="py-3 pl-5">Ownership Type</th>
                        <th>Slug</th>
                        <th>Deleted At</th>
                        <th>Deleted By</th>
                        <th class="pr-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-300">
                    @forelse ($ownershipTypes as $item)
                        <tr class="transition-colors hover:bg-base-200/30">
                            {{-- Ownership Type --}}
                            <td class="max-w-xs py-4 pl-5">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <div class="w-48 min-w-0">
                                        <a href="{{ route('admin.ownership-types.show', $item) }}" class="font-semibold text-base-content transition hover:text-primary">
                                            {{ $item->name }}
                                        </a>
                                        @if ($item->description)
                                            <p class="mt-1 line-clamp-2 w-full text-sm leading-relaxed text-base-content/65">
                                                {{ $item->description }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Slug --}}
                            <td>
                                <code class="text-xs">{{ $item->slug }}</code>
                            </td>

                            {{-- Deletion Date --}}
                            <td>
                                <div class="flex items-start gap-2">
                                    <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                        <i class="fa-solid fa-clock text-xs"></i>
                                    </div>
                                    <div class="whitespace-nowrap">
                                        <div class="text-sm font-bold">
                                            {{ $item->deleted_at?->format('d M Y, h:i A') }}
                                        </div>
                                        <div class="text-xs text-base-content/50">
                                            {{ $item->deleted_at?->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Deleted By --}}
                            <td class="py-4">
                                @if ($item->deleter)
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                            <i class="fa-solid fa-user-shield text-xs"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold" title="{{ $item->deleter->name }}">
                                                {{ $item->deleter->name }}
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40">
                                                {{ str_replace('_', ' ', $item->deleter->role) }}
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

                            {{-- Desktop Actions --}}
                            <td class="pr-5">
                                <div class="flex justify-between items-center gap-2 xl:justify-end">
                                    @can('restore', $item)
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-success btn-outline gap-2 flex-1 sm:flex-none"
                                            title="Restore ownership type"
                                            data-admin-action
                                            data-action-url="{{ route('admin.ownership-types.restore', $item->id) }}"
                                            data-action-method="PATCH"
                                            data-action-type="success"
                                            data-action-title="Restore Ownership Type"
                                            data-action-description="Restore “{{ $item->name }}” and return it to the active ownership types."
                                            data-action-icon="fa-solid fa-rotate-left"
                                            data-action-confirm-icon="fa-solid fa-rotate-left"
                                            data-action-confirm-text="Restore Ownership Type"
                                        >
                                            <i class="fa-solid fa-rotate-left"></i>
                                            <span>Restore</span>
                                        </button>
                                    @endcan
                                    @can('forceDelete', $item)
                                        <button
                                            type="button"
                                            class="btn btn-error btn-sm btn-outline gap-2 flex-1 sm:flex-none"
                                            title="Delete permanently"
                                            data-admin-action
                                            data-action-url="{{ route('admin.ownership-types.force-delete', $item->id) }}"
                                            data-action-method="DELETE"
                                            data-action-type="danger"
                                            data-action-title="Delete Ownership Type Permanently"
                                            data-action-description="Permanently delete “{{ $item->name }}”. This action cannot be undone."
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
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-base-200 text-base-content/30">
                                    <i class="fa-solid fa-trash-can text-xl"></i>
                                </div>
                                <h3 class="mt-4 font-semibold text-base-content">
                                    {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'No matching deleted ownership types' : 'Ownership Types Trash is empty' }}
                                </h3>
                                <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">
                                    {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'Try adjusting your filters or clearing them to see more records.' : 'Deleted ownership types will appear here when moved to trash.' }}
                                </p>
                                @if (filled($search) || filled($deletedBy) || filled($deletedPeriod))
                                    <a href="{{ route('admin.ownership-types.trash') }}" class="btn btn-sm btn-outline mt-4 gap-2">
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
            @forelse ($ownershipTypes as $item)
                <article class="p-4 transition-colors hover:bg-base-200/20">
                    {{-- Identity --}}
                    <div class="flex items-start gap-3">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                            <i class="fa-solid fa-layer-group text-lg"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="break-words font-semibold text-base-content">{{ $item->name }}</h3>
                                <span class="badge badge-error badge-outline badge-sm">Deleted</span>
                            </div>
                            <p class="mt-1 break-all text-xs text-base-content/50">{{ $item->slug }}</p>
                            @if ($item->description)
                                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-base-content/60">
                                    {{ $item->description }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Deletion Information --}}
                    <div class="mt-4 flex items-start gap-3 rounded-lg border border-base-300/70 px-3 py-2.5">
                        <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-base-200 text-base-content/55">
                            <i class="fa-solid fa-user-shield text-sm"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-base-content/50">Deleted by</p>
                            <p class="mt-0.5 break-words text-sm font-medium text-base-content">
                                {{ $item->deleter?->name ?? 'Unknown user' }}
                                @if ($item->deleter?->role)
                                    <span class="font-normal text-base-content/50">
                                        · {{ $item->deleter->role }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-xs text-base-content/50">Deleted at</p>
                            <p class="mt-0.5 text-sm font-medium text-base-content">
                                {{ $item->deleted_at?->format('M d, Y') ?? '—' }}
                            </p>
                            <p class="mt-0.5 text-xs text-base-content/50">
                                {{ $item->deleted_at?->format('h:i A') }}
                            </p>
                        </div>
                    </div>

                    {{-- Mobile Actions --}}
                    <div class="mt-4 flex items-center gap-2 border-t border-base-300/70 pt-3">
                        @can('restore', $item)
                            <button
                                type="button"
                                class="btn btn-sm btn-success btn-outline min-w-0 flex-1 gap-2"
                                data-admin-action
                                data-action-url="{{ route('admin.ownership-types.restore', $item->id) }}"
                                data-action-method="PATCH"
                                data-action-type="success"
                                data-action-title="Restore Ownership Type"
                                data-action-description="Restore “{{ $item->name }}” and return it to the active ownership types."
                                data-action-icon="fa-solid fa-rotate-left"
                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                data-action-confirm-text="Restore Ownership Type"
                            >
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>Restore</span>
                            </button>
                        @endcan
                        @can('forceDelete', $item)
                            <button
                                type="button"
                                class="btn btn-sm btn-error btn-outline min-w-0 flex-1 gap-2"
                                data-admin-action
                                data-action-url="{{ route('admin.ownership-types.force-delete', $item->id) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Delete Ownership Type Permanently"
                                data-action-description="Permanently delete “{{ $item->name }}”. This action cannot be undone."
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
                        {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'No matching deleted ownership types' : 'Ownership Types Trash is empty' }}
                    </h3>
                    <p class="mx-auto mt-1 max-w-sm text-sm text-base-content/50">
                        {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'Try adjusting your filters or clearing them to see more records.' : 'Deleted ownership types will appear here when moved to trash.' }}
                    </p>
                    @if (filled($search) || filled($deletedBy) || filled($deletedPeriod))
                        <a href="{{ route('admin.ownership-types.trash') }}" class="btn btn-sm btn-outline mt-4 gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- ======================== PAGINATION ======================== --}}
        @if ($ownershipTypes->hasPages())
            <div class="border-t border-base-300 px-4 py-4 sm:px-5">
                {{ $ownershipTypes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
