
@extends('layouts.admin.app')

@section('title', 'Technology Categories Trash | CareerVault')
@section('page_title', 'Technology Categories / Trash')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- Header --}}
    <header>
        <x-admin.ui.page-header
            icon="fa-solid fa-trash-can text-lg"
            title="Technology Categories Trash"
            title-color="text-error"
            title-bg-color="bg-error/10 text-error"
            description="Deleted technology categories remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records."
            :back-route="route('admin.tech-categories.index')"
            backText="Back to Technology Categories"
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
    <details class="cv-section overflow-hidden rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="techCategoryTrashStates" open>
        <x-admin.ui.disclosure-summary
            title="Trash Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of deleted technology categories and deletion activity."
        />
        <div class="grid grid-cols-1 gap-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))] p-1 sm:grid-cols-3">
            <x-admin.ui.statistic
                title="Total in Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$totalTrashedTechCategories"
                total-description="Deleted technology categories"
                last-icon="fa-solid fa-database"
            />
            <x-admin.ui.statistic
                title="Deleted Today"
                title-icon="fa-solid fa-calendar-day"
                main-icon="fa-solid fa-calendar-check"
                main-icon-color="bg-warning/10 text-warning"
                :total="$deletedTodayTechCategories"
                total-description="Records deleted today"
                last-icon="fa-solid fa-clock"
            />
            <x-admin.ui.statistic
                title="Deleted This Week"
                title-icon="fa-solid fa-calendar-week"
                main-icon="fa-solid fa-clock-rotate-left"
                main-icon-color="bg-info/10 text-info"
                :total="$deletedThisWeekTechCategories"
                total-description="Records deleted in the last 7 days"
                last-icon="fa-solid fa-calendar"
            />
            <x-admin.ui.statistic
                title="Access Control"
                title-icon="fa-solid fa-shield-halved"
                main-icon="fa-solid fa-shield-halved"
                main-icon-color="bg-warning/10 text-warning"
                sp="Super Admin"
                total-description="Restore and permanent deletion permissions"
                last-icon="fa-solid fa-lock"
            />
        </div>
    </details>

    {{-- Filters --}}
    <x-admin.filters.panel
        title="Filter Deleted Technology Categories"
        description="Narrow deleted records by category name, deleting user, or deletion period."
        section="techCategoryTrashFilters"
        action="{{ route('admin.tech-categories.trash') }}"
        clear-url="{{ route('admin.tech-categories.trash') }}"
        result-label="deleted category"
        result-label-plural="deleted categories"
        summary="Deleted technology categories"
        gridClass="grid grid-cols-1 gap-4 sm:grid-cols-5"
        :count="$techCategories->count()"
        :total="$totalTrashedTechCategories"
        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search categories...',
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

    {{-- Trash Directory --}}
    <div class="mt-5 overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
        <div class="flex flex-col gap-3 border-b border-base-300 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                    <i class="fa-solid fa-trash-can text-lg"></i>
                </div>
                <div class="min-w-0">
                    <h2 class="font-semibold text-base-content">Deleted Technology Categories</h2>
                    <p class="mt-1 text-xs text-base-content/55">
                        Showing {{ $techCategories->firstItem() ?? 0 }}–{{ $techCategories->lastItem() ?? 0 }}
                        of {{ number_format($techCategories->total()) }} matching records
                    </p>
                </div>
            </div>
            <span class="badge badge-error badge-outline gap-1.5 self-start sm:self-center">
                <i class="fa-solid fa-trash-can"></i> Trash
            </span>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="table w-full">
                <thead>
                    <tr class="bg-base-200/60 text-xs uppercase tracking-wide text-base-content/60">
                        <th class="py-3 pl-5">Category</th>
                        <th>Technologies</th>
                        <th>Deleted At</th>
                        <th>Deleted By</th>
                        <th class="pr-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-300">
                    @forelse ($techCategories as $techCategory)
                        <tr class="transition-colors hover:bg-base-200/30">
                            <td class="max-w-xs py-4 pl-5">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-semibold text-base-content">{{ $techCategory->name }}</span>
                                        <p class="mt-0.5 text-xs text-base-content/50">{{ $techCategory->slug }}</p>
                                        @if ($techCategory->description)
                                            <p class="mt-1 line-clamp-2 max-w-md text-sm text-base-content/60">{{ $techCategory->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info badge-outline gap-1">
                                    <i class="fa-solid fa-code"></i>
                                    {{ number_format($techCategory->technologies_count ?? 0) }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-start gap-2">
                                    <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                        <i class="fa-solid fa-clock text-xs"></i>
                                    </div>
                                    <div class="whitespace-nowrap">
                                        <div class="text-sm font-bold">{{ $techCategory->deleted_at?->format('d M Y, h:i A') }}</div>
                                        <div class="text-xs text-base-content/50">{{ $techCategory->deleted_at?->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                @if ($techCategory->deletedBy)
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                            <i class="fa-solid fa-user-shield text-xs"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold" title="{{ $techCategory->deletedBy->name }}">
                                                {{ $techCategory->deletedBy->name }}
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40">
                                                {{ str_replace('_', ' ', $techCategory->deletedBy->role ?? '') }}
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
                            <td class="pr-5">
                                <div class="flex items-center gap-2 xl:justify-end">
                                    @can('restore', $techCategory)
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-success btn-outline gap-2"
                                            data-admin-action
                                            data-action-url="{{ route('admin.tech-categories.restore', $techCategory->slug) }}"
                                            data-action-method="PATCH"
                                            data-action-type="success"
                                            data-action-title="Restore Technology Category"
                                            data-action-description="Restore “{{ $techCategory->name }}” and return it to the active technology categories."
                                            data-action-icon="fa-solid fa-rotate-left"
                                            data-action-confirm-icon="fa-solid fa-rotate-left"
                                            data-action-confirm-text="Restore Category"
                                        >
                                            <i class="fa-solid fa-rotate-left"></i>
                                            <span>Restore</span>
                                        </button>
                                    @endcan

                                    @can('forceDelete', $techCategory)
                                        <button
                                            type="button"
                                            class="btn btn-error btn-sm btn-outline gap-2"
                                            data-admin-action
                                            data-action-url="{{ route('admin.tech-categories.force-delete', $techCategory->slug) }}"
                                            data-action-method="DELETE"
                                            data-action-type="danger"
                                            data-action-title="Delete Technology Category Permanently"
                                            data-action-description="Permanently delete “{{ $techCategory->name }}”. This action cannot be undone."
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
                                    {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'No matching deleted categories' : 'Technology Categories Trash is empty' }}
                                </h3>
                                <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">
                                    {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'Try adjusting your filters or clearing them to see more records.' : 'Deleted technology categories will appear here when moved to trash.' }}
                                </p>
                                @if (filled($search) || filled($deletedBy) || filled($deletedPeriod))
                                    <a href="{{ route('admin.tech-categories.trash') }}" class="btn btn-sm btn-outline mt-4 gap-2">
                                        <i class="fa-solid fa-rotate-left"></i> Clear Filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="divide-y divide-base-300 md:hidden">
            @forelse ($techCategories as $techCategory)
                <article class="p-4 transition-colors hover:bg-base-200/20">
                    <div class="flex items-start gap-3">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                            <i class="fa-solid fa-layer-group text-lg"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-semibold text-base-content">{{ $techCategory->name }}</span>
                                <span class="badge badge-error badge-outline badge-sm">Deleted</span>
                            </div>
                            <p class="mt-1 break-all text-xs text-base-content/50">{{ $techCategory->slug }}</p>
                            @if ($techCategory->description)
                                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-base-content/60">{{ $techCategory->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <div class="rounded-lg border border-base-300 bg-base-200/30 p-3">
                            <p class="flex items-center gap-1.5 text-xs text-base-content/55">
                                <i class="fa-solid fa-code"></i> Technologies
                            </p>
                            <p class="mt-1.5 text-sm font-semibold">{{ number_format($techCategory->technologies_count ?? 0) }}</p>
                        </div>
                        <div class="rounded-lg border border-base-300 bg-base-200/30 p-3">
                            <p class="flex items-center gap-1.5 text-xs text-base-content/55">
                                <i class="fa-solid fa-calendar"></i> Deleted At
                            </p>
                            <p class="mt-1.5 text-sm font-semibold">{{ $techCategory->deleted_at?->format('M d, Y') ?? '—' }}</p>
                            <p class="mt-0.5 text-xs text-base-content/50">{{ $techCategory->deleted_at?->format('h:i A') }}</p>
                        </div>
                    </div>

                    <div class="mt-3 flex items-start gap-3 rounded-lg border border-base-300/70 px-3 py-2.5">
                        <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-base-200 text-base-content/55">
                            <i class="fa-solid fa-user-shield text-sm"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-base-content/50">Deleted by</p>
                            <p class="mt-0.5 break-words text-sm font-medium text-base-content">
                                {{ $techCategory->deletedBy?->name ?? 'Unknown user' }}
                                @if ($techCategory->deletedBy?->role)
                                    <span class="font-normal text-base-content/50">· {{ $techCategory->deletedBy->role }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-2 border-t border-base-300/70 pt-3">
                        @can('restore', $techCategory)
                            <button
                                type="button"
                                class="btn btn-sm btn-success btn-outline min-w-0 flex-1 gap-2"
                                data-admin-action
                                data-action-url="{{ route('admin.tech-categories.restore', $techCategory->slug) }}"
                                data-action-method="PATCH"
                                data-action-type="success"
                                data-action-title="Restore Technology Category"
                                data-action-description="Restore “{{ $techCategory->name }}” and return it to the active technology categories."
                                data-action-icon="fa-solid fa-rotate-left"
                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                data-action-confirm-text="Restore Category"
                            >
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>Restore</span>
                            </button>
                        @endcan
                        @can('forceDelete', $techCategory)
                            <button
                                type="button"
                                class="btn btn-sm btn-error btn-outline min-w-0 flex-1 gap-2"
                                data-admin-action
                                data-action-url="{{ route('admin.tech-categories.force-delete', $techCategory->slug) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Delete Technology Category Permanently"
                                data-action-description="Permanently delete “{{ $techCategory->name }}”. This action cannot be undone."
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
                        {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'No matching deleted categories' : 'Technology Categories Trash is empty' }}
                    </h3>
                    <p class="mx-auto mt-1 max-w-sm text-sm text-base-content/50">
                        {{ filled($search) || filled($deletedBy) || filled($deletedPeriod) ? 'Try adjusting your filters or clearing them to see more records.' : 'Deleted technology categories will appear here when moved to trash.' }}
                    </p>
                    @if (filled($search) || filled($deletedBy) || filled($deletedPeriod))
                        <a href="{{ route('admin.tech-categories.trash') }}" class="btn btn-sm btn-outline mt-4 gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($techCategories->hasPages())
            <div class="border-t border-base-300 px-4 py-4 sm:px-5">
                {{ $techCategories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
